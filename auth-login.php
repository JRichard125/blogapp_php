<?php 


    /**
     * @var AuthDAO
     */
    $authDAO = require_once'./database/models/AuthDAO.php';

    const ERROR_REQUIRED = "Veuillez renseignez ce champ";
    const ERROR_EMAIL_INVALID = "L'email n'est pas valide";
    const ERROR_EMAIL_UNKNOWN = "Cet email n'existe pas";
    const ERROR_PASSWORD_MISMATCH = "L'email et/ou  le mot de passe sont erroné";

    $errors = [
        'email' => '',
        'password' => '',
    ];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = filter_input_array(INPUT_POST, [
            'email' => FILTER_SANITIZE_EMAIL
        ]);

        $email = $input["email"] ?? '';
        $password = $_POST["password"] ?? '';


        if(!$email) {
            $errors['email'] = ERROR_REQUIRED;
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = ERROR_EMAIL_INVALID;
        }

        if(!$password) {
            $errors['password'] = ERROR_REQUIRED;
        }



        if(empty(array_filter($errors, fn ($e) => $e !== ''))) {
            // logger l'utilisateur
            $user = $authDAO->getUser($email);

            if(!$user) {
                $errors['email'] = ERROR_EMAIL_UNKNOWN;
            } else {
                if(!password_verify($password, $user['password'])) {
                    $errors['password'] = ERROR_PASSWORD_MISMATCH;
                } else {
                    // on cree une nouvelle session
                    $sessionId = $authDAO->createSession($user['id']);

                    // on creer notre cookie
                    setcookie('session', $sessionId, time() + 60 * 60 * 24 * 14, "", "", false, true);

                    header('Location: /');
                }
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="public/css/profile.css">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <?php require_once 'includes/header.php' ?>
        <div class="content">
        <div class="block p-20 form-container">
                <h1>Se connecter</h1>
                <form action="/auth-login.php" method="POST">
                    <div class="form-control">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email">
                        <?php if($errors['email']) : ?>
                            <p class="text-error"><?= $errors['email'] ?></p>
                        <?php endif ; ?>
                    </div>

                    <div class="form-control">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password">
                        <?php if($errors['password']) : ?>
                            <p class="text-error"><?= $errors['password'] ?></p>
                        <?php endif ; ?>
                    </div>

                    <div class="form-action">
                        <a href="/" class="btn btn-secondary">Annuler</a>
                        <button class="btn btn-primary" type="submit">Se connecter</button>
                    </div>
                </form>
            </div>
        </div>
        <?php require_once 'includes/footer.php' ?>
    </div>
</body>
</html>