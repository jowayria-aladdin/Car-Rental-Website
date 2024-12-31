<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "car_rental");

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}

// Handle GET requests for dropdown values
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $field = $_GET['field'];
    $validFields = ['branch', 'brand', 'model', 'year', 'color', 'turbo', 'ccs'];

    if (!in_array($field, $validFields)) {
        echo json_encode(["error" => "Invalid field"]);
        exit;
    }

    $query = "";
    switch ($field) {
        case "branch":
            $query = "SELECT DISTINCT location AS value FROM office";
            break;
        case "brand":
            $query = "SELECT DISTINCT brand AS value FROM car";
            break;
        case "model":
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
    }

    $result = $conn->query($query);
    $values = [];
    while ($row = $result->fetch_assoc()) {
        $values[] = $row['value'];
    }

    echo json_encode($values);
}
// Handle POST requests for search results
else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conditions = [];
    $params = [];
    $types = "";

    if (!empty($_POST['location'])) {
        $conditions[] = "o.location = ?";
        $params[] = $_POST['location'];
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

    $query = "SELECT c.*, o.location 
              FROM car c 
              JOIN office o ON c.office_id = o.office_id 
              WHERE c.status = 'active'";
    
    if (!empty($conditions)) {
        $query .= " AND " . implode(" AND ", $conditions);
    }

    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $cars = [];
    while ($row = $result->fetch_assoc()) {
        $cars[] = $row;
    }
    
    echo json_encode($cars);
    $stmt->close();
}

$conn->close();
?>