<?php 

    class AuthDAO {

        public PDOStatement $statementCreate;
        public PDOStatement $statementRead;
        public PDOStatement $statementCreateSession;

        function __construct(public PDO $pdo )
        {
            $this->statementCreate = $this->pdo->prepare(
                'INSERT INTO user VALUES (DEFAULT, :firstname, :lastname, :email, :password)'
            );
            $this->statementRead = $this->pdo->prepare(
                'SELECT * FROM user WHERE email=:email'
            );
            $this->statementCreateSession= $this->pdo->prepare(
                'INSERT INTO session VALUES (DEFAULT , :userid)'
            );
        }


        function create($user) {
            $hashPassword = password_hash($user['password'], PASSWORD_ARGON2I);

            $this->statementCreate->bindValue(':firstname', $user['firstname']);
            $this->statementCreate->bindValue(':lastname', $user['lastname']);
            $this->statementCreate->bindValue(':email', $user['email']);
            $this->statementCreate->bindValue(':password', $hashPassword);
            $this->statementCreate->execute();
        }

        function getUser($email) {
            $this->statementRead->bindValue(':email', $email);
            $this->statementRead->execute();

            return $this->statementRead->fetch();
        }

        function createSession($userId) {
            $this->statementCreateSession->bindValue(':userid', $userId);
            $this->statementCreateSession->execute();

            // on recupere le id de la session qui vient d etre creer
            return $this->pdo->lastInsertId();
        }
    }


    $pdo = require_once './database/database.php';
    return new AuthDAO($pdo);
?>