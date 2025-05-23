<?php
$users = [["user" => "alice", "pass" => "1234", "id" => 0, "role" => "admin"], ["user" => "brigitte", "pass" => "5678", "id" => 1, "role" => "author"]];

function numberStringLength($mot)
{
    return strlen($mot);
}

function verificationLogin($data)
{
    if ($data) {
        global $users;
        global $errors;
        foreach ($users as $value) {
            if ($value["user"] === $data["forUser"]) {
                if ($value["pass"] === $data["forPass"]) {
                    return [$value["role"], $value["id"]];
                } else {
                    $errors['forPass'] = "Le mot de passe que vous avez entré est incorrect.";
                }
            } else {
                $errors['forUser'] = "Le nom que vous avez entré n'existe pas.";
            }
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
