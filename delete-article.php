<?php 

    $articles = [];
    /**
     * @var ArticleDAO
     */
    $articleDAO = require_once './database/models/ArticleDAO.php';

    $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $idArticle = $_GET['id'] ?? '';


    if($idArticle) {
        $articleDAO->deleteOne($idArticle);
    }
    header('Location: /');