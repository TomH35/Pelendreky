<?php
// Include the Composer autoloader
require '../vendor/autoload.php';

// Include the Database class
require_once '../Database.php';

// Import Firebase JWT library
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function AdminLogin() {
    // Initialize the database connection
    $db = connect_to_database();

    // Get the incoming JSON payload
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Validate the required fields
    if (!isset($data['email'], $data['password'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid input']);
        return;
    }

    $email = $data['email'];
    $password = $data['password'];

    try {
        // Check if the user exists and is an admin
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND user_is_admin = 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            http_response_code(401);
            echo json_encode(['message' => 'The entered email or password is incorrect']);
            return;
        }

        // Verify the password
        if (!password_verify($password, $user['password'])) {
            http_response_code(401);
            echo json_encode(['message' => 'The entered email or password is incorrect']);
            return;
        }

        // Generate JWT
        $secretKey = 'M07gGoLVPCMAPuFvV2PLgFBFYH3lPb0Ov22jlxxcliX3PkBYXnXfFmXm76y5twn7';
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600; // JWT valid for 1 hour
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'iss' => 'your_domain.com', // Replace with your domain
            'data' => [
                'user_id' => $user['user_id'],
                'email' => $user['email'],
                'name' => $user['name'],
                'surname' => $user['surname'],
                'user_is_admin' => $user['user_is_admin'] // Include user_is_admin in the JWT
            ]
        ];

        $jwt = JWT::encode($payload, $secretKey, 'HS256');

        // Return the JWT
        echo json_encode(['access_token' => $jwt]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
    } finally {
        // Close the database connection
        close_database_connection($db);
    }
}

// Call the function to handle the request
AdminLogin();
?>
