<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "car_rental");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brand = $_POST['car_brand'];
    $model = $_POST['car_model'];
    $year = $_POST['year'];
    $color = $_POST['color'];
    $turbo = $_POST['turbo'] === 'yes' ? 1 : 0;
    $ccs = $_POST['ccs'];
    $branch = $_POST['branch'];
    $status = $_POST['status'];
    $plate_id = $_POST['plate'];
    $price = $_POST['price'];

    try {
        // Validate and process
        if (empty($brand) || empty($model) || empty($year) || empty($price)) {
            throw new Exception("All fields are required");
        }

        // Get office_id
        $office_query = "SELECT office_id FROM office WHERE location = ?";
        $stmt = $conn->prepare($office_query);
        $stmt->bind_param("s", $branch);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("Invalid branch location");
        }
        
        $row = $result->fetch_assoc();
        $office_id = $row['office_id'];
        $stmt->close();

        // Insert car
        $sql = "INSERT INTO car (brand, model, year, color, turbo, ccs, office_id, status, plate_id, price) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if (!($stmt = $conn->prepare($sql))) {
            throw new Exception("Query preparation failed");
        }

        $stmt->bind_param("ssissiissd", $brand, $model, $year, $color, $turbo, $ccs, $office_id, $status, $plate_id, $price);
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to add car: " . $stmt->error);
        }
        
        $stmt->close();
        echo json_encode(["success" => true, "message" => "Car added successfully!"]);
        
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }

    $conn->close();
}
?>