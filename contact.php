<?php

session_start();

if ($_POST) {
    $name = htmlspecialchars($_POST['forName']);
    $prenom = htmlspecialchars($_POST['forPrenom']);
    $select = htmlspecialchars($_POST['leSelect']);
    $email = htmlspecialchars($_POST['inputEmail']);
    $radio = htmlspecialchars($_POST['radioOptions']);
    $message = htmlspecialchars($_POST['inputMessage']);
    echo print_r($_POST);
}


?>

<?php include('./public/structure/header.php'); ?>

<main>
    <section class="mt-5 mb-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <p class="fs-1 text-center">Voici ma page contact.php</p>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <form method="post" action="contact.php" id="formContact" class="d-flex flex-column align-items-center">
                        <div class="d-flex mb-2 flex-column align-items-center">
                            <p class="fs-6 mb-1">Select</p>
                            <select class="form-select" aria-label="Default select example" id="leSelect" name="leSelect">
                                <option selected>Open this select menu</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                        <div class="d-flex flex-column align-items-center mt-2 mb-2">
                            <label for="forName">Nom</label>
                            <input type="text" name="forName" id="forName" min="3">
                        </div>
                        <div class="d-flex flex-column align-items-center mt-2 mb-2">
                            <label for="forPrenom">Prénom</label>
                            <input type="text" name="forPrenom" id="forPrenom" min="3">
                        </div>
                        <div class="d-flex flex-column align-items-center mt-4 mb-4">
                            <div class="form-floating">
                                <input type="email" class="form-control" name="inputEmail" id="inputEmail">
                                <label for="inputEmail">Email</label>
                            </div>
                        </div>
                        <div class="form-floating d-flex flex-column align-items-center mt-2 mb-2">
                            <p class="fs-6 mb-1">Radio</p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radioOptions" id="radioOption1">
                                <label class="form-check-label" for="radioOption1">
                                    Option 1
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radioOptions" id="radioOption2" checked>
                                <label class="form-check-label" for="radioOption2">
                                    Option 2
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="radioOptions" id="radioOption3" checked>
                                <label class="form-check-label" for="radioOption3">
                                    Option 3
                                </label>
                            </div>
                        </div>
                        <div class="form-floating d-flex flex-column align-items-center mt-3 mb-3">
                            <textarea class="form-control" placeholder="Leave a comment here" name="inputMessage" id="inputMessage" minlength="5"></textarea>
                            <label for="inputMessage">Message</label>
                        </div>
                        <div class=" d-flex flex-column align-items-center mt-2 mb-5">
                            <button type="submit" class="btn btn-primary">Envoyez</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include('./public/structure/footer.php'); ?>