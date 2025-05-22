<?php
session_start();
$users = ["user" => "alice", "pass" => "1234"];
$errors = [];

function numberStringLength($mot)
{
    return strlen($mot);
}

function validateForm($post)
{
    global $errors;

    if (empty($post['forUser'])) {
        $errors['forUser'] = "Champs d'utilisateur est vide !";
    } else if (filter_has_var(INPUT_POST, 'forUser') && numberStringLength($post['forUser']) < 3 && filter_input(INPUT_POST, 'forUser', FILTER_SANITIZE_SPECIAL_CHARS)) {
        $errors['forUser'] = "Le nom doit contenir au moins 3 caractères.";
    }

    if (empty($post['forPass'])) {
        $errors['forPass'] = "Champs du mot de passe est vide !";
    } else if (filter_has_var(INPUT_POST, 'forPass') && numberStringLength($post['forPass']) < 3 && filter_input(INPUT_POST, 'forPass', FILTER_SANITIZE_SPECIAL_CHARS)) {
        $errors['forPass'] = "Le mot de passe doit contenir au moins 3 caractères.";
    }


    return empty($errors);
}

function verificationLogin($data)
{
    if ($data) {
        global $users;
        global $errors;

        if ($users["user"] === $data["forUser"]) {
            if ($users["pass"] === $data["forPass"]) {
                return true;
            } else {
                $errors['forPass'] = "Le mot de passe que vous avez entré est incorrect.";
            }
        } else {
            $errors['forUser'] = "Le nom que vous avez entré n'existe pas.";
        }
        return empty($errors);
    }
}


if ($_POST) {
    if (validateForm($_POST) && verificationLogin($_POST)) {
        $_SESSION['user'] = $_POST['forUser'];
        header('Location: dashboard');
    } else {
        $errors['forUser'] = "Erreur de connexion !";
    }
}


if (isset($_SESSION['user'])) {
    header('Location: dashboard');
    exit();
}
?>

<?php include('./private/structures/header.php'); ?>

<head>
    <title>Page login</title>
    <meta name="description" content="C'est ma page login aller connecte toi" />
</head>
<main>
    <section class="mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 d-flex flex-column align-items-center">
                    <div class="d-flex flex-column p-5 rounded border border-dark">
                        <p class=" fs-1 text-center">Voici ma page <span class="fw-bold">login</span>.php</p>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12 d-flex flex-column align-items-center">
                    <div class="border border-dark rounded p-3">
                        <div class="d-flex flex-column ">
                            <p class=" fs-1 text-center">Connection</p>
                        </div>
                        <div class="">
                            <form method="post" action="login" id="">
                                <div class="d-flex flex-column align-items-center mb-3">
                                    <span class="text-danger"><?php echo $errors['forUser'] ?? ''; ?></span>
                                    <label for="forUser">User</label>
                                    <input type="text" name="forUser" id="forUser" minlength="3" placeholder="" class="form-control">
                                </div>
                                <div class="d-flex flex-column align-items-center mt-3 mb-2">
                                    <span class="text-danger"><?php echo $errors['forPass'] ?? ''; ?></span>
                                    <label for="forPass">Password</label>
                                    <input type="text" name="forPass" id="forPass" minlength="3" placeholder="" class="form-control">
                                </div>
                                <div class="d-flex flex-column align-items-center mt-4">
                                    <button type="submit" class="btn btn-primary">Connection</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include('./private/structures/footer.php'); ?>