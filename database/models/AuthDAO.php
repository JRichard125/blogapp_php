<?php 

    class AuthDAO {

        public PDOStatement $statementCreateUser; 
        public PDOStatement $statementReadUserFromUsername; 
        public PDOStatement $statementReadUserFromId; 
        public PDOStatement $statementCreateSession;
        public PDOStatement $statementReadSessionById;
        public PDOStatement $statementDeleteSession;

        function __construct(public PDO $pdo )
        {
            // User
            $this->statementCreateUser = $this->pdo->prepare(
                'INSERT INTO user VALUES (DEFAULT, :firstname, :lastname, :email, :password)'
            );
            $this->statementReadUserFromUsername = $this->pdo->prepare(
                'SELECT * FROM user WHERE email=:email'
            );
            $this->statementReadUserFromId = $this->pdo->prepare(
                'SELECT * FROM user WHERE id=:id'
            );
            //session
            $this->statementCreateSession= $this->pdo->prepare(
                'INSERT INTO session VALUES (:sessionid, :userid)'
            );
            $this->statementReadSessionById= $this->pdo->prepare(
                'SELECT * FROM session WHERE id=:id'
            );
            $this->statementDeleteSession =$this->pdo->prepare(
                'DELETE FROM session WHERE id=:id'
            );
        }


        function create($user) {
            $hashPassword = password_hash($user['password'], PASSWORD_ARGON2I);

            $this->statementCreateUser->bindValue(':firstname', $user['firstname']);
            $this->statementCreateUser->bindValue(':lastname', $user['lastname']);
            $this->statementCreateUser->bindValue(':email', $user['email']);
            $this->statementCreateUser->bindValue(':password', $hashPassword);
            $this->statementCreateUser->execute();
        }

        function getUser($email) {
            $this->statementReadUserFromUsername->bindValue(':email', $email);
            $this->statementReadUserFromUsername->execute();

            return $this->statementReadUserFromUsername->fetch();
        }

        function getUserById($userId) {
            $this->statementReadUserFromId->bindValue(':id', $userId);
            $this->statementReadUserFromId->execute();

            return $this->statementReadUserFromId->fetch();
        }

        function createSession($userId) {

            $sessionId = bin2hex(random_bytes(32));

            $this->statementCreateSession->bindValue(':sessionid', $sessionId);
            $this->statementCreateSession->bindValue(':userid', $userId);
            $this->statementCreateSession->execute();

            $signature = hash_hmac('sha256', $sessionId, 'formation dwwm');
            // on creer notre cookie
            setcookie('session', $sessionId, time() + 60 * 60 * 24 * 14, "", "", false, true);
            setcookie('signature', $signature, time() + 60 * 60 * 24 * 14, "", "", false, true);
        }

        function getSessionById($sessionId) {
            $this->statementReadSessionById->bindValue(':id', $sessionId);
            $this->statementReadSessionById->execute();
            return $this->statementReadSessionById->fetch();
        }

        function isLoggedIn() {

    
            $sessionId = $_COOKIE["session"] ?? '';
            $signature = $_COOKIE["signature"] ?? '';
    
            if($sessionId && $signature) {
                $hash = hash_hmac('sha256', $sessionId, 'formation dwwm');

                if(hash_equals($signature, $hash)) {
                    $session = $this->getSessionById($sessionId);
    
                    if($session) {
                        $user = $this->getUserById($session['userid']);
                    }
                }
            }
    
    
            return $user ?? false;
    
        }

        function logout($sessionId) {
            $this->statementDeleteSession->bindValue(':id', $sessionId);
            $this->statementDeleteSession->execute();
            setcookie('session', '', time() -1);
            setcookie('signature', '', time() -1);
        }
    }


    global $pdo;
    return new AuthDAO($pdo);
?>