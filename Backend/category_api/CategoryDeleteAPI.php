<?php
// Include the Composer autoloader
require '../vendor/autoload.php';

// Include the Database class
require_once '../Database.php';

function DeleteCategory() {
    // Initialize the database connection
    $db = connect_to_database();

    // Get the incoming JSON payload
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Validate the required fields
    if (!isset($data['category_id']) || !is_numeric($data['category_id'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid input: Category ID is required']);
        return;
    }

    $categoryId = $data['category_id'];

    try {
        // Delete the category by ID
        $stmt = $db->prepare("DELETE FROM categories WHERE category_id = :category_id");
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode(['message' => 'Category deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Category not found']);
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
DeleteCategory();
?>