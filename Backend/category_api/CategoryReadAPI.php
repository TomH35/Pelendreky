<?php
// Include the Composer autoloader
require '../vendor/autoload.php';

// Include the Database class
require_once '../Database.php';

function ReadCategories() {
    // Initialize the database connection
    $db = connect_to_database();

    try {
        // Retrieve all categories
        $stmt = $db->query("SELECT category_id, category_name, category_slug FROM categories");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        http_response_code(200);
        echo json_encode(['categories' => $categories]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
    } finally {
        // Close the database connection
        close_database_connection($db);
    }
}

// Call the function to handle the request
ReadCategories();
?>