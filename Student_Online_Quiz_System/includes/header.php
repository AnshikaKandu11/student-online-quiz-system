<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        QuizCore | Student Online Quiz System
    </title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">


    <!-- Bootstrap Icons -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet">


    <!-- QuizCore Stylesheet -->

    <link
        rel="stylesheet"
        href="../assets/css/style.css">


    <!-- Load saved theme before page renders -->

    <script>

        (function () {

            const savedTheme =
                localStorage.getItem("quizcore-theme");

            if (savedTheme === "light") {

                document.documentElement
                    .setAttribute(
                        "data-theme",
                        "light"
                    );

            } else {

                document.documentElement
                    .setAttribute(
                        "data-theme",
                        "dark"
                    );

            }

        })();

    </script>


    <style>

        /* =========================================================
           QUIZCORE SHARED HEADER
        ========================================================= */

        :root {

            --header-bg: #070b14;

            --header-surface: #101827;

            --header-border:
                rgba(255,255,255,0.08);

            --header-text: #f8fafc;

            --header-muted: #94a3b8;

            --header-primary: #6366f1;

            --header-primary-2: #8b5cf6;

        }


        html[data-theme="light"] {

            --header-bg: #f5f7fb;

            --header-surface: #ffffff;

            --header-border:
                rgba(15,23,42,0.09);

            --header-text: #0f172a;

            --header-muted: #64748b;

            --header-primary: #4f46e5;

            --header-primary-2: #7c3aed;

        }


        body {

            background:
                var(--header-bg);

            color:
                var(--header-text);

            transition:
                background .3s ease,
                color .3s ease;

        }


        /* =========================================================
           NAVBAR
        ========================================================= */

        .tech-navbar {

            position: sticky;

            top: 0;

            z-index: 1050;

            padding:
                13px 0;

            background:
                rgba(7,11,20,0.78) !important;

            border-bottom:
                1px solid
                var(--header-border);

            backdrop-filter:
                blur(18px);

            -webkit-backdrop-filter:
                blur(18px);

            transition:
                background .3s ease,
                border-color .3s ease;

        }


        html[data-theme="light"]
        .tech-navbar {

            background:
                rgba(255,255,255,0.84) !important;

        }


        /* =========================================================
           BRAND
        ========================================================= */

        .tech-navbar .navbar-brand {

            display:
                inline-flex;

            align-items:
                center;

            color:
                var(--header-text) !important;

            font-size:
                1.2rem;

            font-weight:
                800;

            letter-spacing:
                -.4px;

            text-decoration:
                none;

        }


        .tech-navbar .brand-icon {

            width:
                38px;

            height:
                38px;

            display:
                inline-flex;

            align-items:
                center;

            justify-content:
                center;

            margin-right:
                9px;

            border-radius:
                11px;

            background:
                linear-gradient(
                    135deg,
                    var(--header-primary),
                    var(--header-primary-2)
                );

            color:
                #ffffff;

            font-size:
                .95rem;

            font-weight:
                800;

            box-shadow:
                0 8px 25px
                rgba(99,102,241,0.30);

        }


        /* =========================================================
           NAV LINKS
        ========================================================= */

        .tech-navbar .nav-link {

            display:
                inline-flex;

            align-items:
                center;

            gap:
                7px;

            color:
                var(--header-muted) !important;

            font-size:
                .88rem;

            font-weight:
                600;

            padding:
                9px 12px !important;

            border-radius:
                10px;

            transition:
                color .2s ease,
                background .2s ease,
                transform .2s ease;

        }


        .tech-navbar .nav-link:hover {

            color:
                var(--header-text) !important;

            background:
                rgba(99,102,241,0.08);

            transform:
                translateY(-1px);

        }


        /* =========================================================
           ACTIVE-LIKE ICONS
        ========================================================= */

        .nav-item-dashboard .nav-link::before {

            content:
                "\f425";

            font-family:
                "bootstrap-icons";

        }


        .nav-item-quizzes .nav-link::before {

            content:
                "\f4ca";

            font-family:
                "bootstrap-icons";

        }


        .nav-item-questions .nav-link::before {

            content:
                "\f4d6";

            font-family:
                "bootstrap-icons";

        }


        .nav-item-students .nav-link::before {

            content:
                "\f4da";

            font-family:
                "bootstrap-icons";

        }


        .nav-item-login .nav-link::before {

            content:
                "\f1b6";

            font-family:
                "bootstrap-icons";

        }


        .nav-item-register .nav-link::before {

            content:
                "\f4fe";

            font-family:
                "bootstrap-icons";

        }


        .nav-item-admin .nav-link::before {

            content:
                "\f3e7";

            font-family:
                "bootstrap-icons";

        }


        .nav-item-logout .nav-link::before {

            content:
                "\f1c3";

            font-family:
                "bootstrap-icons";

        }


        /* =========================================================
           THEME BUTTON
        ========================================================= */

        .theme-toggle {

            width:
                42px;

            height:
                42px;

            display:
                inline-flex;

            align-items:
                center;

            justify-content:
                center;

            margin-left:
                7px;

            border:
                1px solid
                var(--header-border);

            border-radius:
                11px;

            background:
                var(--header-surface);

            color:
                var(--header-text);

            font-size:
                .95rem;

            transition:
                transform .2s ease,
                border-color .2s ease,
                background .2s ease;

        }


        .theme-toggle:hover {

            transform:
                translateY(-2px);

            border-color:
                rgba(99,102,241,0.40);

            background:
                rgba(99,102,241,0.08);

        }


        /* =========================================================
           MOBILE MENU BUTTON
        ========================================================= */

        .tech-navbar .navbar-toggler {

            border:
                1px solid
                var(--header-border);

            border-radius:
                10px;

            padding:
                8px 10px;

        }


        .tech-navbar .navbar-toggler:focus {

            box-shadow:
                0 0 0 3px
                rgba(99,102,241,0.12);

        }


        /* =========================================================
           USER / LOGOUT STYLE
        ========================================================= */

        .nav-item-logout .nav-link {

            color:
                #f87171 !important;

        }


        .nav-item-logout .nav-link:hover {

            color:
                #fca5a5 !important;

            background:
                rgba(239,68,68,0.08);

        }


        /* =========================================================
           MAIN CONTENT
        ========================================================= */

        .page-wrapper {

            min-height:
                calc(100vh - 72px);

        }


        /* =========================================================
           MOBILE
        ========================================================= */

        @media (max-width: 991px) {

            .tech-navbar {

                padding:
                    11px 0;

            }


            .tech-navbar .navbar-nav {

                padding-top:
                    12px;

            }


            .tech-navbar .nav-link {

                padding:
                    11px 12px !important;

            }


            .theme-toggle {

                margin:
                    10px 0 0 0;

            }

        }


        @media (max-width: 576px) {

            .tech-navbar .brand-icon {

                width:
                    35px;

                height:
                    35px;

            }

            .tech-navbar .navbar-brand {

                font-size:
                    1.05rem;

            }

        }

    </style>

