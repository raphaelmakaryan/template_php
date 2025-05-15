<?php
$valuePC = rand(1, 20);
$compt = 0;
$debug = false;

while ($valuePC) {
    fscanf(STDIN, "%d\n", $valueUser);

    if ($valuePC === $valueUser) {
        die("Tu as trouvé le chiffre ! C'étais " . $valuePC . " et tu as fais " . $compt . " essaies !");
    } else if ($valueUser > $valuePC) {
        $compt++;
        echo "Nombres d'essaies : " . $compt . " | Plus petit";
    } else if ($valueUser < $valuePC) {
        $compt++;
        echo "Nombres d'essaies : " . $compt . " | Plus grand";
    }

    if ($debug) {
        echo "-------";
        echo $valueUser;
        echo "----X---";
        echo $valuePC;
        echo "-------";
    }
}
