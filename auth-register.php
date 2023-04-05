<?php 

    require __DIR__.'/database/database.php';
    /**
     * @var AuthDAO
     */
    $authDAO = require_once'./database/models/AuthDAO.php';

    const ERROR_REQUIRED = "Veuillez renseignez ce champ";
    const ERROR_EMAIL_INVALID = "L'email n'est pas valide";
    const ERROR_PASSWORD_MISMATCH = "les mots de passe sont differents";
    // const ERROR_PASSWORD_TOO_SHORT = 'Le mot de passe doit faire au moins 6 caracteres';

    $errors = [
        'firstname' => '',
        'lastname' => '',
        'email' => '',
        'password' => '',
        'confirmPassword' => ''
    ];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = filter_input_array(INPUT_POST, [
            'firstname' => FILTER_SANITIZE_SPECIAL_CHARS,
            'lastname' => FILTER_SANITIZE_SPECIAL_CHARS,
            'email' => FILTER_SANITIZE_EMAIL
        ]);

        $firstname = $input["firstname"] ?? '';
        $lastname = $input["lastname"] ?? '';
        $email = $input["email"] ?? '';
        $password = $_POST["password"] ?? '';
        $confirmPassword = $_POST["confirmPassword"] ?? '';

        if(!$firstname) {
            $errors['firstname'] = ERROR_REQUIRED;
        }

        if(!$lastname) {
            $errors['lastname'] = ERROR_REQUIRED;
        }

        if(!$email) {
            $errors['email'] = ERROR_REQUIRED;
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = ERROR_EMAIL_INVALID;
        }

        if(!$password) {
            $errors['password'] = ERROR_REQUIRED;
        }

        if(!$confirmPassword) {
            $errors['confirmPassword'] = ERROR_REQUIRED;
        } else if ($confirmPassword !== $password) {
            $errors['confirmPassword'] = ERROR_PASSWORD_MISMATCH;
        }


        if(empty(array_filter($errors, fn ($e) => $e !== ''))) {

            $newUser = [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'password' => $password,
            ];

            $authDAO->create($newUser);

            header('Location: /auth-login.php');
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="public/css/profile.css">
    <title>Sign up</title>
</head>
<body>
    <div class="container">
        <?php require_once 'includes/header.php' ?>
        <div class="content">
        <div class="block p-20 form-container">
                <h1>Inscription</h1>
                <form action="/auth-register.php" method="POST">
                    <div class="form-control">
                        <label for="firstname">Firstname</label>
                        <input type="text" name="firstname" id="firstname">
                        <?php if($errors['firstname']) : ?>
                            <p class="text-error"><?= $errors['firstname'] ?></p>
                        <?php endif ; ?>
                    </div>

                    <div class="form-control">
                        <label for="lastname">Lastname</label>
                        <input type="text" name="lastname" id="lastname">
                        <?php if($errors['lastname']) : ?>
                            <p class="text-error"><?= $errors['lastname'] ?></p>
                        <?php endif ; ?>
                    </div>

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

                    <div class="form-control">
                        <label for="confirmPassword">Confirm Password</label>
                        <input type="password" name="confirmPassword" id="confirmPassword">
                        <?php if($errors['confirmPassword']) : ?>
                            <p class="text-error"><?= $errors['confirmPassword'] ?></p>
                        <?php endif ; ?>
                    </div>

                    <div class="form-action">
                        <a href="/" class="btn btn-secondary">Annuler</a>
                        <button class="btn btn-primary" type="submit">S'inscrire</button>
                    </div>
                </form>
            </div>
        </div>
        <?php require_once 'includes/footer.php' ?>
    </div>
</body>
</html>