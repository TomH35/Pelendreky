<?php
// Include the Composer autoloader
require '../vendor/autoload.php';

// Include the Database class
require_once '../Database.php';

function ReadTopArticlesByCategory() {
    // Initialize the database connection
    $db = connect_to_database();

    // Get the category_slug from the query string
    $category_slug = isset($_GET['category_slug']) ? $_GET['category_slug'] : null;

    if (!$category_slug) {
        http_response_code(400);
        echo json_encode(['message' => 'category_slug is required']);
        return;
    }

    try {
        // Retrieve the category by slug
        $stmt = $db->prepare("SELECT category_id, category_name FROM categories WHERE category_slug = :category_slug");
        $stmt->bindParam(':category_slug', $category_slug);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$category) {
            http_response_code(404);
            echo json_encode(['message' => 'Category not found']);
            return;
        }

        $category_id = $category['category_id'];
        $category_name = $category['category_name'];

        // Fetch the top 5 most popular articles in this category by article_view_count
        $stmt = $db->prepare("
            SELECT article_id, article_title, article_slug, article_text, image_url, article_view_count
            FROM articles
            WHERE category_id = :category_id
            ORDER BY article_view_count DESC
            LIMIT 5
        ");
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        http_response_code(200);
        echo json_encode([
            'category_name' => $category_name,
            'articles' => $articles
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
    } finally {
        // Close the database connection
        close_database_connection($db);
    }
}

// Call the function to handle the request
ReadTopArticlesByCategory();
?>
