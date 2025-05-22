<?php
function guess($max)
{
    global $valuePC;
    if ($max[1]) {
        $valuePC = rand(1, max: $max[1]);
    } else {
        $valuePC = rand(1, 15);
    }
    $compt = 0;
    $debug = false;

    while ($valuePC) {
        fscanf(STDIN, "%d\n", $valueUser);
        if ($valuePC === $valueUser) {
            die("Tu as trouvé le chiffre ! C'étais " . $valuePC . " et tu as fait " . $compt . " essais !");
        } else if (abs($valueUser - $valuePC) > 1) {
            $compt++;
            echo "Nombres d'essaies : " . $compt . " | Tu t'éloigne";
            echo "\n";
        } else if (abs($valueUser - $valuePC) == 1 || abs($valueUser - $valuePC) <= 2) {
            $compt++;
            echo "Nombres d'essaies : " . $compt . " | Tu te rapproche";
            echo "\n";
        }

        if ($debug) {
            echo "\n";
            echo "-------";
            echo $valueUser;
            echo "----X---";
            echo $valuePC;
            echo "-------";
        }
    }
}

guess($argv);
