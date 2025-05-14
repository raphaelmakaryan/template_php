<?php
$requested_page = isset($_GET['page']) ? $_GET['page'] : 'notfound';

switch ($requested_page) {
    case "page1":
        include(__DIR__ . "/page1.php");
        break;
    case "page2":
        include(__DIR__ . "/page2.php");
        break;
    case "page3":
        include(__DIR__ . "/page3.php");
        break;
    case "contact":
        include(__DIR__ . "/contact.php");
        break;
    default:
        include __DIR__ . "/notfound.php";
        break;
}
