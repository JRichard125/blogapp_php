<?php 
    
    require __DIR__.'/database/database.php';
    /**
     * @var AuthDAO
     */
    $authDAO = require './database/models/AuthDAO.php';

    $sessionId = $_COOKIE["session"];
    if($sessionId) {
        //supprimer la session de la bdd
        $authDAO->logout($sessionId);
        setcookie('session', '', time() -1);
        header('Location: /auth-login.php');
    }