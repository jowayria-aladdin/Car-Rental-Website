<?php
header('Content-Type: application/json');

function getDbConnection() {
    $conn = new mysqli("localhost", "root", "", "car_rental");
    if ($conn->connect_error) {
        die(json_encode(["error" => "Database connection failed", "details" => $conn->connect_error]));
    }
    return $conn;
}

function checkReservationOverlap($conn, $car_id, $office_id, $pickup_date, $return_date) {
    $query = "
    SELECT * 
    FROM reservation 
    WHERE car_id = ? 
      AND office_id = ? 
      AND (
        (pickup_date BETWEEN ? AND ?) OR 
        (return_date BETWEEN ? AND ?) OR 
        (? BETWEEN pickup_date AND return_date) OR 
        (? BETWEEN pickup_date AND return_date)
      );
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("iissssss", $car_id, $office_id, $pickup_date, $return_date, $pickup_date, $return_date, $pickup_date, $return_date);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0;
}

function validateCarStatus($conn, $car_id, $office_id) {
    $query = "SELECT status, office_id FROM car WHERE car_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return ["error" => "Car not found"];
    }

    $car = $result->fetch_assoc();
    if ($car['status'] !== 'active') {
        return ["error" => "Car is out of service"];
    }

    if ($car['office_id'] != $office_id) {
        return ["error" => "Car is not available at the specified office"];
    }

    return true;
}
function handleGetRequest($conn) {
    if (!isset($_GET['field'])) {
        echo json_encode(["error" => "Field parameter missing"]);
        exit;
    }

    $field = $_GET['field'];
    $validFields = ['branch', 'car_brand', 'car_model', 'year', 'color', 'turbo', 'ccs'];

    if (!in_array($field, $validFields)) {
        echo json_encode(["error" => "Invalid field"]);
        exit;
    }

    $query = "";
    switch ($field) {
        case "branch":
            $query = "SELECT DISTINCT location AS value FROM office";
            break;
        case "car_brand":
            $query = "SELECT DISTINCT brand AS value FROM car";
            break;
        case "car_model":
            $query = "SELECT DISTINCT model AS value FROM car";
            break;
        case "year":
            $query = "SELECT DISTINCT year AS value FROM car";
            break;
        case "color":
            $query = "SELECT DISTINCT color AS value FROM car";
            break;
        case "turbo":
            $query = "SELECT DISTINCT turbo AS value FROM car";
            break;
        case "ccs":
            $query = "SELECT DISTINCT ccs AS value FROM car";
            break;
        default:
            echo json_encode(["error" => "Invalid field"]);
            exit;
    }

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $values = [];
        while ($row = $result->fetch_assoc()) {
            $values[] = $row['value'];
        }
        echo json_encode($values);
    } else {
        echo json_encode(["error" => "No data found for the requested field"]);
    }
}
function handlePostRequest($conn) {
    $conditions = [];
    $params = [];
    $types = "";

    // Check if email is provided
    if (!empty($_POST['email'])) {
        // Validate the email format
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["error" => "Invalid email address."]);
            exit;
        }

        $email = $_POST['email'];

        // Check if the email exists in the customer table
        $query = "SELECT customer_id FROM customer WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $customer = $result->fetch_assoc();
            $customer_id = $customer['customer_id']; // Use this customer_id
        } else {
            echo json_encode(["error" => "No customer found with this email."]);
            exit;
        }
    } else {
        echo json_encode(["error" => "Email is required."]);
        exit;
    }

    // Filtering based on car attributes
    if (!empty($_POST['branch'])) {
        $conditions[] = "o.location = ?";
        $params[] = $_POST['branch'];
        $types .= "s";
    }
    
    if (!empty($_POST['brand'])) {
        $conditions[] = "c.brand = ?";
        $params[] = $_POST['brand'];
        $types .= "s";
    }
    if (!empty($_POST['model'])) {
        $conditions[] = "c.model = ?";
        $params[] = $_POST['model'];
        $types .= "s";
    }
    if (!empty($_POST['year'])) {
        $conditions[] = "c.year = ?";
        $params[] = $_POST['year'];
        $types .= "i";
    }
    if (!empty($_POST['color'])) {
        $conditions[] = "c.color = ?";
        $params[] = $_POST['color'];
        $types .= "s";
    }
    if (!empty($_POST['turbo'])) {
        $conditions[] = "c.turbo = ?";
        $params[] = $_POST['turbo'];
        $types .= "i";
    }
    if (!empty($_POST['ccs'])) {
        $conditions[] = "c.ccs = ?";
        $params[] = $_POST['ccs'];
        $types .= "i";
    }
    
    // Query for cars with applied conditions
    $carQuery = "
    SELECT car_id, price 
    FROM car c
    WHERE c.status = 'active'
    ";
    
    if (!empty($conditions)) {
        $carQuery .= " AND " . implode(" AND ", $conditions);
    }

    $carStmt = $conn->prepare($carQuery);
    if (!empty($params)) {
        $carStmt->bind_param($types, ...$params);
    }
    $carStmt->execute();
    $carResult = $carStmt->get_result();

    if ($carResult->num_rows > 0) {
        $car = $carResult->fetch_assoc();
        $car_id = $car['car_id'];
        $payment_amount = $car['price']; // Get the price from the car table and use it as the payment amount
    } else {
        echo json_encode(["error" => "No car found based on the provided information."]);
        exit;
    }

    
    // Find office_id based on location
    if (!empty($_POST['location'])) {
        $location = $_POST['location'];
        $officeQuery = "SELECT office_id FROM office WHERE location = ?";
        $officeStmt = $conn->prepare($officeQuery);
        $officeStmt->bind_param("s", $location);
        $officeStmt->execute();
        $officeResult = $officeStmt->get_result();

        if ($officeResult->num_rows > 0) {
            $office = $officeResult->fetch_assoc();
            $office_id = $office['office_id'];
        } else {
            echo json_encode(["error" => "No office found with the specified location."]);
            exit;
        }
    } else {
        echo json_encode(["error" => "Location is required."]);
        exit;
    }

    // Validate car status and availability at the specified office
    $carStatus = validateCarStatus($conn, $car_id, $office_id);

    if ($carStatus !== true) {
        echo json_encode($carStatus);  // Will return an error if the car is inactive or not at the specified office
        exit;
    }

    // Validate pickup and return dates
    if (empty($_POST['pickup_date']) || empty($_POST['return_date'])) {
        echo json_encode(["error" => "Pickup and return dates are required."]);
        exit;
    }
    $pickup_date = $_POST['pickup_date'];
    $return_date = $_POST['return_date'];

    if (strtotime($pickup_date) <= time()) {
        echo json_encode(["error" => "Pickup date must be in the future."]);
        exit;
    }

    if (strtotime($return_date) <= strtotime($pickup_date)) {
        echo json_encode(["error" => "Return date must be after the pickup date."]);
        exit;
    }

    if (checkReservationOverlap($conn, $car_id, $office_id, $pickup_date, $return_date)) {
        echo json_encode(["error" => "This car is already reserved for the selected dates."]);
        exit;
    }

    // Calculate new reservation_id
    $result = $conn->query("SELECT COUNT(*) AS total FROM reservation");
    $row = $result->fetch_assoc();
    $reservation_id = $row['total'] + 1;

    // Insert reservation
    $insertReservationQuery = "
        INSERT INTO reservation (reservation_id, car_id, office_id, customer_id, reservation_date, pickup_date, return_date, status)
        VALUES (?, ?, ?, ?, NOW(), ?, ?, 'active')
    ";
    $stmt = $conn->prepare($insertReservationQuery);
    $stmt->bind_param("iiisss", $reservation_id, $car_id, $office_id, $customer_id, $pickup_date, $return_date);

    if (!$stmt->execute()) {
        echo json_encode(["error" => "Failed to create reservation.", "details" => $stmt->error]);
        exit;
    }

    // Calculate new payment_id
    $result = $conn->query("SELECT COUNT(*) AS total FROM payment");
    $row = $result->fetch_assoc();
    $payment_id = $row['total'] + 1;

    // Insert payment
    $insertPaymentQuery = "
        INSERT INTO payment (payment_id, reservation_id, reservation_date, payment_method, payment_amount)
        VALUES (?, ?, NOW(), ?, ?)
    ";
    $stmt = $conn->prepare($insertPaymentQuery);
    $stmt->bind_param("iisi", $payment_id, $reservation_id, $_POST['payment_method'], $payment_amount);

    if (!$stmt->execute()) {
        echo json_encode(["error" => "Failed to create payment.", "details" => $stmt->error]);
        exit;
    }

    echo json_encode([
        "success" => "Reservation and payment created successfully",
        "reservation_id" => $reservation_id,
        "payment_id" => $payment_id
    ]);
}


$conn = getDbConnection();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    handleGetRequest($conn);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handlePostRequest($conn);
} else {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
}

$conn->close();
?>
