<?php 
    $filename = __DIR__.'/data/articles.json';
    $articles = [];
    $categories = [];
    $selectedCategory = '';


    $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $selectedCategory = $_GET['cat'] ?? '';

    if(file_exists($filename)) {
        $articles = json_decode(file_get_contents($filename), true) ?? [];
        $catmap = array_map(fn ($article) => $article['category'], $articles);
        $categories = array_reduce($catmap, function ($acc, $cat){
            if(isset($acc[$cat])) {
                $acc[$cat]++;
            } else {
                $acc[$cat] = 1;
            }
            return $acc;
        }, []);

        $articlesPerCategory = array_reduce($articles, function ($acc,$article) {
            if(isset($acc[$article['category']])) {
                $acc[$article['category']] = [...$acc[$article['category']], $article];
            } else {
                $acc[$article['category']] = [$article];
            }

            return $acc;
        }, []);
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="public/css/index.css">
    <title>BLOG APP</title>
</head>
<body>
    <div class="container">
        <?php require_once "includes/header.php" ?>
        <div class="content">
            <div class="newsfeed-container">
                <ul class="category-container">
                    <li class=<?= $selectedCategory ? '' : 'cat-active' ?> ><a href="/">
                        Tous les articles <span class="small">( <?= count($articles) ?> )</span>
                    </a></li>
                    <?php foreach($categories as $category => $nbArticles) : ?>
                        <li class=<?= $selectedCategory === $category ? 'cat-active' : '' ?> ><a href="/?cat=<?= $category ?>">
                            <?= $category ?> <span class="small">( <?= $nbArticles ?> )</span>
                        </a></li>
                    <?php endforeach; ?>
                </ul>
                <div class="feed-container">
                    <?php if(!$selectedCategory) : ?>
                        <?php foreach($categories as $category => $num) : ?>
                            <h1 class="p-10"><?= $category ?>( <?= $num ?> )</h1>
                            <div class="articles-container">
                                    <?php foreach($articlesPerCategory[$category] as $article) : ?>
                                        <div class="article block">
                                            <div class="overflow">
                                                <div class="img-container" style="background-image: url(<?= $article['image']  ?>)"></div>
                                            </div>
                                            <h2><?= $article['title'] ?></h2>
                                        </div>
                                    <?php endforeach; ?>
                                
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <h1><?= $selectedCategory ?></h1>
                        <div class="articles-container">
                        <?php foreach($articlesPerCategory[$selectedCategory] as $article) : ?>
                            <div class="article block">
                                <div class="overflow">
                                    <div class="img-container" style="background-image: url(<?= $article['image']  ?>)"></div>
                                </div>
                                <h2><?= $article['title'] ?></h2>
                            </div>
                        <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php require_once 'includes/footer.php' ?>
    </div>
</body>
</html>