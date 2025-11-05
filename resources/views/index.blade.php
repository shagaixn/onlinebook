<?php
require_once "../config/database.php";
$page = $_GET['page'] ?? 'home';

include "../includes/header.php";

switch ($page) {
    case 'books':        include "../pages/books.php"; break;
    case 'podcasts':     include "../pages/podcasts.php"; break;
    case 'subscription': include "../pages/subscription.php"; break;
    case 'profile':      include "../pages/profile.php"; break;
    case 'login':        include "../pages/login.php"; break;
    default:             include "../pages/home.php"; break;
}

include "../includes/footer.php";
?>