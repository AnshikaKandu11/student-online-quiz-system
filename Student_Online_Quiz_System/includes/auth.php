<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/*
|--------------------------------------------------------------------------
| Require Login
|--------------------------------------------------------------------------
| Allows only logged-in users to access the protected page.
*/

function requireLogin()
{
    if (!isset($_SESSION['user_id'])) {

        header("Location: ../student/login.php");
        exit;

    }
}


/*
|--------------------------------------------------------------------------
| Require Admin
|--------------------------------------------------------------------------
| Allows only authenticated administrators.
*/

function requireAdmin()
{
    if (
        !isset($_SESSION['user_id']) ||
        !isset($_SESSION['role']) ||
        $_SESSION['role'] !== 'admin'
    ) {

        header("Location: login.php");
        exit;

    }
}


/*
|--------------------------------------------------------------------------
| Require Student
|--------------------------------------------------------------------------
| Allows only authenticated students.
*/

function requireStudent()
{
    if (
        !isset($_SESSION['user_id']) ||
        !isset($_SESSION['role']) ||
        $_SESSION['role'] !== 'student'
    ) {

        header("Location: login.php");
        exit;

    }
}

?>