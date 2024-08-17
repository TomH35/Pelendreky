<?php
// Include the Composer autoloader
require '../vendor/autoload.php';

// Include the Database class
require_once '../Database.php';

function UpdateCategory() {
    // Initialize the database connection
    $db = connect_to_database();

    // Get the incoming JSON payload
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Validate the required fields
    if (!isset($data['category_id']) || !isset($data['category_name']) || !isset($data['category_slug'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid input: All fields are required']);
        return;
    }

    $categoryId = $data['category_id'];
    $categoryName = trim($data['category_name']);
    $categorySlug = trim($data['category_slug']);

    try {
        // Update the category in the database
        $stmt = $db->prepare("UPDATE categories SET category_name = :category_name, category_slug = :category_slug WHERE category_id = :category_id");
        $stmt->bindParam(':category_name', $categoryName);
        $stmt->bindParam(':category_slug', $categorySlug);
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(['message' => 'Category updated successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to update category']);
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
UpdateCategory();
?>