<?php
ini_set('display_errors', 1);
session_start();
$errors = [];
$validate = "";

function numberStringLength($mot)
{
    return strlen($mot);
}

function validateForm($post)
{
    global $errors;

    if (empty($post['leSelect'])) {
        $errors['leSelect'] = "Champs de civilité est vide !";
    } else if (filter_has_var(INPUT_POST, 'leSelect') && !in_array($post['leSelect'], ['Homme', 'Femme']) && filter_input(INPUT_POST, 'leSelect', FILTER_SANITIZE_SPECIAL_CHARS)) {
        $errors['leSelect'] = "Veuillez sélectionner un sexe valide.";
    }

    if (empty($post['forName'])) {
        $errors['forName'] = "Champs de nom est vide !";
    } else if (filter_has_var(INPUT_POST, 'forName') && numberStringLength($post['forName']) < 3 && filter_input(INPUT_POST, 'forName', FILTER_SANITIZE_SPECIAL_CHARS)) {
        $errors['forName'] = "Le nom doit contenir au moins 3 caractères.";
    }

    if (empty($post['forPrenom'])) {
        $errors['forPrenom'] = "Champs de prénom est vide !";
    } else if (filter_has_var(INPUT_POST, 'forPrenom') && numberStringLength($post['forPrenom']) < 3 && filter_input(INPUT_POST, 'forPrenom', FILTER_SANITIZE_SPECIAL_CHARS)) {
        $errors['forPrenom'] = "Le prénom doit contenir au moins 3 caractères.";
    }

    if (empty($post['inputEmail'])) {
        $errors['inputEmail'] = "Champs d'email est vide !";
    } else if (filter_has_var(INPUT_POST, 'inputEmail') && empty($post['inputEmail']) && filter_var($post['inputEmail'], FILTER_VALIDATE_EMAIL) && filter_input(INPUT_POST, 'inputEmail', FILTER_SANITIZE_SPECIAL_CHARS)) {
        $errors['inputEmail'] = "L'email n'est pas valide.";
    }

    if (empty($post['radioOptions'])) {
        $errors['radioOptions'] = "Champs de contact est vide !";
    } else if (filter_has_var(INPUT_POST, 'radioOptions') && !in_array($post['radioOptions'], ['TEL', 'EMAIL', 'RDV']) && filter_input(INPUT_POST, 'radioOptions', FILTER_SANITIZE_SPECIAL_CHARS)) {
        $errors['radioOptions'] = "Veuillez sélectionner une raison de contact valide.";
    }

    if (empty($post['inputMessage'])) {
        $errors['inputMessage'] = "Champs de message est vide !";
    } else if (filter_has_var(INPUT_POST, 'inputMessage') && numberStringLength($post['inputMessage']) < 5 && filter_input(INPUT_POST, 'inputMessage', FILTER_SANITIZE_SPECIAL_CHARS)) {
        $errors['inputMessage'] = "Le message doit contenir au moins 5 caractères.";
    }

    return empty($errors);
}

function saveToFile($data)
{
    $file = __DIR__ . '/fichier.txt';
    $content = "Civilité: {$data['leSelect']}\n";
    $content .= "Nom: {$data['forName']}\n";
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
                            <span class="text-danger"><?php echo $errors['leSelect'] ?? ''; ?></span>
                            <label for="leSelect" class="fs-6 mb-1">Select</label>
                            <select class="form-select" aria-label="leSelect" id="leSelect" name="leSelect">
                                <!-- <option selected> <?php echo htmlspecialchars($_SESSION['leSelect'] ?? ' Choissisez votre sexe'); ?></option> -->
                                <option selected> Choissisez votre sexe</option>
                                <option value="Homme">Homme</option>
                                <option value="Femme">Femme</option>
                            </select>
                        </div>
                        <div class="d-flex flex-column align-items-center mt-2 mb-2">
                            <span class="text-danger"><?php echo $errors['forName'] ?? ''; ?></span>
                            <label for="forName">Nom</label>
                            <input type="text" name="forName" id="forName" minlength="3" placeholder="" class="form-control" value="<?php echo htmlspecialchars($_SESSION['forName'] ?? ''); ?>">
                        </div>
                        <div class="d-flex flex-column align-items-center mt-2 mb-2">
                            <span class="text-danger"><?php echo $errors['forPrenom'] ?? ''; ?></span>
                            <label for="forPrenom">Prénom</label>
                            <input type="text" name="forPrenom" id="forPrenom" minlength="3" class="form-control" placeholder="" value="<?php echo htmlspecialchars($_SESSION['forPrenom'] ?? ''); ?>">
                        </div>
                        <div class="d-flex flex-column align-items-center mt-4 mb-4">
                            <span class="text-danger"><?php echo $errors['inputEmail'] ?? ''; ?></span>
                            <input type="email" class="form-control" placeholder="Email" name="inputEmail" id="inputEmail" value="<?php echo htmlspecialchars($_SESSION['inputEmail'] ?? ''); ?>">
                            <label for="inputEmail"></label>
                        </div>
                        <div class="d-flex flex-column align-items-center mt-2 mb-2">
                            <p class="fs-6 mb-1">Choix de contact</p>
                            <span class="text-danger"><?php echo $errors['radioOptions'] ?? ''; ?></span>
                            <label for="radioOption1">TEL</label>
                            <input type="radio" id="radioOption1" name="radioOptions" value="TEL">
                            <label for="radioOption2">EMAIL</label>
                            <input type="radio" id="radioOption2" name="radioOptions" value="EMAIL">
                            <label for="radioOption3">RDV</label>
                            <input type="radio" id="radioOption3" name="radioOptions" value="RDV">
                        </div>
                        <div class="d-flex flex-column align-items-center mt-3 mb-3">
                            <span class="text-danger mb-2"><?php echo $errors['inputMessage'] ?? ''; ?></span>
                            <label for="inputMessage"></label>
                            <textarea class="form-control" placeholder="Message" name="inputMessage" id="inputMessage"><?php echo htmlspecialchars($_SESSION['inputMessage'] ?? ''); ?></textarea>
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