<?php
//var_dump($argv);
function calculv1($demande)
{
    if ($demande[1] === "+" || $demande[1] === "add") {
        $valeur1 = intval($demande[2]);
        $valeur2 = intval($demande[3]);
        $calcul = $valeur1 + $valeur2;
        echo $calcul;
    } else  if ($demande[1] === "-" || $demande[1] === "sub") {
        $valeur1 = intval($demande[2]);
        $valeur2 = intval($demande[3]);
        $calcul = $valeur1 - $valeur2;
        echo $calcul;
    } else  if ($demande[1] === "*" || $demande[1] === "mult") {
        $valeur1 = intval($demande[2]);
        $valeur2 = intval($demande[3]);
        $calcul = $valeur1 * $valeur2;
        echo $calcul;
    } else  if ($demande[1] === "/" || $demande[1] === "div") {
        $valeur1 = intval($demande[2]);
        $valeur2 = intval($demande[3]);
        if ($valeur1 == 0 || $valeur2 == 0) {
            die("invalid division");
        } else {
            $calcul = $valeur1 / $valeur2;
            echo $calcul;
        }
    } else  if ($demande[1] === "%" || $demande[1] === "mod") {
        $valeur1 = intval($demande[2]);
        $valeur2 = intval($demande[3]);
        $calcul = $valeur1 % $valeur2;
        echo $calcul;
    } else {
        die("unknown operation");
    }
}

//calculv1($argv);

function calculv2($demande)
{
    switch ($demande) {
        case $demande[1] === "+" || $demande[1] === "add":
            echo intval($demande[2]) + intval($demande[3]);
            break;
        case $demande[1] === "-" || $demande[1] === "sub":
            echo intval($demande[2]) - intval($demande[3]);
            break;
        case $demande[1] === "*" || $demande[1] === "mult":
            echo intval($demande[2]) * intval($demande[3]);
            break;
        case $demande[1] === "/" || $demande[1] === "div":
            $valeur1 = intval($demande[2]);
            $valeur2 = intval($demande[3]);
            if ($valeur1 == 0 || $valeur2 == 0) {
                die("invalid division");
            } else {
                echo  $valeur1 /  $valeur2;
            }
            break;
        case $demande[1] === "%" || $demande[1] === "mod":
            echo intval($demande[2]) % intval($demande[3]);
            break;
        default:
            die("unknown operation");
    }
}
calculv2($argv);
