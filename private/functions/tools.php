<?php
$users = ["user" => "alice", "pass" => "1234"];

function numberStringLength($mot)
{
    return strlen($mot);
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

function createTokenCSRF()
{
    return md5(uniqid(mt_rand(), true));
}

function verifToken($session)
{
    if ($session) {
        $token = htmlspecialchars(filter_input(INPUT_POST, 'token'));
        if ($token == $session['token']) {
            $_SESSION['token'] = createTokenCSRF();
            return true;
        }
    }
    return false;
}