<?php
require '../vendor/autoload.php';
require_once '../Database.php';

function deleteArticle() {
    $db = connect_to_database();

    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    $articleId = $data['article_id'] ?? null;

    if (!$articleId) {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid input']);
        return;
    }

    try {
        // Fetch the article to get the image URL
        $stmt = $db->prepare("SELECT image_url FROM articles WHERE article_id = :article_id");
        $stmt->bindParam(':article_id', $articleId, PDO::PARAM_INT);
        $stmt->execute();
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$article) {
            http_response_code(404);
            echo json_encode(['message' => 'Article not found']);
            return;
        }

        $imageUrl = $article['image_url'];

        // Replace './Backend' with '..' to correctly resolve the path
        $filePath = str_replace('/Backend', '..', $imageUrl);
        $filePath = realpath($filePath);
        
        // Debugging output
        error_log('Attempting to delete file: ' . $filePath);

        // Delete the article from the database
        $stmt = $db->prepare("DELETE FROM articles WHERE article_id = :article_id");
        $stmt->bindParam(':article_id', $articleId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Delete the image file if it exists
            if ($filePath && file_exists($filePath)) {
                if (unlink($filePath)) {
                    error_log('File deleted successfully');
                } else {
                    error_log('Failed to delete file');
                }
            } else {
                error_log('File does not exist: ' . $filePath);
            }
            http_response_code(200);
            echo json_encode(['message' => 'Article deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to delete article']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
    } finally {
        close_database_connection($db);
    }
}

deleteArticle();
?>