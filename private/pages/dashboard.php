<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login');
    session_destroy();
    exit;
}
?>

<?php include('./private/structures/header.php'); ?>

<head>
    <title>Page dashboard</title>
    <meta name="description" content="C'est ma page dashboard bravo t'es co" />
</head>
<main>
    <section class="mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 d-flex flex-column align-items-center">
                    <div class="d-flex flex-column p-5 rounded border border-dark">
                        <p class=" fs-1 text-center">Bienvenue <?php echo $_SESSION['user']["name"] ?> sur la page <span class="fw-bold">dashboard</span>.php !</p>
                    </div>
                </div>
            </div>
            <div class="row mt-5 ">
                <div class="col-12 d-flex flex-column align-items-center mb-2">
                    <p>Vous avez le rôle : <?php echo $_SESSION['user']["role"] ?></p>
                </div>
                <div class="col-12 d-flex flex-column align-items-center mb-2">
                    <p>Vous avez l'id n° : <?php echo $_SESSION['user']["id"] ?></p>
                </div>
                <div class="col-12 d-flex flex-column align-items-center mb-2">
                    <a href="crud">
                        <button type="button" class="btn btn-primary">Crud</button>
                    </a>
                </div>
                <div class="col-12 d-flex flex-column align-items-center mt-2">
                    <a href="logout">
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include('./private/structures/footer.php'); ?>