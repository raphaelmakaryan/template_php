<?php

session_start();
$errors = [];
$folder = dirname(__DIR__) . '/json/articles.json';

if (!isset($_SESSION['user'])) {
    header('Location: login');
    session_destroy();
    exit;
};

function numberStringLength($mot)
{
    return strlen($mot);
}

function validateForm($post)
{
    global $errors;

    if (empty($post['forTitle'])) {
        $errors['forTitle'] = "Champs de titr est vide !";
    } else if (filter_has_var(INPUT_POST, 'forTitle') && numberStringLength($post['forTitle']) < 3 && filter_input(INPUT_POST, 'forTitle', FILTER_SANITIZE_SPECIAL_CHARS)) {
        $errors['forTitle'] = "Le titr doit contenir au moins 3 caractères.";
    }

    if (empty($post['forContent'])) {
        $errors['forContent'] = "Champs de contenue est vide !";
    } else if (filter_has_var(INPUT_POST, 'forContent') && numberStringLength($post['forContent']) < 3 && filter_input(INPUT_POST, 'forContent', FILTER_SANITIZE_SPECIAL_CHARS)) {
        $errors['forContent'] = "Le contenue doit contenir au moins 3 caractères.";
    }

    return empty($errors);
}

function displayArticles()
{
    global $folder;

    $file = file_get_contents($folder);
    $articles = json_decode($file);
    if ($articles) {
        foreach ($articles as $article) {
            echo '<div class="col-12 mb-3 border rounded">';
            echo '<div class="container-fluid">';
            echo '<div class="row">';
            echo '<div class="col-10 d-flex flex-column align-items-start p-2">';
            echo '<p class="fs-4">' . htmlspecialchars($article->title) . '</p>';
            echo '<p class="fs-6">' . htmlspecialchars($article->content) . '</p>';
            echo '</div>';
            echo '<div class="col-1 d-flex flex-row align-items-center">';
            echo "<a href='edit?id=" . htmlspecialchars($article->id) . "'>";
            echo '<button type="button" class="btn btn-secondary">Modifier</button>';
            echo '</a>';
            echo '</div>';
            echo '<div class="col-1 d-flex flex-row align-items-center">';
            echo '<form method="post" action="crud">';
            echo '<button type="submit" name="deleteButton" value="' . htmlspecialchars($article->id) . '" class="btn btn-danger">Supprimer</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<div class="col-12"><p>Aucun article trouvé.</p></div>';
    }
}

function addArticles($data)
{
    global $folder;

    // Recupere et le decode
    $current = file_get_contents($folder);
    $articles = json_decode($current, associative: true);

    // Si c'est pas un tableau on le defini tel
    if (!is_array($articles)) {
        $articles = [];
    }

    // Dis que l'id commence par 1, si il est pas vide on recupere le nombre d'article + 1
    $nextId = 1;
    if (!empty($articles)) {
        $ids = array_column($articles, column_key: 'id');
        $nextId = max($ids) + 1;
    }

    // Prepare l'ajout
    $newArticle = [
        'id' => $nextId,
        'title' => $data['forTitle'],
        'content' => $data['forContent']
    ];

    //L'ajoute au tableau avec le reste
    $articles[] = $newArticle;

    file_put_contents($folder, json_encode($articles, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function deleteArticles($data)
{
    global $folder;
    $data = $data["deleteButton"];
    $current = file_get_contents($folder);
    $articles = json_decode($current);
    $updateArticle = [];
    if ($articles) {
        foreach ($articles as $key => $article) {
            if ((int) $article->id !== (int) $data) {
                $newArticle = [
                    'id' => $article->id,
                    'title' => $article->title,
                    'content' => $article->content
                ];

                $updateArticle[] = $newArticle;
            }
            file_put_contents($folder, json_encode($updateArticle, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }
}

if ($_POST) {
    if (isset($_POST["deleteButton"])) {
        deleteArticles($_POST);
    } else {
        if (validateForm($_POST)) {
            addArticles($_POST);
        }
    }
}

?>

<?php include('./public/structures/header.php'); ?>

<head>
    <title>Page crud</title>
    <meta name="description" content="C'est ma page crud bravo t'es co" />
</head>
<main>
    <section class="mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 d-flex flex-column align-items-center">
                    <div class="d-flex flex-column p-5 rounded border border-dark">
                        <p class=" fs-1 text-center">Bienvenue <?php echo $_SESSION['user'] ?> sur la page <span class="fw-bold">crud</span>.php !</p>
                    </div>
                </div>
            </div>
            <div class="row mt-5 mb-5">
                <div class="col-12 d-flex flex-column align-items-center">
                    <form method="post" action="crud">
                        <div class="d-flex flex-column">
                            <p class="fs-2 text-center">Ajouter un article</p>
                        </div>
                        <div class="d-flex flex-column align-items-center mb-3">
                            <span class="text-danger"><?php echo $errors['forTitle'] ?? ''; ?></span>
                            <label for="forTitle">Titre</label>
                            <input type="text" name="forTitle" id="forTitle" placeholder="" class="form-control">
                        </div>
                        <div class="d-flex flex-column align-items-center mt-3 mb-2">
                            <span class="text-danger"><?php echo $errors['forContent'] ?? ''; ?></span>
                            <label for="forContent">Contenue</label>
                            <input type="text" name="forContent" id="forContent" placeholder="" class="form-control">
                        </div>
                        <div class="d-flex flex-column align-items-center mt-4">
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row mt-3">
                <?php displayArticles() ?>
            </div>
    </section>


</main>

<?php include('./public/structures/footer.php'); ?>