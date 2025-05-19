<?php

//echo "Hello World";

/* $argc = Argument passés au script mais renvoie la valeur en int */
//var_dump($argc);

/* $argv = Argument passés au script mais renvoie la valeur en array */
//var_dump($argv);

function sendHello($nom)
{
    if (!$nom[1]) {
        echo "Hello World";
    } else {
        echo "hello " . $nom[1];
    }
};

sendHello($argv);
