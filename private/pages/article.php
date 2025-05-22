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

?>

<?php include('./private/structures/header.php'); ?>

<head>
    <title>Page article </title>
    <meta name="description" content="C'est la page article" />
</head>

<main>
    <div class="container-fluid">
        <div class="row mt-5 mb-5">
            <div class="col-12 d-flex flex-column align-items-center rounded border border-dark p-5">
                <p class=" fs-1 text-center">Voici ma page <span class="fw-bold">article</span>.php</p>
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
                            <div class="col-12 d-flex flex-column align-items-center">
                                <button type="submit" name="deleteButton" class="btn btn-danger mb-5" value="<?php echo $articleNow->id;  ?>">Supprimer</button>
                                <button type="button" class="btn btn-secondary mb-5">Modifier</button>
                            </div>
                        <?php } ?>
                        <div class="col-12 d-flex flex-column align-items-start mt-3">
                            <p><?php echo $articleNow->title;  ?></p>
                            <p><?php echo $articleNow->content;  ?></p>
                            <p><?php echo $articleNow->slug;  ?></p>
                            <p><?php echo $articleNow->id;  ?></p>
                            <p><?php echo $articleNow->category;  ?></p>
                            <p><?php echo $articleNow->created_at;  ?></p>
                            <p><?php echo $articleNow->updated_at;  ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2"></div>
        </div>
    </div>

</main>


<?php include('./private/structures/footer.php'); ?>