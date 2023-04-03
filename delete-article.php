<?php 

    $pdo = require_once "./database/database.php";
    $statement = $pdo->prepare('DELETE FROM article WHERE id=:id');
    
    $articles = [];
    /**
     * @var ArticleDAO
     */
    $articleDAO = require_once './database/models/ArticleDAO.php';

    $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $idArticle = $_GET['id'] ?? '';


    if($idArticle) {
            $statement->bindValue(':id', $idArticle);
            $statement->execute();
        // $articleDAO->deleteOne()
        }
    header('Location: /');