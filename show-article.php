<?php

$articleDAO = require_once './database/models/ArticleDAO.php';

$filename = __DIR__.'/data/articles.json';
$articles = [];
$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$idArticle = $_GET['id'] ?? '';

if(!$idArticle){
    header('Location: /');
} else {
        // $pdo = require_once './database/database.php';
        // $statement = $pdo->prepare('SELECT * FROM article WHERE id=:id');
        // $statement->bindValue(':id', $idArticle);
        // $statement->execute();
        // $article = $statement->fetch(); supprimer ce qu il ya endessous
        // $article = $articleDAO->getOne($idArticle);
        if(file_exists($filename)) {
            $articles = json_decode(file_get_contents($filename), true) ?? [];

            // on cherche l'index de l'article correspondant a l'id recuperer
            $articleIndex = array_search($idArticle, array_column($articles, 'id'));
            $article = $articles[$articleIndex];
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="public/css/show-article.css">
    <title>Article</title>
</head>
<body>
    <div class="container">
        <?php require_once 'includes/header.php' ?>
        <div class="content">
            <div class="article-container">
                <a href="/" class="article-back"><<< Retour a la liste des articles</a>
                <div class="article-cover-img" style="background-image: url(<?= $article['image'] ?>);" ></div>
                <h1 class="article-title"><?= $article['title'] ?></h1>
                <div class="separator"></div>
                <p class="article-content"><?= $article['content'] ?></p>
                <div class="action">
                    <a href="/form-article.php?id=<?= $article['id'] ?>" class="btn btn-primary">Editer l'article</a>
                    <a href="/delete-article.php?id=<?= $article['id'] ?>" class="btn btn-secondary">Supprimer l'article</a>
                </div>
            </div>
        </div>
        <?php require_once 'includes/footer.php' ?>
    </div>
    
</body>
</html>