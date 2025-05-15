<?php
ini_set('display_errors', 1);
session_start();
$errors = [];
$validate = "";

function validateForm($post)
{
    global $errors;
    if (empty($post['inputMessage']) || strlen($post['inputMessage']) < 5) {
        $errors['inputMessage'] = "Le message doit contenir au moins 5 caractères.";
    }

    if (empty($post['inputEmail']) || !filter_var($post['inputEmail'], FILTER_VALIDATE_EMAIL)) {
        $errors['inputEmail'] = "L'email n'est pas valide.";
    }

    if (empty($post['radioOptions']) || !in_array($post['radioOptions'], ['TEL', 'EMAIL', 'RDV'])) {
        $errors['radioOptions'] = "Veuillez sélectionner une raison de contact valide.";
    }

    if (empty($post['forName']) || strlen($post['forName']) < 3) {
        $errors['forName'] = "Le nom doit contenir au moins 3 caractères.";
    }

    if (empty($post['forPrenom']) || strlen($post['forPrenom']) < 3) {
        $errors['forPrenom'] = "Le prénom doit contenir au moins 3 caractères.";
    }

    return empty($errors);
}

function saveToFile($data)
{
    $file = __DIR__ . '/fichier.txt';
    $content = "Nom: {$data['forName']}\n";
    $content .= "Prénom: {$data['forPrenom']}\n";
    $content .= "Email: {$data['inputEmail']}\n";
    $content .= "Raison de contact: {$data['radioOptions']}\n";
    $content .= "Message: {$data['inputMessage']}\n";
    file_put_contents($file, $content);
}

if ($_POST) {
    if (validateForm($_POST)) {
        saveToFile($_POST);
        unset($_SESSION);
        $validate = "Formulaire soumis avec succès.";
        //echo "Formulaire soumis avec succès.";
        //$_SESSION = $_POST;
    } else {
        $_SESSION = $_POST;
    }
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
                <div class="col-12 d-flex align-items-center flex-column mb-5">
                    <span class="text-success fs-4 text-center"><?php echo $validate; ?></span>
                </div>
                <div class="col-12">
                    <form method="post" action="contact.php" id="formContact" class="d-flex flex-column align-items-center" novalidate>
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
                            <input type="text" name="forName" id="forName" minlength="3" placeholder="" value="<?php echo htmlspecialchars($_SESSION['forName'] ?? ''); ?>">
                            <span class="text-danger"><?php echo $errors['forName'] ?? ''; ?></span>
                        </div>
                        <div class="d-flex flex-column align-items-center mt-2 mb-2">
                            <label for="forPrenom">Prénom</label>
                            <input type="text" name="forPrenom" id="forPrenom" minlength="3" placeholder="" value="<?php echo htmlspecialchars($_SESSION['forPrenom'] ?? ''); ?>">
                            <span class="text-danger"><?php echo $errors['forPrenom'] ?? ''; ?></span>
                        </div>
                        <div class="d-flex flex-column align-items-center mt-4 mb-4">
                            <div class="form-floating">
                                <input type="email" class="form-control" name="inputEmail" id="inputEmail" minlength="5" value="<?php echo htmlspecialchars($_SESSION['inputEmail'] ?? ''); ?>">
                                <label for="inputEmail">Email</label>
                                <span class="text-danger"><?php echo $errors['inputEmail'] ?? ''; ?></span>
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-center mt-2 mb-2">
                            <p class="fs-6 mb-1">Choix de contact</p>
                            <label for="radioOption1">TEL</label>
                            <input type="radio" id="radioOption1" name="radioOptions" value="TEL">
                            <label for="radioOption2">EMAIL</label>
                            <input type="radio" id="radioOption2" name="radioOptions" value="EMAIL">
                            <label for="radioOption3">RDV</label>
                            <input type="radio" id="radioOption3" name="radioOptions" value="RDV">

                            <span class="text-danger"><?php echo $errors['radioOptions'] ?? ''; ?></span>
                        </div>
                        <div class="form-floating d-flex flex-column align-items-center mt-3 mb-3">
                            <textarea class="form-control" placeholder="Leave a comment here" name="inputMessage" id="inputMessage" minlength="5"><?php echo htmlspecialchars($_SESSION['inputMessage'] ?? ''); ?></textarea>
                            <label for="inputMessage">Message</label>
                            <span class="text-danger"><?php echo $errors['inputMessage'] ?? ''; ?></span>
                        </div>
                        <div class="d-flex flex-column align-items-center mt-2 mb-5">
                            <button type="submit" class="btn btn-primary">Envoyez</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include('./public/structure/footer.php'); ?>