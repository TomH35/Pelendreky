<?php
require '../vendor/autoload.php';
require_once '../Database.php';

function updateArticle() {
    $db = connect_to_database();

    // Set the target directory for image uploads
    $targetDir = '../public/ArticleImages/';
    $newImageUrl = null;

    // Check if an image file was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageFile = $_FILES['image'];

        // Generate a unique file name to avoid overwriting existing files
        $imageFileName = uniqid() . '-' . basename($imageFile['name']);
        $targetFilePath = $targetDir . $imageFileName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($imageFile['tmp_name'], $targetFilePath)) {
            // Store the relative path to the new image in the database
            $newImageUrl = '/Backend/public/ArticleImages/' . $imageFileName;
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to upload image']);
            return;
        }
    }

    $articleId = $_POST['article_id'] ?? null;
    $articleTitle = $_POST['article_title'] ?? '';
    $articleSlug = $_POST['article_slug'] ?? '';
    $articleText = $_POST['article_text'] ?? '';
    $categoryId = $_POST['category_id'] ?? null;
    $tags = $_POST['tags'] ?? '';
    $status = $_POST['status'] ?? 'draft';

    if (!$articleId || empty($articleTitle) || empty($articleSlug) || empty($articleText) || empty($status)) {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid input']);
        return;
    }

    try {
        // Fetch the old image URL if a new image was uploaded
        $oldImageUrl = null;
        if ($newImageUrl) {
            $stmt = $db->prepare("SELECT image_url FROM articles WHERE article_id = :article_id");
            $stmt->bindParam(':article_id', $articleId, PDO::PARAM_INT);
            $stmt->execute();
            $article = $stmt->fetch(PDO::FETCH_ASSOC);
            $oldImageUrl = $article['image_url'];
        }

        // Update the article in the database
        $stmt = $db->prepare("
            UPDATE articles 
            SET article_title = :article_title, 
                article_slug = :article_slug, 
                article_text = :article_text, 
                category_id = :category_id, 
                tags = :tags, 
                status = :status,
                image_url = COALESCE(:new_image_url, image_url)
            WHERE article_id = :article_id
        ");

        $stmt->bindParam(':article_title', $articleTitle);
        $stmt->bindParam(':article_slug', $articleSlug);
        $stmt->bindParam(':article_text', $articleText);
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':tags', $tags);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':new_image_url', $newImageUrl);
        $stmt->bindParam(':article_id', $articleId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // If a new image was uploaded, delete the old one
            if ($oldImageUrl) {
                $filePath = str_replace('/Backend', '..', $oldImageUrl);
                $filePath = realpath($filePath);
                
                error_log('Attempting to delete file: ' . $filePath); // Debugging line

                if ($filePath && file_exists($filePath)) {
                    if (unlink($filePath)) {
                        error_log('Old file deleted successfully');
                    } else {
                        error_log('Failed to delete old file');
                    }
                } else {
                    error_log('Old file does not exist: ' . $filePath);
                }
            }
            http_response_code(200);
            echo json_encode(['message' => 'Article updated successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to update article']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
    } finally {
        close_database_connection($db);
    }
}

updateArticle();
?>