<?php 

    $url = 'mysql:host=localhost:3306;dbname=blog';
    $user = 'root';
    $pwd = '';

    try {
        $pdo = new PDO($url, $user, $pwd, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } catch (PDOException $error) {
        echo "ERROR: ".$error->getMessage();
    }

    return $pdo;