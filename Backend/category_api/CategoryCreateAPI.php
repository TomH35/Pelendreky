<?php
// Include the Composer autoloader
require '../vendor/autoload.php';

// Include the Database class
require_once '../Database.php';

function CreateCategory() {
    // Initialize the database connection
    $db = connect_to_database();

    // Get the incoming JSON payload
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Validate the required fields
    if (!isset($data['categoryName']) || empty(trim($data['categoryName'])) || !isset($data['categorySlug']) || empty(trim($data['categorySlug']))) {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid input: Category name and slug are required']);
        return;
    }

    $categoryName = trim($data['categoryName']);
    $categorySlug = trim($data['categorySlug']);

    try {
        // Check if the category or slug already exists
        $stmt = $db->prepare("SELECT * FROM categories WHERE category_name = :category_name OR category_slug = :category_slug");
        $stmt->execute([
            ':category_name' => $categoryName,
            ':category_slug' => $categorySlug
        ]);
        $existingCategory = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingCategory) {
            http_response_code(409);
            echo json_encode(['message' => 'Category or slug already exists']);
            return;
        }

        // Insert the new category into the database
        $stmt = $db->prepare("INSERT INTO categories (category_name, category_slug) VALUES (:category_name, :category_slug)");
        $stmt->execute([
            ':category_name' => $categoryName,
            ':category_slug' => $categorySlug
        ]);

        http_response_code(201);
        echo json_encode(['message' => 'Category created successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
    } finally {
        // Close the database connection
        close_database_connection($db);
    }
}

// Call the function to handle the request
CreateCategory();
?>