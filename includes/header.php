<?php 
    $currentUser = $currentUser ?? false;
?>

<header>
    <a href="/" class="logo">BLOGAPP</a>
    <ul class="header-menu">

        <?php if($currentUser) : ?>
            <li class="<?= $_SERVER['REQUEST_URI'] === '/form-article.php' ? 'active' : '' ?>">
                <a href="/form-article.php">Ecrire un article</a>
            </li>

            <li class="<?= $_SERVER['REQUEST_URI'] === '/profile.php' ? 'active' : '' ?>">
                <a href="/profile.php">Ma page</a>
            </li>

            <li class="<?= $_SERVER['REQUEST_URI'] === '/auth-logout.php' ? 'active' : '' ?>">
                <a href="/auth-logout.php">Logout</a>
            </li>
        <?php else : ?>
            <li class="<?= $_SERVER['REQUEST_URI'] === '/auth-login.php' ? 'active' : '' ?>">
                <a href="/auth-login.php">Login</a>
            </li>
        
            <li class="<?= $_SERVER['REQUEST_URI'] === '/auth-register.php' ? 'active' : '' ?>">
                <a href="/auth-register.php">Inscription</a>
            </li>
        <?php endif; ?>

        
        
    </ul>
</header>