<?php
session_start();
$folder = dirname(__DIR__) . '/json/articles.json';
$errors = [];

if (!isset($_SESSION['user'])) {
    header('Location: login');
    session_destroy();
    exit;
};

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
}

function numberStringLength($mot)
{
    return strlen($mot);
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
    } else if (filter_has_var(INPUT_POST, 'forContentMdf') && numberStringLength($post['forContentMdf']) < 3 && filter_input(INPUT_POST, 'forContentMdf', FILTER_SANITIZE_SPECIAL_CHARS)) {
        $errors['forContentMdf'] = "Le contenue doit contenir au moins 3 caractères.";
    }

    return empty($errors);
}

function getFileWithId($data)
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
                $articles[$key]->content = $data['forContentMdf'];

                // Sauvegarde dans le fichier
                file_put_contents($folder, json_encode($articles, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

                return true;
            }
        }
    }

    return false; // Aucun article modifié
}

if ($_POST) {
    if (validateForm($_POST)) {
        if (getFileWithId($_POST)) {
            header('Location: crud');
        }
    }
}


?>

<?php include('./public/structure/header.php'); ?>

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
                        <label for="forTitleMdf">Titre</label>
                        <input type="text" name="forTitleMdf" id="forTitleMdf" placeholder="" class="form-control">
                    </div>
                    <div class="d-flex flex-column align-items-center mt-3 mb-2">
                        <label for="forContentMdf">Contenue</label>
                        <input type="text" name="forContentMdf" id="forContentMdf" placeholder="" class="form-control">
                    </div>
                    <div class="d-flex flex-column align-items-center mt-4">
                        <button type="submit" class="btn btn-primary">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php include('./public/structure/footer.php'); ?>