<?php

$request = $_SERVER['REQUEST_URI'];

switch (true) {
    case $request === '/page1':
        include './public/pages/page1.php';
        break;
    case $request === '/page2':
        include './public/pages/page2.php';
        break;
    case $request === '/page3':
        include './public/pages/page3.php';
        break;
    case $request === '/contact':
        include './public/pages/contact.php';
        break;
    case $request === '/login':
        include './public/pages/login.php';
        break;
    case $request === '/dashboard':
        include './public/pages/dashboard.php';
        break;
    case $request === '/crud':
        include './public/pages/crud.php';
        break;
    case $request === '/logout':
        include './public/pages/logout.php';
        break;
    default:
        if (strpos($request, '/edit') === 0 && isset($_GET['id'])) {
            include './public/pages/edit.php';
        } else {
            include './public/pages/notfound.php';
        }
        break;
}
