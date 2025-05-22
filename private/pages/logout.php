<?php
session_start();
if (isset($_SESSION['user'])) {
    session_destroy();
    header('Location: login');
    exit();
} else {
    header('Location: login');
    exit();
}
