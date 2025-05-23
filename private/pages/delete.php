<?php
session_start();
include('private/functions/tools.php');
$folder = dirname(__DIR__) . '/json/articles.json';
$errors = [];
$articleNow;

if (!isset($_SESSION['user'])) {
    header('Location: login');
    session_destroy();
    exit;
};

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    getFileWithId($id);
}

if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = createTokenCSRF();
}



function getFileWithId($id)
{
    global $folder;
    global $articleNow;

    $file = file_get_contents($folder);
    $articles = json_decode($file);

    if ($articles) {
        foreach ($articles as $key => $article) {
            if ((int) $article->id === (int) $id) {
                $articleNow = $article;
            }
        }
    }
}


if ($_POST) {
    if (verifToken($_SESSION)) {
        if (isset($_POST["accept"])) {
            deleteArticles($articleNow->id);
            header('Location: crud');
            exit;
        } else if (isset($_POST["refuse"])) {
            header('Location: crud');
            exit;
        }
    }
}


?>

<?php include('./private/structures/header.php'); ?>

<head>
    <title>Page delete</title>
    <meta name="description" content="C'est ma page delete bravo t'es co" />
</head>
<main>
    <div class="container-fluid">
        <div class="row mt-5 mb-5">
            <div class="col-12 d-flex flex-column align-items-center">
                <form method="post" action="delete?id=<?php echo $id ?>">
                    <div class="d-flex flex-column">
                        <p class="fs-2 text-center">Etes vous sur de supprimer cet article ?</p>
                    </div>
                    <div class="d-flex flex-column align-items-center mb-3">
                        <label for="forId">ID</label>
                        <input type="text" name="forId" id="forId" placeholder="" class="form-control" value="<?php echo $id ?>" disabled>
                    </div>
                    <div class="d-flex flex-column align-items-center mb-3">
                        <span class="text-danger"><?php echo $errors['forTitleMdf'] ?? ''; ?></span>
                        <label for="forTitleMdf">Titre</label>
                        <input type="text" name="forTitleMdf" id="forTitleMdf" placeholder="" class="form-control" value="<?php echo $articleNow->title;  ?>" readonly disabled>
                    </div>
                    <div class="d-flex flex-column align-items-center mt-3 mb-2">
                        <span class="text-danger"><?php echo $errors['forContentMdf'] ?? ''; ?></span>
                        <label for="forContentMdf">Contenue</label>
                        <input type="text" name="forContentMdf" id="forContentMdf" placeholder="" class="form-control" value="<?php echo $articleNow->content;  ?>" readonly disabled>
                    </div>
                    <div class="d-flex mb-3 flex-column align-items-center">
                        <label for="categorySelectMdf">Catégorie</label>
                        <input type="text" name="categorySelectMdf" id="categorySelectMdf" placeholder="" class="form-control" value="<?php echo $articleNow->category;  ?>" readonly disabled>
                    </div>
                    <div class="d-flex flex-row align-items-center mt-4 justify-content-evenly">
                        <button type="submit" class="btn btn-danger" name="accept">Oui</button>
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
                        <button type="submit" class="btn btn-success" name="refuse">Non</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php include('./private/structures/footer.php'); ?>