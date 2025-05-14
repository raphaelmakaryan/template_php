<?php

switch ($_SERVER['REQUEST_URI']) {
    case '/page1':
        include 'page1.php';
        break;
    case '/page2':
        include 'page2.php';
        break;
    case '/page3':
        include 'page3.php';
        break;
    default:
        include 'notfound.php';
        break;
}
