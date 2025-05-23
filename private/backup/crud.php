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
        $errors['forTitle'] = "Champs de titre est vide !";
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

    if (isset($_FILES["inputFileCrud"]["tmp_name"]) && $_FILES["inputFileCrud"]["tmp_name"] !== "") {
        // taille du fichier
        $fileSize = filesize($_FILES["inputFileCrud"]["tmp_name"]);
        // extension
        $fileExt = strtolower(pathinfo($_FILES["inputFileCrud"]["name"], PATHINFO_EXTENSION));
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif', "webp"];

        if ($fileSize > 2 * 1024 * 1024) {
            $errors['inputFileCrud'] = "La taille du fichier ne doit pas dépasser 2 Mo.";
            return;
        }

        if (!in_array($fileExt, $allowedExt)) {
            $errors['inputFileCrud'] = "Extension de fichier non autorisée. Seuls les fichiers jpg, png, gif sont acceptés.";
            return;
        }

        $check = getimagesize($_FILES["inputFileCrud"]["tmp_name"]);
        if ($check !== false) {
            // Renommer le fichier avec un hash ou timestamp
            $newFileName = uniqid('img_', true) . '.' . $fileExt;
            $target_file = $target_dir . $newFileName;

            if (move_uploaded_file($_FILES["inputFileCrud"]["tmp_name"], $target_file)) {
                // Mettre à jour le nom du fichier dans $_FILES pour l'utiliser ailleurs
                $_FILES["inputFileCrud"]["name"] = $newFileName;
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

    if (!file_exists($folder)) {
        echo '<div class="col-12"><p>Aucun article trouvé.</p></div>';
        return;
    }

    $file = file_get_contents($folder);
    $articles = json_decode($file, true);

    if (!is_array($articles) || empty($articles)) {
        echo '<div class="col-12"><p>Aucun article trouvé.</p></div>';
        return;
    }

    // Filtrer les articles de l'utilisateur courant
    $userId = $_SESSION["user"]["id"];
    $userArticles = array_filter($articles, function ($article) use ($userId) {
        return isset($article['creator']) && $article['creator'] == $userId;
    });

    if (empty($userArticles)) {
        echo '<div class="col-12"><p>Aucun article trouvé.</p></div>';
        return;
    }

    // Grouper les articles par catégorie
    $grouped = [];
    foreach ($userArticles as $article) {
        $grouped[$article['category']][] = $article;
    }

    $hasGroup = false;
    foreach ($grouped as $category => $catArticles) {
        if (count($catArticles) >= 2) {
            $hasGroup = true;
            echo '<div class="col-12 mb-4">';
            echo '<h4 class="fw-bold">' . htmlspecialchars($category) . '</h4>';
            foreach ($catArticles as $article) {
                echo '<div class="mb-3 border rounded">';
                echo '<div class="container-fluid">';
                echo '<div class="row">';
                echo '<div class="col-lg-1 col-12 d-flex flex-column align-items-center p-2">';
                echo '<img class="img-fluid" src="' . htmlspecialchars($article['image']) . '" >';
                echo '</div>';
                echo '<div class="col-lg-9 col-12 d-flex flex-column align-items-start">';
                echo '<p class="fs-5">' . htmlspecialchars($article['title']) . '</p>';
                echo '<p class="fs-6">' . htmlspecialchars($article['content']) . '</p>';
                echo '<p class="fs-6">' . htmlspecialchars($article['category']) . '</p>';
                echo '<p class="fs-6">Crée le : ' . htmlspecialchars($article['created_at']) . ' | Modifié le : ' . htmlspecialchars($article['updated_at']) . '</p>';
                echo '</div>';
                echo '<div class="col-lg-1 col-12 d-flex flex-row align-items-center">';
                echo "<a href='edit?id=" . htmlspecialchars($article['id']) . "'>";
                echo '<button type="button" class="btn btn-secondary">Modifier</button>';
                echo '</a>';
                echo '</div>';
                echo '<div class="col-lg-1 col-12 d-flex flex-row align-items-center">';
                echo '<form method="post" action="crud">';
                echo '<input type="hidden" name="token" value="' . htmlspecialchars($_SESSION['token']) . '">';
                echo '<button type="submit" name="deleteButton" value="' . htmlspecialchars($article['id']) . '" class="btn btn-danger">Supprimer</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        }
    }

    // Afficher les articles restants (moins de 2 par catégorie)
    $rest = [];
    foreach ($grouped as $category => $catArticles) {
        if (count($catArticles) < 2) {
            foreach ($catArticles as $article) {
                $rest[] = $article;
            }
        }
    }
    if (count($rest) > 0) {
        echo '<div class="col-12 mb-4">';
        echo '<h4 class="fw-bold">Autres articles</h4>';
        foreach ($rest as $article) {
            echo '<div class="mb-3 border rounded">';
            echo '<div class="container-fluid">';
            echo '<div class="row">';
            echo '<div class="col-lg-1 col-12 d-flex flex-column align-items-center p-2">';
            echo '<img class="img-fluid" src="' . htmlspecialchars($article['image']) . '" >';
            echo '</div>';
            echo '<div class="col-lg-9 col-12 d-flex flex-column align-items-start">';
            echo '<p class="fs-5">' . htmlspecialchars($article['title']) . '</p>';
            echo '<p class="fs-6">' . htmlspecialchars($article['content']) . '</p>';
            echo '<p class="fs-6">' . htmlspecialchars($article['category']) . '</p>';
            echo '<p class="fs-6">Crée le : ' . htmlspecialchars($article['created_at']) . ' | Modifié le : ' . htmlspecialchars($article['updated_at']) . '</p>';
            echo '</div>';
            echo '<div class="col-lg-1 col-12 d-flex flex-row align-items-center">';
            echo "<a href='edit?id=" . htmlspecialchars($article['id']) . "'>";
            echo '<button type="button" class="btn btn-secondary">Modifier</button>';
            echo '</a>';
            echo '</div>';
            echo '<div class="col-lg-1 col-12 d-flex flex-row align-items-center">';
            echo '<form method="post" action="crud">';
            echo '<input type="hidden" name="token" value="' . htmlspecialchars($_SESSION['token']) . '">';
            echo '<button type="submit" name="deleteButton" value="' . htmlspecialchars($article['id']) . '" class="btn btn-danger">Supprimer</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }

    if (!$hasGroup && count($rest) === 0) {
        echo '<div class="col-12"><p>Aucun article trouvé.</p></div>';
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
        'updated_at' => date("Y-m-d h:i:sa"),
        'creator' => $_SESSION["user"]["id"]
    ];

    //L'ajoute au tableau avec le reste
    $articles[] = $newArticle;

    file_put_contents($folder, json_encode($articles, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simplemde/dist/simplemde.min.css">

</head>
<main>
    <section class="mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 d-flex flex-column align-items-center">
                    <div class="d-flex flex-column p-5 rounded border border-dark">
                        <p class=" fs-1 text-center">Bienvenue <?php echo $_SESSION['user']["name"] ?> sur la page <span class="fw-bold">crud</span>.php !</p>
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
                            <p class="fs-2 text-center text-decoration-underline">Ajouter un article</p>
                        </div>
                        <div class="d-flex flex-column align-items-center mb-3">
                            <span class="text-danger"><?php echo $errors['forTitle'] ?? ''; ?></span>
                            <label for="forTitle">Titre</label>
                            <input type="text" name="forTitle" id="forTitle" placeholder="" class="form-control" onchange="previsualisation()">
                        </div>
                        <div class="mt-4 mb-3">
                            <span class="text-danger"><?php echo $errors['forContent'] ?? ''; ?></span>
                            <label for="forContent text-center">Contenue</label>
                            <textarea id="forContent" name="forContent" onchange="previsualisation()"></textarea>
                        </div>
                        <div class="d-flex mb-3 flex-column align-items-center">
                            <span class="text-danger"><?php echo $errors['categorySelect'] ?? ''; ?></span>
                            <label for="categorySelect" class="fs-6 mb-1">Catégorie</label>
                            <select class="form-select" aria-label="categorySelect" id="categorySelect" name="categorySelect" onchange="previsualisation()">
                                <option selected value="none"> Choissisez la catégory</option>
                                <option value="Actualité">Actualité</option>
                                <option value="Tutoriel">Tutoriel</option>
                            </select>
                        </div>
                        <div class=" d-flex flex-column align-items-center mt-4 ">
                            <span class="text-danger"><?php echo $errors['inputFileCrud'] ?? ''; ?></span>
                            <div class="input-group d-flex flex-row">
                                <input type="file" class="form-control" name="inputFileCrud" id="inputFileCrud" onchange="previsualisation()">
                                <label class="input-group-text" for="inputFileCrud">Image</label>
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-center mt-5">
                            <p class="fs-2">Previsualisation</p>
                        </div>
                        <div class="mb-3 mt-2 border rounded">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-1 col-12 d-flex flex-column align-items-center p-2"><img class="img-fluid" id="imgPrev" src="https://placehold.co/250x250"></div>
                                    <div class="col-lg-9 col-12 d-flex flex-column align-items-start">
                                        <p class="fs-5" id="titlePrev">Title previsualisation</p>
                                        <p class="fs-6" id="contentPrev">Content previsualisation</p>
                                        <p class="fs-6" id="catPrev">category previsualisation</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column align-items-center mt-4">
                            <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12 mt-5">
                    <p class="fs-1 text-center text-decoration-underline">Listes d'articles</p>
                </div>
                <?php displayArticles() ?>
            </div>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/simplemde/dist/simplemde.min.js"></script>
<script>
    let simplemde = new SimpleMDE({
        element: document.getElementById("forContent")
    });

    function previsualisation() {
        let title = document.getElementById("titlePrev")
        let titleInput = document.getElementById("forTitle").value

        let content = document.getElementById("contentPrev")
        let contentInput = simplemde.value()

        let category = document.getElementById("catPrev")
        let categoryInput = document.getElementById("categorySelect").value

        let image = document.getElementById("imgPrev");
        let imageInput = document.getElementById("inputFileCrud").files;


        if (titleInput != "") {
            title.innerText = titleInput
        }

        if (contentInput != "") {
            content.innerText = contentInput
        }

        if (categoryInput != "none") {
            category.innerText = categoryInput
        }

        if (imageInput && imageInput[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                image.src = e.target.result;
            }
            reader.readAsDataURL(imageInput[0]);
        } else {
            image.src = "https://placehold.co/250x250";
        }
    }
</script>

<?php include('./private/structures/footer.php'); ?>