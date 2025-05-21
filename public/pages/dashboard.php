<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login');
    session_destroy();
    exit;
}
?>

<?php include('./public/structures/header.php'); ?>

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
                        <p class=" fs-1 text-center">Bienvenue <?php echo $_SESSION['user'] ?> sur la page <span class="fw-bold">dashboard</span>.php !</p>
                    </div>
                </div>
            </div>
            <div class="row mt-5 ">
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

<?php include('./public/structures/footer.php'); ?>