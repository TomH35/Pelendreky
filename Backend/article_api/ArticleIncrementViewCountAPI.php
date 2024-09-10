<?php
// Include the Composer autoloader
require '../vendor/autoload.php';

// Include the Database class
require_once '../Database.php';

function IncrementArticleViewCount() {
    // Initialize the database connection
    $db = connect_to_database();

    // Get the incoming JSON payload
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Validate the required field
    if (!isset($data['article_slug'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid input: article_slug is required']);
        return;
    }

    $article_slug = $data['article_slug'];

    try {
        // Increment the view count for the article
        $stmt = $db->prepare("UPDATE articles SET article_view_count = article_view_count + 1 WHERE article_slug = :article_slug");
        $stmt->bindParam(':article_slug', $article_slug, PDO::PARAM_STR);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(['message' => 'View count incremented successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to increment view count']);
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
IncrementArticleViewCount();
?>
