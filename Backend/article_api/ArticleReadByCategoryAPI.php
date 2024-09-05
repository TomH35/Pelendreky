<?php
// Include database connection
require_once '../Database.php';

function readArticlesByCategory() {
    $db = connect_to_database();

    // Get category_slug from the query parameters
    $category_slug = $_GET['category_slug'] ?? '';

    try {
        // Query to get articles and category name by category_slug
        $stmt = $db->prepare("
            SELECT c.category_name, a.article_id, a.article_title, a.article_slug, a.article_text, a.image_url
            FROM articles a
            JOIN categories c ON a.category_id = c.category_id
            WHERE c.category_slug = :category_slug
        ");
        $stmt->bindParam(':category_slug', $category_slug);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($results)) {
            echo json_encode(['message' => 'No articles found for this category.']);
            return;
        }

        // Extract category name from the first result
        $category_name = $results[0]['category_name'];

        // Remove category_name from individual article records (since it's redundant)
        $articles = array_map(function($article) {
            unset($article['category_name']);
            return $article;
        }, $results);

        echo json_encode([
            'category_name' => $category_name,
            'articles' => $articles
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
    } finally {
        close_database_connection($db);
    }
}

readArticlesByCategory();
?>