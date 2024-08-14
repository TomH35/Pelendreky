<?php
require '../vendor/autoload.php';
require_once '../Database.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function AdminLogout() {
    $secretKey = 'M07gGoLVPCMAPuFvV2PLgFBFYH3lPb0Ov22jlxxcliX3PkBYXnXfFmXm76y5twn7';

    // Get all headers
    $headers = getallheaders();

    // Debug: Output headers to verify they are being received correctly
    // echo json_encode($headers); exit;

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

        // Simulate logout by invalidating the token (you can also implement a token blacklist if needed)
        http_response_code(200);
        echo json_encode(['message' => 'Logged out successfully']);
    } catch (Exception $e) {
        // If the token is invalid or decoding fails
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized', 'error' => $e->getMessage()]);
    }
}

// Call the function to handle the request
AdminLogout();

?>


