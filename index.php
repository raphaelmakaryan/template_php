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
    case '/login':
        include './public/pages/login.php';
        break;
    case '/dashboard':
        include './public/pages/dashboard.php';
        break;
    case '/crud':
        include './public/pages/crud.php';
        break;
    case '/logout':
        include './public/pages/logout.php';
        break;
    case '/edit?id=':
        include './public/pages/edit.php';
        break;
    default:
        include './public/pages/notfound.php';
        break;
}
