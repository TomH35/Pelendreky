<?php
// Include the Composer autoloader
require '../vendor/autoload.php';

// Include the Database class
require_once '../Database.php';

function ReadArticleBySlug() {
    // Initialize the database connection
    $db = connect_to_database();

    // Get category_slug and article_slug from the query parameters
    $category_slug = $_GET['category_slug'] ?? '';
    $article_slug = $_GET['article_slug'] ?? '';

    if (empty($category_slug) || empty($article_slug)) {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid request: Missing slugs']);
        return;
    }

    try {
        // Fetch the article based on the slugs
        $stmt = $db->prepare("
            SELECT a.article_id, a.article_title, a.article_text, a.published_at, a.image_url, a.tags
            FROM articles a
            JOIN categories c ON a.category_id = c.category_id
            WHERE a.article_slug = :article_slug AND c.category_slug = :category_slug
        ");
        $stmt->bindParam(':article_slug', $article_slug);
        $stmt->bindParam(':category_slug', $category_slug);
        $stmt->execute();
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($article) {
            http_response_code(200);
            echo json_encode(['article' => $article]);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Article not found']);
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
ReadArticleBySlug();
?>
