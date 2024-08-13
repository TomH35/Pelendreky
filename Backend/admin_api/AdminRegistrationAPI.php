<?php
// Include the Composer autoloader
require '../vendor/autoload.php';

// Include the Database class
require_once '../Database.php';

function AdminRegistration() {
    // Initialize the database connection
    $db = connect_to_database();

    // Get the incoming JSON payload
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Validate the required fields
    if (!isset($data['meno'], $data['priezvisko'], $data['email'], $data['password'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid input']);
        return;
    }

    $meno = $data['meno'];
    $priezvisko = $data['priezvisko'];
    $email = $data['email'];
    $password = $data['password'];

    try {
        // Check if the email already exists
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            http_response_code(400);
            echo json_encode(['message' => 'Email is already registered']);
            return;
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new admin into the database
        $query = "INSERT INTO users (name, surname, email, password, created_at, user_is_admin) VALUES (:name, :surname, :email, :password, NOW(), :user_is_admin)";
        $stmt = $db->prepare($query);
        $result = $stmt->execute([
            'name' => $meno,
            'surname' => $priezvisko,
            'email' => $email,
            'password' => $hashedPassword,
            'user_is_admin' => 1 // 1 for `user_is_admin` to set admin flag to true
        ]);

        if ($result) {
            http_response_code(201);
            echo json_encode(['message' => 'Admin registered successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to register admin']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
    } finally {
        // Close the database connection
        close_database_connection($db);
    }
}

// Call the function to handle the request
AdminRegistration();
?>


