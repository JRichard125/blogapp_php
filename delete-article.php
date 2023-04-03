<?php 

    $pdo = require_once "./database/database.php";
    $statement = $pdo->prepare('DELETE FROM article WHERE id=:id');
    
    $filename = __DIR__.'/data/articles.json'; // a delete
    $articles = [];
    /**
     * @var ArticleDAO
     */
    $articleDAO = require_once './database/models/ArticleDAO.php';

    $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $idArticle = $_GET['id'] ?? '';


    // if($idArticle) {
        //     $statement->bindValue(':id', $idArticle);
        //     $statement->execute();
        // $articleDAO->deleteOne()
        // }
        // header('Location: /');
        
        
        
        // tout est deletable
    if(!$idArticle) {
        header('Location: /');
    } else {
        if(file_exists($filename)) {
            // on recupere les article depuis articles .json
            $articles = json_decode(file_get_contents($filename), true) ?? [];
            // on recupere l'index correspondant a l'article ayant l'id sur lequel on est
            $articleIndex = array_search($idArticle, array_column($articles, 'id'));
            // on supprime cet article
            array_splice($articles, $articleIndex, 1);
            // on enregistre dans articles.json
            file_put_contents($filename, json_encode($articles));
            header('Location: /');
        }
    }