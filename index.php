<?php

switch ($_SERVER['REQUEST_URI']) {
    case '/page1':
        include './public/pages/page1.php';
        break;
    case '/page2':
        include './public/pages/page2.php';
        break;
    case '/page3':
        include './public/pages/page3.php';
        break;
    case '/contact':
        include './public/pages/contact.php';
        break;
    default:
        include './public/pages/notfound.php';
        break;
}
