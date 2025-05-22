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

function validateForm($post)
{
    global $errors;

    if (empty($post['forTitleMdf'])) {
        $errors['forTitleMdf'] = "Champs titre est vide !";
    } else if (filter_has_var(INPUT_POST, 'forTitleMdf') && numberStringLength($post['forTitleMdf']) < 3 && filter_input(INPUT_POST, 'forTitleMdf', FILTER_SANITIZE_SPECIAL_CHARS)) {
        $errors['forTitleMdf'] = "Le titre doit contenir au moins 3 caractères.";
    }

    if (empty($post['forContentMdf'])) {
        $errors['forContentMdf'] = "Champs du contenue est vide !";
    } else if (filter_has_var(INPUT_POST, 'forContentMdf') && numberStringLength($post['forContentMdf']) < 100 && filter_input(INPUT_POST, 'forContentMdf', FILTER_SANITIZE_SPECIAL_CHARS)) {
        $errors['forContentMdf'] = "Le contenue doit contenir au moins 100 caractères.";
    }

    if (empty($post['categorySelectMdf'])) {
        $errors['categorySelectMdf'] = "Champs de catégory est vide !";
    } else if (filter_has_var(INPUT_POST, 'categorySelectMdf') && !in_array($post['categorySelectMdf'], ['Actualité','Tutoriel']) && filter_input(INPUT_POST, 'categorySelectMdf', FILTER_SANITIZE_SPECIAL_CHARS)) {
        $errors['categorySelectMdf'] = "Veuillez sélectionner une catégorie valide.";
    }

    return empty($errors);
}

function updateArticleWithId($data)
{
    global $folder;
    global $id;

    $file = file_get_contents($folder);
    $articles = json_decode($file);

    if ($articles) {
        foreach ($articles as $key => $article) {
            if ((int) $article->id === (int) $id) {
                // Mise à jour de l'article
                $articles[$key]->title = $data['forTitleMdf'];
                $articles[$key]->slug = $data['forTitleMdf'];
                $articles[$key]->content = $data['forContentMdf'];
                $articles[$key]->category = $data['categorySelectMdf'];
                $articles[$key]->updated_at = date("Y-m-d h:i:sa");

                // Sauvegarde dans le fichier
                file_put_contents($folder, json_encode($articles, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

                return true;
            }
        }
    }

    return false; // Aucun article modifié
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
    if (validateForm($_POST) && verifToken($_SESSION)) {
        if (updateArticleWithId($_POST)) {
            header('Location: crud');
        }
    }
}


?>

<?php include('./private/structures/header.php'); ?>

<head>
    <title>Page crud</title>
    <meta name="description" content="C'est ma page crud bravo t'es co" />
</head>
<main>
    <div class="container-fluid">
        <div class="row mt-5 mb-5">
            <div class="col-12 d-flex flex-column align-items-center">
                <form method="post" action="edit?id=<?php echo $id ?>">
                    <div class="d-flex flex-column">
                        <p class="fs-2 text-center">Modifier un article</p>
                    </div>
                    <div class="d-flex flex-column align-items-center mb-3">
                        <label for="forId">ID</label>
                        <input type="text" name="forId" id="forId" placeholder="" class="form-control" value="<?php echo $id ?>" disabled>
                    </div>
                    <div class="d-flex flex-column align-items-center mb-3">
                        <span class="text-danger"><?php echo $errors['forTitleMdf'] ?? ''; ?></span>
                        <label for="forTitleMdf">Titre</label>
                        <input type="text" name="forTitleMdf" id="forTitleMdf" placeholder="" class="form-control" value="<?php echo $articleNow->title;  ?>">
                    </div>
                    <div class="d-flex flex-column align-items-center mt-3 mb-2">
                        <span class="text-danger"><?php echo $errors['forContentMdf'] ?? ''; ?></span>
                        <label for="forContentMdf">Contenue</label>
                        <input type="text" name="forContentMdf" id="forContentMdf" placeholder="" class="form-control" value="<?php echo $articleNow->content;  ?>">
                    </div>
                    <div class="d-flex mb-3 flex-column align-items-center">
                        <span class="text-danger"><?php echo $errors['categorySelectMdf'] ?? ''; ?></span>
                        <label for="categorySelectMdf" class="fs-6 mb-1">Catégorie</label>
                        <select class="form-select" aria-label="categorySelectMdf" id="categorySelectMdf" name="categorySelectMdf">
                            <option selected> Choissisez la catégory</option>
                           <option value="Actualité">Actualité</option>
                                <option value="Tutoriel">Tutoriel</option>
                        </select>
                    </div>
                    <div class="d-flex flex-column align-items-center mt-4">
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
                        <button type="submit" class="btn btn-primary">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php include('./private/structures/footer.php'); ?>