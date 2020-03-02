<?php

if (isset($_POST["article_delete"])) {
    ArticleService::delete($_POST["article_id"]);
}

$articles = ArticleService::getAll();

?>

<div id="content" class="container">
    <?php
    if (!SecurityService::isLogged()) {
        ?>
        <div class="row">
            <div class="twelve columns text-center">
                You must be logged in to read articles!
            </div>
        </div>
        <?php
    } else {
        foreach ($articles as $article) {
            ?>
            <form action="" method="post">
                <div class="row">
                    <div class="article">
                        <div class="row">
                            <div class="eleven columns title">
                                <h2>
                                    <?= $article['title'] ?>
                                </h2>
                            </div>
                            <div class="one column">
                                <input type="hidden" value="<?= $article['id'] ?>" name="article_id">
                                <input type="submit" value="Delete" name="article_delete">
                            </div>
                        </div>
                        <div class="row">
                            <div class="twelve columns content">
                                <?= $article['content'] ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="twelve columns author">
                                by <?= $article['author'] ?>, <?= $article['created'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php
        }
    }
    ?>
</div>
