<?php
session_start();
include('private/functions/tools.php');
$folder = dirname(__DIR__) . '/json/articles.json';
$errors = [];
$articleNow;

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
    if (isset($_POST["deleteButton"]) && verifToken($_SESSION)) {
        deleteArticles($_POST);
        header('Location: home');
    }
}

?>

<?php include('./private/structures/header.php'); ?>

<head>
    <title>Page article </title>
    <meta name="description" content="C'est la page article" />
</head>

<main>
    <div class="container-fluid">
        <div class="row mt-5 mb-5">
            <div class="col-12 d-flex flex-column align-items-center">
                <div class="d-flex flex-column p-5 rounded border border-dark p-5">
                    <p class=" fs-1 text-center">Voici ma page <span class="fw-bold">article</span>.php</p>
                </div>
            </div>
        </div>
        <div class="row mt-5 mb-5">
            <div class="col-lg-2"></div>
            <div class="col-lg-4 mt-5 border">
                <img src="<?php echo $articleNow->image;  ?>" alt="" class="img-fluid">
            </div>
            <div class="col-lg-4 mt-5 border">
                <div class="container-fluid">
                    <div class="row mt-3 ">
                        <?php if (isset($_SESSION['user'])) { ?>
                            <div class="col-12 d-flex flex-column align-items-center mt-2 mb-2">
                                <a href='edit?id=<?php echo $articleNow->id;  ?>'>
                                    <button type="button" class="btn btn-secondary">Modifier</button>
                                </a>
                            </div>
                            <div class="col-12 d-flex flex-column align-items-center mt-2 mb-2">
                                <form method="post" action="article?id=<?php echo $articleNow->id;  ?>">
                                    <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?>">
                                    <button type="submit" name="deleteButton" value="<?php echo $articleNow->id;  ?>" class="btn btn-danger">Supprimer</button>
                                </form>
                            </div>
                        <?php } ?>
                        <div class="col-12 d-flex flex-column align-items-start mt-3">
                            <p>ID : <?php echo $articleNow->id;  ?></p>
                            <p>Titre : <?php echo $articleNow->title;  ?></p>
                            <p>Contenue : <?php echo $articleNow->content;  ?></p>
                            <p>Slug : <?php echo $articleNow->slug;  ?></p>
                            <p>Catégorie : <?php echo $articleNow->category;  ?></p>
                            <p>Crée le : <?php echo $articleNow->created_at;  ?></p>
                            <p>Mis a jour le : <?php echo $articleNow->updated_at;  ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2"></div>
        </div>
    </div>

</main>


<?php include('./private/structures/footer.php'); ?>