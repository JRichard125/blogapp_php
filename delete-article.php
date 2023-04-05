<?php 

    require __DIR__.'/database/database.php';
    $authDAO = require './database/models/AuthDAO.php';
    $currentUser= $authDAO->isLoggedIn();

    if(!$currentUser) {
        header('Location: /auth-login.php');
    }else {
        /**
         * @var ArticleDAO
         */
        $articleDAO = require_once './database/models/ArticleDAO.php';
        $articles = [];

        $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $idArticle = $_GET['id'] ?? '';


        if($idArticle) {
            $articleDAO->deleteOne($idArticle);
        }
        header('Location: /');
    }

    