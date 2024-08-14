<?php
// Include the Composer autoloader
require '../vendor/autoload.php';

// Include the Database class
require_once '../Database.php';

// Import Firebase JWT library
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

function authenticateAdmin() {
    $secretKey = 'M07gGoLVPCMAPuFvV2PLgFBFYH3lPb0Ov22jlxxcliX3PkBYXnXfFmXm76y5twn7'; // Replace with your actual secret key

    // Get all headers
    $headers = getallheaders();

    // Check if the 'Authorization' header is set
    if (isset($headers['Authorization'])) {
        $authHeader = $headers['Authorization'];
    } elseif (isset($headers['authorization'])) {
        $authHeader = $headers['authorization'];
    } else {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized', 'error' => 'Authorization header not found']);
        return;
    }

    // Extract the JWT from the Authorization header
    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $jwt = $matches[1];
    } else {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized', 'error' => 'Invalid Authorization header format']);
        return;
    }

    try {
        // Decode the JWT
        $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));

        // Check if the user is an admin
        if (empty($decoded->data->user_is_admin) || !$decoded->data->user_is_admin) {
            http_response_code(403); // Forbidden
            echo json_encode(['message' => 'Forbidden: Admin access required']);
            return;
        }

        // Check if the token has expired
        if ($decoded->exp < time()) {
            // Token is expired, so generate a new JWT
            $issuedAt = time();
            $expirationTime = $issuedAt + 3600; // New JWT valid for 1 hour
            $payload = [
                'iat' => $issuedAt,
                'exp' => $expirationTime,
                'iss' => $decoded->iss,
                'data' => $decoded->data
            ];

            $newJwt = JWT::encode($payload, $secretKey, 'HS256');

            // Return the new JWT
            echo json_encode([
                'message' => 'Token renewed',
                'access_token' => $newJwt
            ]);
            return;
        }

        // If the token is valid, not expired, and the user is an admin, return "Authorized"
        echo json_encode(['message' => 'Authorized']);

    } catch (ExpiredException $e) {
        // If the token is expired and not caught by the previous check
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized: Token expired']);
    } catch (Exception $e) {
        // If the token is invalid
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized', 'error' => $e->getMessage()]);
    }
}

// Call the function to handle the request
authenticateAdmin();
?>