</head>


<body>


<!-- =========================================================
     NAVIGATION
========================================================= -->

<nav class="navbar navbar-expand-lg navbar-dark tech-navbar">

    <div class="container">


        <!-- BRAND -->

        <a
            class="navbar-brand"
            href="../index.php">

            <span class="brand-icon">

                Q

            </span>

            QuizCore

        </a>


        <!-- MOBILE TOGGLE -->

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarContent"
            aria-controls="navbarContent"
            aria-expanded="false"
            aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>

        </button>


        <!-- NAVIGATION -->

        <div
            class="collapse navbar-collapse"
            id="navbarContent">


            <ul
                class="navbar-nav ms-auto align-items-lg-center">


                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'student'): ?>


                    <!-- STUDENT DASHBOARD -->

                    <li class="nav-item nav-item-dashboard">

                        <a
                            class="nav-link"
                            href="../student/dashboard.php">

                            Dashboard

                        </a>

                    </li>


                    <!-- LOGOUT -->

                    <li class="nav-item nav-item-logout">

                        <a
                            class="nav-link"
                            href="../logout.php">

                            Logout

                        </a>

                    </li>


                <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>


                    <!-- ADMIN DASHBOARD -->

                    <li class="nav-item nav-item-dashboard">

                        <a
                            class="nav-link"
                            href="../admin/dashboard.php">

                            Dashboard

                        </a>

                    </li>


                    <!-- QUIZZES -->

                    <li class="nav-item nav-item-quizzes">

                        <a
                            class="nav-link"
                            href="../admin/quizzes.php">

                            Quizzes

                        </a>

                    </li>


                    <!-- QUESTIONS -->

                    <li class="nav-item nav-item-questions">

                        <a
                            class="nav-link"
                            href="../admin/questions.php">

                            Questions

                        </a>

                    </li>


                    <!-- STUDENTS -->

                    <li class="nav-item nav-item-students">

                        <a
                            class="nav-link"
                            href="../admin/students.php">

                            Students

                        </a>

                    </li>


                    <!-- LOGOUT -->

                    <li class="nav-item nav-item-logout">

                        <a
                            class="nav-link"
                            href="../logout.php">

                            Logout

                        </a>

                    </li>


                <?php else: ?>


                    <!-- STUDENT LOGIN -->

                    <li class="nav-item nav-item-login">

                        <a
                            class="nav-link"
                            href="../student/login.php">

                            Student Login

                        </a>

                    </li>


                    <!-- REGISTER -->

                    <li class="nav-item nav-item-register">

                        <a
                            class="nav-link"
                            href="../student/register.php">

                            Register

                        </a>

                    </li>


                    <!-- ADMIN -->

                    <li class="nav-item nav-item-admin">

                        <a
                            class="nav-link"
                            href="../admin/login.php">

                            Admin

                        </a>

                    </li>


                <?php endif; ?>


                <!-- THEME SWITCH -->

                <li class="nav-item">

                    <button
                        type="button"
                        class="theme-toggle"
                        id="themeToggle"
                        aria-label="Toggle dark and light mode"
                        title="Toggle theme">

                        <i
                            class="bi bi-sun-fill"
                            id="themeIcon">
                        </i>

                    </button>

                </li>


            </ul>

        </div>

    </div>

</nav>


<!-- =========================================================
     MAIN CONTENT
========================================================= -->

<main class="page-wrapper">