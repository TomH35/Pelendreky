<?php
// Include the Composer autoloader
require '../vendor/autoload.php';
require_once '../Database.php';

function getLatestArticleFromEachCategory() {
    // Initialize the database connection
    $db = connect_to_database();

    try {
        // Prepare the SQL query to get the latest article from each category
        $stmt = $db->query("
            SELECT 
                a.article_id, 
                a.article_title, 
                a.article_slug, 
                a.article_text, 
                a.image_url, 
                a.published_at, 
                c.category_id, 
                c.category_name, 
                c.category_slug
            FROM articles a
            JOIN categories c ON a.category_id = c.category_id
            WHERE a.status = 'published'
            GROUP BY a.category_id
            ORDER BY a.published_at DESC
        ");

        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        http_response_code(200);
        echo json_encode(['articles' => $articles]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
    } finally {
        close_database_connection($db);
    }
}

// Call the function to handle the request
getLatestArticleFromEachCategory();
?>
