<?php

include('private/functions/tools.php');
session_start();
$errors = [];
$folder = dirname(__DIR__) . '/json/articles.json';

function displayArticles()
{
    global $folder;

    $file = file_get_contents($folder);
    $articles = json_decode($file);
    if ($articles) {
        foreach ($articles as $article) {
            echo '<div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 col-xxl-3 flex-column align-items-center forArticle">';
            echo '<div class="card mb-2 mt-2" style="width: 18rem;">';
            echo '<img src="' . htmlspecialchars($article->image) . '" class="card-img-top" alt="...">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($article->title) . '</h5>';
            echo '<p class="card-text">' . htmlspecialchars($article->content) . ' </p>';
            echo '<p class="card-category">Catégorie : ' . htmlspecialchars($article->category) . ' </p>';
            echo '<a href="article?id=' . htmlspecialchars($article->id) . '" class="btn btn-primary">Voir le produit</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<div class="col-12"><p>Aucun article trouvé.</p></div>';
    }
}
?>


<?php include('./private/structures/header.php'); ?>

<head>
    <title>Page home pelo</title>
    <meta name="description" content="C'est ma page home " />
</head>
<main>
    <section class="mt-5">
        <div class="container-fluid">
            <div class="row mt-5">
                <div class="col-12 d-flex flex-column align-items-center">
                    <div class="d-flex flex-column p-5 rounded border border-dark">
                        <p class=" fs-1 text-center">Voici ma page <span class="fw-bold">home</span>.php</p>
                    </div>
                </div>
            </div>
            <div class="row mt-5 mb-5">
                <div class="col-lg-4"></div>
                <div class="col-12 col-lg-2 mt-5 d-flex flex-column align-items-center">
                    <span class="text-danger"><?php echo $errors['forTitle'] ?? ''; ?></span>
                    <input type="text" name="forTitle" id="forTitle" placeholder="" class="form-control">
                </div>
                <div class="col-12 col-lg-2 mt-5 d-flex flex-column align-items-center">
                    <button type="submit" class="btn btn-primary" onclick="searchBar()">Rechercher</button>
                </div>
                <div class="col-lg-4"></div>
            </div>
            <div class="row mt-5 mb-5 ">
                <div class="col-12 col-lg-3 ms-2 d-flex flex-column align-items-center">
                    <select class="form-select" id="selectFilter" aria-label="Default select example" onchange="filterCategory()">
                        <option selected value="all">Filtrage par catégorie</option>
                        <option value="Actualité">Actualité</option>
                        <option value="Tutoriel">Tutoriel</option>
                    </select>
                </div>
                <div class="col-lg-9"></div>
            </div>
            <div class="row mt-5">
                <?php displayArticles() ?>
            </div>
        </div>
    </section>
</main>

<script>
    function searchBar() {
        let input = document.getElementById("forTitle").value;
        let divCard = document.getElementsByClassName("card");
        let itemsCard = document.getElementsByClassName("forArticle");

        for (let i = 0; i < divCard.length; i++) {
            let titleCard = divCard[i].lastChild.firstChild.innerText;
            let contentCard = divCard[i].lastChild.children[1].innerText
            if (titleCard.includes(input) || contentCard.includes(input)) {
                itemsCard[i].style.display = "";
            } else {
                itemsCard[i].style.display = "none";
            }
        }
    }

    function filterCategory() {
        let input = document.getElementById("selectFilter").value;
        let divCard = document.getElementsByClassName("card");
        let itemsCard = document.getElementsByClassName("forArticle");

        for (let i = 0; i < divCard.length; i++) {
            let categoryCard = divCard[i].lastChild.children[2].innerText
            if (categoryCard.includes(input)) {
                itemsCard[i].style.display = "";
            } else if (input === "all") {
                itemsCard[i].style.display = "flex";
            } else {
                itemsCard[i].style.display = "none";
            }
        }
    }
</script>

<?php include('./private/structures/footer.php'); ?>