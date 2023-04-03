<?php

    $articles = json_decode(file_get_contents('articles.json'), true);

    // PDO : PHP DATA OBJECT => il va permettre de communiquer avec les basse de données
    $url = 'mysql:host=localhost:3306;dbname=blog';
    $user = 'root';
    $pwd = '';

    $pdo = new PDO($url, $user, $pwd);

    $requetSql = 'INSERT INTO article (title, category, content, image) VALUES (:title, :category, :content, :image)';
    $statement = $pdo->prepare($requetSql);

    foreach ($articles as $article) {
        $statement->bindValue(':title', $article['title']);
        $statement->bindValue(':category', $article['category']);
        $statement->bindValue(':content', $article['content']);
        $statement->bindValue(':image', $article['image']);
        $statement->execute();
    }