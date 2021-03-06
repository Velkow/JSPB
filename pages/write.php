<?php

SecurityService::requireAuth(true);
SecurityService::redirectIfNotAllowed(RanksEnum::WRITER);

$title = null;
$content = null;

$errors = array();
$success = array();

$user = SecurityService::getLogged();

if (isset($_POST["article_submit"])) {
    $title = $_POST["article_title"];
    $content = $_POST["article_content"];
    if (!isset($title) || strlen($title) <= 0) {
        array_push($errors, array("Title cannot be empty!"));
    } if (!isset($content) || strlen($content) <= 0) {
        array_push($errors, array("Content cannot be empty!"));
    } if ($_FILES["input_cover"]["error"] == UPLOAD_ERR_NO_FILE) {
        array_push($errors, array("Cover file cannot be empty!"));
    } else {
        $allowed = array("png", "jpg", "jpeg");
        $destination = __ROOT__ . "/assets/images/covers/";
        $extension = strtolower(pathinfo($_FILES['input_cover']['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowed)) {
            array_push($errors, array("Cover file extension not in " . implode( ", ", $allowed ) . "!"));
        } else {
            $filename = uniqid() . "." . $extension;
        }
    }

    if (count($errors) <= 0) {
        if (move_uploaded_file($_FILES['input_cover']['tmp_name'],  $destination . $filename)) {
            ArticleService::add($title, $content, $user->getId(), $filename);
            array_push($success, array("Article successfully added!"));
        } else {
            array_push($errors, array("Cover file cannot be uploaded!"));
        }
    }
}

?>

<div id="content" class="container">
    <form enctype="multipart/form-data" action="" method="post">
        <div class="row">
            <div class="twelve columns">
                <input class="u-full-width" type="text" name="article_title" placeholder="Lorem ipsum dolor sit amet" value="<?= $title ?>">
            </div>
        </div>
        <div class="row">
            <div class="twelve columns">
                <input class="u-full-width" name="input_cover" type="file">
            </div>
        </div>
        <div class="row">
            <div class="twelve columns">
                <textarea id="write" name="article_content"
                          class="u-full-width"
                          cols="30"
                          rows="10"
                          placeholder="Maybe you can write something about coronavirus?"><?= $content ?></textarea>
            </div>
        </div>
        <div class="row">
            <div class="offset-by-three six columns">
                <input class="u-full-width space-top" type="submit" value="Submit" name="article_submit">
            </div>
        </div>
        <div class="row">
            <div class="twelve columns">
                <ul>
                    <div>
                        <?php
                        foreach ($errors as $error) {
                        ?>
                            <li class="error"><?= array_values($error)[0] ?></li>
                        <?php
                        }
                        ?>
                    </div>
                    <div>
                        <?php
                        foreach ($success as $message) {
                            ?>
                            <li class="success"><?= array_values($message)[0] ?></li>
                            <?php
                        }
                        ?>
                    </div>
                </ul>
            </div>
        </div>
    </form>
    <script>
        CKEDITOR.replace( 'write' );
    </script>
</div>
