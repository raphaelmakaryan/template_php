<?php
include('private/functions/tools.php');
session_start();
$errors = [];
$folder = dirname(__DIR__) . '/json/articles.json';
$validate = "";

if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = createTokenCSRF();
}

if (!isset($_SESSION['user'])) {
    header('Location: login');
    session_destroy();
    exit;
};

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
    } else if (filter_has_var(INPUT_POST, 'forContent') && numberStringLength($post['forContent']) < 100 && filter_input(INPUT_POST, 'forContent', FILTER_SANITIZE_SPECIAL_CHARS)) {
        $errors['forContent'] = "Le contenue doit contenir au moins 100 caractères.";
    }

    if (empty($post['categorySelect'])) {
        $errors['categorySelect'] = "Champs de catégory est vide !";
    } else if (filter_has_var(INPUT_POST, 'categorySelect') && !in_array($post['categorySelect'], ['Actualité', 'Tutoriel']) && filter_input(INPUT_POST, 'categorySelect', FILTER_SANITIZE_SPECIAL_CHARS)) {
        $errors['categorySelect'] = "Veuillez sélectionner une catégorie valide.";
    }


    if (!empty($_FILES['inputFileCrud']['name'])) {
        saveFileInput();
    }

    return empty($errors);
}
function saveFileInput()
{
    global $errors;
    $target_dir = "private/crud/uploads/";
    $target_file = $target_dir . basename($_FILES["inputFileCrud"]["name"]);

    if (isset($_FILES["inputFileCrud"]["tmp_name"]) && $_FILES["inputFileCrud"]["tmp_name"] !== "") {
        $check = getimagesize($_FILES["inputFileCrud"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["inputFileCrud"]["tmp_name"], $target_file)) {
                //
            } else {
                $errors['inputFileCrud'] = "Erreur lors du téléchargement du fichier.";
            }
        } else {
            $errors['inputFileCrud'] = "Le fichier n'est pas une image valide.";
        }
    } else {
        $errors['inputFileCrud'] = "Aucun fichier téléchargé.";
    }
}
function displayArticles()
{
    global $folder;

    $file = file_get_contents($folder);
    $articles = json_decode($file);
    if ($articles) {
        foreach ($articles as $article) {
            echo "<tr>";

            echo "<td>";
            echo htmlspecialchars($article->id);
            echo "</td>";

            echo "<td>";
            echo '<img class="img-fluid" src="' . htmlspecialchars($article->image) . '" >';
            echo "</td>";

            echo "<td>";
            echo htmlspecialchars($article->title);
            echo "</td>";

            echo "<td>";
            echo htmlspecialchars($article->content);
            echo "</td>";

            echo "<td>";
            echo htmlspecialchars($article->category);
            echo "</td>";

            echo "<td>";
            echo htmlspecialchars($article->created_at);
            echo "</td>";

            echo "<td>";
            echo htmlspecialchars($article->updated_at);
            echo "</td>";

            echo "<td>";
            echo "<a href='edit?id=" . htmlspecialchars($article->id) . "'>";
            echo '<button type="button" class="btn btn-secondary">Modifier</button>';
            echo '</a>';
            echo "</td>";

            echo "<td>";
            echo '<form method="post" action="crud">';
            echo '<input type="hidden" name="token" value="' . $_SESSION['token'] . '">';
            echo '<button type="submit" name="deleteButton" value="' . htmlspecialchars($article->id) . '" class="btn btn-danger">Supprimer</button>';
            echo "</td>";

            echo "</tr>";
        }
    }
}

