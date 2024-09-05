<?php
// Include the Composer autoloader
require '../vendor/autoload.php';

// Include the Database class
require_once '../Database.php';

function CreateArticle() {
    // Initialize the database connection
    $db = connect_to_database();

    // Set the target directory for image uploads
    $targetDir = '../public/ArticleImages/';
    $imageUrl = null;

    // Check if an image file was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageFile = $_FILES['image'];

        // Generate a unique file name to avoid overwriting existing files
        $imageFileName = uniqid() . '-' . basename($imageFile['name']);
        $targetFilePath = $targetDir . $imageFileName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($imageFile['tmp_name'], $targetFilePath)) {
            // Store the relative path to the image in the database
            $imageUrl = '/Backend/public/ArticleImages/' . $imageFileName;
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to upload image']);
            return;
        }
    }

    // Get the other form data
    $articleTitle = $_POST['article_title'] ?? '';
    $articleSlug = $_POST['article_slug'] ?? '';
    $articleText = $_POST['article_text'] ?? '';
    $categoryId = $_POST['category_id'] ?? null;
    $tags = $_POST['tags'] ?? null;
    $status = $_POST['status'] ?? 'draft';
    $userId = $_POST['user_id'] ?? null;

    // Validate the required fields
    if (empty($articleTitle) || empty($articleSlug) || empty($articleText) || empty($status) || empty($userId)) {
        http_response_code(400);
        echo json_encode(['message' => 'Invalid input: Title, slug, text, status, and user ID are required']);
        return;
    }

    try {
        // Insert the new article into the database
        $stmt = $db->prepare("
            INSERT INTO articles 
            (article_title, article_slug, article_text, user_id, category_id, image_url, tags, status, published_at) 
            VALUES (:article_title, :article_slug, :article_text, :user_id, :category_id, :image_url, :tags, :status, :published_at)
        ");

        $publishedAt = ($status === 'published') ? date('Y-m-d H:i:s') : null;

        $stmt->bindParam(':article_title', $articleTitle);
        $stmt->bindParam(':article_slug', $articleSlug);
        $stmt->bindParam(':article_text', $articleText);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':image_url', $imageUrl);
        $stmt->bindParam(':tags', $tags);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':published_at', $publishedAt);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['message' => 'Article created successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to create article']);
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
CreateArticle();
?>