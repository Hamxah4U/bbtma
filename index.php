<?php
session_start();



$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
if (!isset($_SESSION['userID']) && $uri != '/' && $uri != '/login' && $uri != '/home' && $uri != '/contact' && $uri != '/about') {
   header('Location: /');
   exit();
}

$routes = [
    '/' => 'controllers/index.php',
    '/adduser' => 'controllers/users.php',
    '/manageuser' => 'controllers/manageuser.php',
    '/addstudent' => 'controllers/students/create.php',
    '/dashboard' => 'controllers/dashboard.php',
    '/managstudent' => 'controllers/managstudent.php',
    '/editstudent' => 'controllers/editstudent.php',
    '/examination' => 'controllers/testandexam.php',
    '/stdexam' => 'controllers/stdexam.php',
    '/subjectallocation' => 'controllers/subjectallocation.php',
    '/assigsub' => 'controllers/assigsub.php',
    '/calender' => 'controllers/calender.php',
    '/logout' => 'controllers/logout.php',
    '/parentlogout' => 'controllers/parentlogout.php',
    '/changepassword' => 'controllers/changepassword.php',
    '/report' => 'controllers/report.php',
    '/printresult' => 'controllers/printresult.php',
    '/generatepdf' => 'controllers/generatepdf.php',
    '/allresult' => 'controllers/allresult.php',
    '/promotion' => 'controllers/promotion.php',
    '/updateprofile' => 'controllers/updateprofile.php',
    '/schoolfees' => 'controllers/schoolfees.php',
    '/payschoolfees' => 'controllers/payschoolfees.php',
    '/receipt' => 'controllers/receipt.php',
    '/attendace' => 'controllers/attendace.php',
    '/newschoolfees' => 'controllers/newschoolfees.php',

    // parents
    '/home' => 'controllers/parents/parent.php',
    '/contact' => 'controllers/parents/contact.php',
    '/about' => 'controllers/parents/about.php',
    '/parentsdashboard' => 'controllers/parents/dashboard.php'


];

if(array_key_exists($uri, $routes)){
    require $routes[$uri];
}else{
    require 'controllers/404.php';
}