function addArticles($data, $dataF)
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

    if ($dataF["inputFileCrud"]["name"]) {
        $forImage = "private/crud/uploads/" . $dataF["inputFileCrud"]["name"];
    } else {
        $forImage = "https://placehold.co/250x250";
    }

    // Prepare l'ajout
    $newArticle = [
        'id' => $nextId,
        'title' => $data['forTitle'],
        'slug' => $data['forTitle'],
        'content' => $data['forContent'],
        'category' => $data['categorySelect'],
        'image' => $forImage,
        'created_at' => date("Y-m-d h:i:sa"),
        'updated_at' => date("Y-m-d h:i:sa")
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
                    'content' => $article->content,
                    'slug' => $article->slug,
                    'image' => $article->image,
                    'category' => $article->category,
                    'created_at' => $article->created_at,
                    'updated_at' => $article->updated_at,
                ];

                $updateArticle[] = $newArticle;
            }
            file_put_contents($folder, json_encode($updateArticle, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }
}

if ($_POST) {
    if (isset($_POST["deleteButton"]) && verifToken($_SESSION)) {
        deleteArticles($_POST);
        $validate = "Article supprimé avec succès.";
    } else {
        if (validateForm($_POST) && verifToken($_SESSION)) {
            addArticles($_POST, $_FILES);
            $validate = "Formulaire d'ajout d'article a été un succès.";
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
                <div class="col-12 d-flex align-items-center flex-column mb-5">
                    <span class="text-success fs-4 text-center"><?php echo $validate; ?></span>
                </div>
                <div class="col-12 d-flex flex-column align-items-center">
                    <form method="post" action="crud" enctype="multipart/form-data" novalidate>
                        <div class="d-flex flex-column">
                            <p class="fs-2 text-center">Ajouter un article</p>
                        </div>
                        <div class="d-flex flex-column align-items-center mb-3">
                            <span class="text-danger"><?php echo $errors['forTitle'] ?? ''; ?></span>
                            <label for="forTitle">Titre</label>
                            <input type="text" name="forTitle" id="forTitle" placeholder="" class="form-control">
                        </div>
                        <div class="d-flex flex-column align-items-center mt-3 mb-3">
                            <span class="text-danger"><?php echo $errors['forContent'] ?? ''; ?></span>
                            <label for="forContent">Contenue</label>
                            <input type="text" name="forContent" id="forContent" placeholder="" class="form-control">
                        </div>
                        <div class="d-flex mb-3 flex-column align-items-center">
                            <span class="text-danger"><?php echo $errors['categorySelect'] ?? ''; ?></span>
                            <label for="categorySelect" class="fs-6 mb-1">Catégorie</label>
                            <select class="form-select" aria-label="categorySelect" id="categorySelect" name="categorySelect">
                                <option selected> Choissisez la catégory</option>
                                <option value="Actualité">Actualité</option>
                                <option value="Tutoriel">Tutoriel</option>
                            </select>
                        </div>
                        <div class=" d-flex flex-column align-items-center mt-4 mb-3 ">
                            <span class="text-danger"><?php echo $errors['inputFileCrud'] ?? ''; ?></span>
                            <div class="input-group d-flex flex-row">
                                <input type="file" class="form-control" name="inputFileCrud" id="inputFileCrud">
                                <label class="input-group-text" for="inputFileCrud">Image</label>
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-center mt-4">
                            <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 table-responsive">
                    <table class="table table-striped-columns">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Image</th>
                                <th scope="col">Titre</th>
                                <th scope="col">Contenue</th>
                                <th scope="col">Categorie</th>
                                <th scope="col">Crée le :</th>
                                <th scope="col">Modifié le :</th>
                                <th scope="col">Modifier</th>
                                <th scope="col">Supprimer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php displayArticles() ?>
                        </tbody>
                    </table>
                </div>
            </div>
    </section>
</main>

<?php include('./private/structures/footer.php'); ?>


<div class="d-flex flex-column align-items-center mt-3 mb-3">
    <textarea id="forContent"></textarea>
    <!--
                            <span class="text-danger"><?php echo $errors['forContent'] ?? ''; ?></span>
                            <label for="forContent">Contenue</label>
                            <input type="text" name="forContent" id="forContent" placeholder="" class="form-control" onchange="previsualisation()">
                            -->
</div>