<?php
require '../vendor/autoload.php';
require_once '../Database.php';

function fetchArticles() {
    $db = connect_to_database();

    try {
        $stmt = $db->query("
            SELECT 
                articles.article_id,
                articles.article_title,
                articles.article_slug,
                articles.article_text,
                articles.category_id,
                articles.tags,
                articles.status,
                categories.category_name
            FROM articles
            LEFT JOIN categories ON articles.category_id = categories.category_id
            ORDER BY categories.category_name, articles.article_title
        ");
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['articles' => $articles]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
    } finally {
        close_database_connection($db);
    }
}

fetchArticles();
?>
