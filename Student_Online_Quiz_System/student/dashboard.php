<?php

session_start();

require_once "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "student") {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION["user_id"];
$studentName = $_SESSION["name"];


/* =========================
   TOTAL ATTEMPTS
========================= */

$stmt = $conn->prepare(
    "SELECT COUNT(*) AS total
     FROM results
     WHERE user_id = ?"
);

$stmt->bind_param("i", $userId);
$stmt->execute();

$attempts = $stmt->get_result()->fetch_assoc()["total"];


/* =========================
   AVERAGE PERCENTAGE
========================= */

$stmt = $conn->prepare(
    "SELECT COALESCE(AVG(percentage), 0) AS average
     FROM results
     WHERE user_id = ?"
);

$stmt->bind_param("i", $userId);
$stmt->execute();

$average = $stmt->get_result()->fetch_assoc()["average"];


/* =========================
   AVAILABLE QUIZZES
========================= */

$quizzes = $conn->query(
    "SELECT *
     FROM quizzes
     WHERE status = 'active'
     ORDER BY id DESC"
);


/* =========================
   PREVIOUS RESULTS
========================= */

$stmt = $conn->prepare(
    "SELECT
        r.id,
        r.score,
        r.total_questions,
        r.percentage,
        r.attempted_at,
        q.title
     FROM results r
     JOIN quizzes q ON r.quiz_id = q.id
     WHERE r.user_id = ?
     ORDER BY r.attempted_at DESC"
);

$stmt->bind_param("i", $userId);
$stmt->execute();

$previousResults = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Dashboard | QuizCore</title>


    <!-- =====================================================
         BOOTSTRAP
    ====================================================== -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">


    <!-- =====================================================
         BOOTSTRAP ICONS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <style>

        /* =====================================================
           THEME VARIABLES
        ====================================================== */

        :root {

            /* DARK MODE */

            --bg-main: #070b14;

            --bg-secondary: #0b1120;

            --navbar-bg: rgba(7, 11, 20, 0.82);

            --panel: #101827;

            --panel-secondary: #0d1523;

            --panel-hover: #141f32;

            --border: rgba(255,255,255,0.08);

            --border-light: rgba(255,255,255,0.13);

            --text: #f5f7fb;

            --text-secondary: #dce3ee;

            --muted: #8b98ab;

            --muted-dark: #64748b;

            --primary: #6d5dfc;

            --primary-light: #8d80ff;

            --cyan: #28c7fa;

            --green: #31d6a1;

            --yellow: #f5c451;

            --red: #ff6b81;

            --shadow:
                0 20px 60px rgba(0,0,0,0.35);

            --card-shadow:
                0 12px 40px rgba(0,0,0,0.18);

            --radius: 18px;

        }


        /* =====================================================
           LIGHT MODE
        ====================================================== */

        html[data-theme="light"] {

            --bg-main: #f5f7fb;

            --bg-secondary: #ffffff;

            --navbar-bg: rgba(255,255,255,0.88);

            --panel: #ffffff;

            --panel-secondary: #f8faff;

            --panel-hover: #f1f4fa;

            --border: rgba(15,23,42,0.09);

            --border-light: rgba(15,23,42,0.14);

            --text: #111827;

            --text-secondary: #273449;

            --muted: #64748b;

            --muted-dark: #94a3b8;

            --primary: #5b4df5;

            --primary-light: #6d5dfc;

            --cyan: #0891b2;

            --green: #059669;

            --yellow: #d97706;

            --red: #e11d48;

            --shadow:
                0 20px 60px rgba(15,23,42,0.08);

            --card-shadow:
                0 12px 35px rgba(15,23,42,0.07);

        }


        /* =====================================================
           GLOBAL
        ====================================================== */

        * {
            box-sizing: border-box;
        }


        html {
            scroll-behavior: smooth;
        }


        body {

            margin: 0;

            background:

                radial-gradient(
                    circle at 15% 0%,
                    rgba(109,93,252,0.10),
                    transparent 28%
                ),

                radial-gradient(
                    circle at 90% 15%,
                    rgba(40,199,250,0.07),
                    transparent 25%
                ),

                var(--bg-main);

            color: var(--text);

            font-family:
                Inter,
                "Segoe UI",
                Roboto,
                Arial,
                sans-serif;

            min-height: 100vh;

            transition:
                background-color .25s ease,
                color .25s ease;
        }


        a {
            text-decoration: none;
        }


        /* =====================================================
           NAVBAR
        ====================================================== */

        .premium-navbar {

            height: 76px;

            background:
                var(--navbar-bg);

            border-bottom:
                1px solid var(--border);

            backdrop-filter:
                blur(18px);

            -webkit-backdrop-filter:
                blur(18px);

            position: sticky;

            top: 0;

            z-index: 1000;

            transition:
                background .25s ease,
                border .25s ease;
        }


        .navbar-inner {

            height: 76px;

            display: flex;

            align-items: center;

            justify-content: space-between;
        }


        /* =====================================================
           BRAND
        ====================================================== */

        .brand {

            display: flex;

            align-items: center;

            gap: 11px;

            color: var(--text);

            font-size: 21px;

            font-weight: 800;

            letter-spacing: -0.5px;
        }


        .brand:hover {
            color: var(--text);
        }


        .brand-mark {

            width: 40px;

            height: 40px;

            border-radius: 12px;

            display: flex;

            align-items: center;

            justify-content: center;

            background:
                linear-gradient(
                    135deg,
                    #6d5dfc,
                    #28c7fa
                );

            box-shadow:
                0 8px 25px rgba(109,93,252,0.25);

            font-size: 18px;

            color: white;
        }


        /* =====================================================
           RIGHT NAVIGATION
        ====================================================== */

        .user-area {

            display: flex;

            align-items: center;

            gap: 12px;
        }


        .user-profile {

            display: flex;

            align-items: center;

            gap: 10px;

            padding-right: 5px;
        }


        .avatar {

            width: 38px;

            height: 38px;

            border-radius: 50%;

            background:
                linear-gradient(
                    135deg,
                    #6d5dfc,
                    #28c7fa
                );

            display: flex;

            align-items: center;

            justify-content: center;

            font-weight: 800;

            font-size: 14px;

            color: white;
        }


        .user-name {

            font-size: 14px;

            font-weight: 600;

            color: var(--text);
        }


        .user-role {

            display: block;

            color: var(--muted);

            font-size: 11px;

            margin-top: 1px;
        }


        /* =====================================================
           THEME TOGGLE
        ====================================================== */

        .theme-toggle {

            width: 40px;

            height: 40px;

            border-radius: 11px;

            border:
                1px solid var(--border-light);

            background:
                rgba(255,255,255,0.035);

            color: var(--text-secondary);

            display: flex;

            align-items: center;

            justify-content: center;

            cursor: pointer;

            transition:
                .2s ease;
        }


        html[data-theme="light"] .theme-toggle {

            background:
                rgba(15,23,42,0.04);

        }


        .theme-toggle:hover {

            color: var(--primary);

            border-color:
                rgba(109,93,252,0.35);

            transform:
                translateY(-1px);
        }


        .theme-toggle i {

            font-size: 17px;

            transition:
                transform .25s ease;
        }


        .theme-toggle:hover i {

            transform:
                rotate(15deg);
        }


        /* =====================================================
           LOGOUT
        ====================================================== */

        .logout-btn {

            border:
                1px solid var(--border-light);

            color: var(--text-secondary);

            background:
                rgba(255,255,255,0.025);

            padding:
                9px 15px;

            border-radius:
                10px;

            font-size: 13px;

            font-weight: 600;

            transition:
                .2s ease;
        }


        html[data-theme="light"] .logout-btn {

            background:
                rgba(15,23,42,0.025);

        }


        .logout-btn:hover {

            color: var(--red);

            border-color:
                rgba(255,107,129,0.4);

            background:
                rgba(255,107,129,0.08);
        }


        /* =====================================================
           MAIN
        ====================================================== */

        .dashboard-wrapper {

            max-width: 1250px;

            margin: auto;

            padding:
                48px 20px 70px;
        }


        /* =====================================================
           WELCOME
        ====================================================== */

        .welcome-section {

            margin-bottom: 35px;
        }


        .eyebrow {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            color: var(--primary-light);

            font-size: 11px;

            font-weight: 800;

            letter-spacing: 1.6px;

            text-transform: uppercase;

            margin-bottom: 12px;
        }


        .eyebrow-dot {

            width: 7px;

            height: 7px;

            border-radius: 50%;

            background:
                var(--green);

            box-shadow:
                0 0 12px rgba(49,214,161,0.7);
        }


        .welcome-title {

            font-size:
                clamp(30px, 5vw, 45px);

            font-weight: 800;

            letter-spacing: -1.8px;

            margin: 0;

            line-height: 1.1;

            color: var(--text);
        }


        .welcome-title span {

            background:
                linear-gradient(
                    90deg,
                    var(--text),
                    var(--primary-light)
                );

            -webkit-background-clip: text;

            -webkit-text-fill-color: transparent;
        }


        .welcome-description {

            color: var(--muted);

            margin-top: 13px;

            font-size: 15px;

            max-width: 600px;

            line-height: 1.7;
        }


        /* =====================================================
           STATISTICS
        ====================================================== */

        .stats-grid {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 18px;

            margin-bottom: 48px;
        }


        .stat-card {

            position: relative;

            overflow: hidden;

            padding: 24px;

            min-height: 145px;

            background:
                linear-gradient(
                    145deg,
                    var(--panel),
                    var(--panel-secondary)
                );

            border:
                1px solid var(--border);

            border-radius:
                var(--radius);

            box-shadow:
                var(--card-shadow);

            transition:
                transform .25s ease,
                border-color .25s ease,
                box-shadow .25s ease;
        }


        .stat-card:hover {

            transform:
                translateY(-4px);

            border-color:
                rgba(109,93,252,0.3);
        }


        .stat-card::after {

            content: "";

            position: absolute;

            width: 100px;

            height: 100px;

            border-radius: 50%;

            right: -35px;

            top: -35px;

            background:
                rgba(109,93,252,0.07);
        }


        .stat-top {

            display: flex;

            justify-content: space-between;

            align-items: center;
        }


        .stat-label {

            color: var(--muted);

            font-size: 13px;

            font-weight: 600;

            margin: 0;
        }


        .stat-icon {

            width: 38px;

            height: 38px;

            border-radius: 11px;

            display: flex;

            align-items: center;

            justify-content: center;

            color:
                var(--primary-light);

            background:
                rgba(109,93,252,0.12);

            font-size: 17px;
        }


        .stat-value {

            margin-top: 17px;

            font-size: 31px;

            line-height: 1;

            font-weight: 800;

            letter-spacing: -1px;

            color: var(--text);
        }


        .stat-sub {

            color: var(--muted-dark);

            font-size: 11px;

            margin-top: 8px;
        }


        /* =====================================================
           SECTION HEADERS
        ====================================================== */

        .section-heading {

            display: flex;

            justify-content: space-between;

            align-items: flex-end;

            margin-bottom: 20px;
        }


        .section-title {

            font-size: 21px;

            font-weight: 750;

            margin: 0;

            letter-spacing: -0.5px;

            color: var(--text);
        }


        .section-subtitle {

            color: var(--muted);

            font-size: 13px;

            margin-top: 5px;
        }


        /* =====================================================
           QUIZ GRID
        ====================================================== */

        .quiz-grid {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 18px;

            margin-bottom: 50px;
        }


        /* =====================================================
           QUIZ CARD
        ====================================================== */

        .quiz-card {

            background:
                linear-gradient(
                    150deg,
                    var(--panel),
                    var(--panel-secondary)
                );

            border:
                1px solid var(--border);

            border-radius:
                var(--radius);

            padding: 23px;

            position: relative;

            overflow: hidden;

            transition:
                transform .25s ease,
                border-color .25s ease,
                box-shadow .25s ease;
        }


        .quiz-card::before {

            content: "";

            position: absolute;

            left: 0;

            right: 0;

            top: 0;

            height: 2px;

            background:
                linear-gradient(
                    90deg,
                    #6d5dfc,
                    #28c7fa
                );

            opacity: 0;

            transition:
                .25s;
        }


        .quiz-card:hover {

            transform:
                translateY(-5px);

            border-color:
                rgba(109,93,252,0.35);

            box-shadow:
                var(--shadow);
        }


        .quiz-card:hover::before {

            opacity: 1;
        }


        .quiz-top {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 20px;
        }


        .active-badge {

            display: inline-flex;

            align-items: center;

            gap: 6px;

            padding:
                6px 9px;

            border-radius:
                7px;

            background:
                rgba(49,214,161,0.09);

            color:
                var(--green);

            font-size: 10px;

            font-weight: 800;

            letter-spacing: .7px;
        }


        .active-dot {

            width: 5px;

            height: 5px;

            border-radius: 50%;

            background:
                var(--green);
        }


        .quiz-number {

            color: var(--muted-dark);

            font-size: 11px;

            font-weight: 700;
        }


        .quiz-title {

            font-size: 18px;

            font-weight: 750;

            line-height: 1.35;

            margin-bottom: 9px;

            color: var(--text);
        }


        .quiz-description {

            color: var(--muted);

            font-size: 13px;

            line-height: 1.65;

            min-height: 43px;

            margin-bottom: 20px;
        }


        .quiz-meta {

            display: flex;

            gap: 9px;

            flex-wrap: wrap;

            margin-bottom: 20px;
        }


        .meta-item {

            display: inline-flex;

            align-items: center;

            gap: 6px;

            color: var(--muted);

            background:
                rgba(127,127,127,0.06);

            border:
                1px solid var(--border);

            padding:
                7px 9px;

            border-radius:
                8px;

            font-size: 11px;
        }


        .meta-item i {

            color:
                var(--primary-light);
        }


        /* =====================================================
           START BUTTON
        ====================================================== */

        .start-btn {

            width: 100%;

            border: 0;

            border-radius:
                11px;

            padding:
                12px 15px;

            color: white;

            font-size: 13px;

            font-weight: 700;

            background:
                linear-gradient(
                    135deg,
                    #6d5dfc,
                    #5968ff
                );

            box-shadow:
                0 8px 22px rgba(109,93,252,0.18);

            transition:
                .2s;
        }


        .start-btn:hover {

            color: white;

            transform:
                translateY(-1px);

            box-shadow:
                0 11px 27px
                rgba(109,93,252,0.3);
        }


        /* =====================================================
           EMPTY STATE
        ====================================================== */

        .empty-state {

            padding:
                55px 20px;

            text-align:
                center;

            background:
                var(--panel);

            border:
                1px dashed var(--border-light);

            border-radius:
                var(--radius);
        }


        .empty-icon {

            width: 55px;

            height: 55px;

            margin:
                auto auto 15px;

            border-radius:
                16px;

            display: flex;

            align-items: center;

            justify-content: center;

            background:
                rgba(109,93,252,0.1);

            color:
                var(--primary-light);

            font-size: 23px;
        }


        .empty-state h4 {

            font-size: 17px;

            margin-bottom: 7px;

            color: var(--text);
        }


        .empty-state p {

            color: var(--muted);

            margin: 0;

            font-size: 13px;
        }


        /* =====================================================
           RESULTS
        ====================================================== */

        .results-card {

            background:
                var(--panel);

            border:
                1px solid var(--border);

            border-radius:
                var(--radius);

            overflow: hidden;

            box-shadow:
                var(--card-shadow);
        }


        .results-table {

            margin: 0;

            color:
                var(--text);

            --bs-table-bg:
                transparent;

            --bs-table-color:
                var(--text);

            --bs-table-border-color:
                var(--border);
        }


        .results-table thead th {

            color:
                var(--muted);

            text-transform:
                uppercase;

            font-size: 10px;

            letter-spacing: 1px;

            font-weight: 800;

            padding:
                17px 20px;

            border-bottom:
                1px solid var(--border);
        }


        .results-table tbody td {

            padding:
                17px 20px;

            vertical-align:
                middle;

            border-bottom:
                1px solid var(--border);

            font-size: 13px;
        }


        .results-table tbody tr {

            transition:
                .18s;
        }


        .results-table tbody tr:hover {

            background:
                rgba(109,93,252,0.035);
        }


        .quiz-result-name {

            font-weight:
                650;

            color:
                var(--text);
        }


        .score-value {

            font-weight:
                750;

            color:
                var(--text-secondary);
        }


        .percentage-badge {

            display:
                inline-flex;

            align-items:
                center;

            padding:
                6px 9px;

            border-radius:
                7px;

            background:
                rgba(109,93,252,0.1);

            color:
                var(--primary-light);

            font-weight:
                700;

            font-size:
                11px;
        }


        .date-text {

            color:
                var(--muted);

            font-size:
                12px;
        }


        .result-btn {

            border:
                1px solid
                rgba(109,93,252,0.3);

            color:
                var(--primary-light);

            background:
                rgba(109,93,252,0.06);

            padding:
                7px 11px;

            border-radius:
                8px;

            font-size:
                11px;

            font-weight:
                700;

            transition:
                .2s;
        }


        .result-btn:hover {

            color:
                var(--text);

            background:
                rgba(109,93,252,0.18);

            border-color:
                rgba(109,93,252,0.5);
        }


        /* =====================================================
           FOOTER
        ====================================================== */

        .premium-footer {

            border-top:
                1px solid var(--border);

            background:
                var(--bg-secondary);

            padding:
                28px 0;

            color:
                var(--muted);

            font-size:
                12px;
        }


        .footer-brand {

            color:
                var(--text-secondary);

            font-weight:
                700;
        }


        /* =====================================================
           RESPONSIVE
        ====================================================== */

        @media (max-width: 992px) {

            .quiz-grid {

                grid-template-columns:
                    repeat(2, 1fr);
            }

        }


        @media (max-width: 768px) {

            .dashboard-wrapper {

                padding-top:
                    35px;
            }


            .stats-grid {

                grid-template-columns:
                    1fr;
            }


            .quiz-grid {

                grid-template-columns:
                    1fr;
            }


            .user-name,
            .user-role {

                display:
                    none;
            }


            .user-profile {

                padding:
                    0;
            }


            .welcome-title {

                font-size:
                    34px;
            }


            .results-table {

                min-width:
                    700px;
            }

        }


        @media (max-width: 480px) {

            .user-area {

                gap:
                    7px;
            }


            .logout-btn span {

                display:
                    none;
            }


            .logout-btn {

                width:
                    38px;

                height:
                    38px;

                padding:
                    0;

                display:
                    flex;

                align-items:
                    center;

                justify-content:
                    center;
            }


            .theme-toggle {

                width:
                    38px;

                height:
                    38px;
            }


            .brand {

                font-size:
                    18px;
            }


            .brand-mark {

                width:
                    36px;

                height:
                    36px;
            }

        }

    </style>


    <!-- =====================================================
         APPLY SAVED THEME BEFORE PAGE RENDERS
    ====================================================== -->

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

</head>


<body>


<!-- =====================================================
     NAVIGATION
====================================================== -->

<nav class="premium-navbar">

    <div class="container">

        <div class="navbar-inner">


            <!-- BRAND -->

            <a
                href="../index.php"
                class="brand">

                <span class="brand-mark">
                    Q
                </span>

                QuizCore

            </a>



            <!-- USER AREA -->

            <div class="user-area">


                <!-- USER -->

                <div class="user-profile">

                    <div class="avatar">

                        <?php

                        echo strtoupper(
                            substr(
                                $studentName,
                                0,
                                1
                            )
                        );

                        ?>

                    </div>


                    <div>

                        <div class="user-name">

                            <?php

                            echo htmlspecialchars(
                                $studentName
                            );

                            ?>

                        </div>


                        <span class="user-role">

                            Student

                        </span>

                    </div>

                </div>



                <!-- THEME TOGGLE -->

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



                <!-- LOGOUT -->

                <a
                    href="../logout.php"
                    class="logout-btn">

                    <i
                        class="bi bi-box-arrow-right">
                    </i>

                    <span class="ms-1">
                        Logout
                    </span>

                </a>


            </div>

        </div>

    </div>

</nav>



<!-- =====================================================
     MAIN DASHBOARD
====================================================== -->

<main>

    <div class="dashboard-wrapper">


        <!-- =================================================
             WELCOME
        ================================================== -->

        <section class="welcome-section">


            <div class="eyebrow">

                <span class="eyebrow-dot"></span>

                Student Dashboard

            </div>


            <h1 class="welcome-title">

                Welcome back,
                <span>

                    <?php

                    echo htmlspecialchars(
                        $studentName
                    );

                    ?>

                </span>

            </h1>


            <p class="welcome-description">

                Continue your learning journey,
                challenge yourself with a quiz,
                and track your performance over time.

            </p>


        </section>



        <!-- =================================================
             STATISTICS
        ================================================== -->

        <section class="stats-grid">


            <!-- ATTEMPTS -->

            <div class="stat-card">

                <div class="stat-top">

                    <p class="stat-label">
                        Total Attempts
                    </p>


                    <div class="stat-icon">

                        <i class="bi bi-layers"></i>

                    </div>

                </div>


                <div class="stat-value">

                    <?php

                    echo number_format(
                        $attempts
                    );

                    ?>

                </div>


                <div class="stat-sub">

                    Quizzes completed

                </div>

            </div>



            <!-- AVERAGE -->

            <div class="stat-card">

                <div class="stat-top">

                    <p class="stat-label">
                        Average Score
                    </p>


                    <div class="stat-icon">

                        <i
                            class="bi bi-graph-up-arrow">
                        </i>

                    </div>

                </div>


                <div class="stat-value">

                    <?php

                    echo number_format(
                        (float)$average,
                        1
                    );

                    ?>%

                </div>


                <div class="stat-sub">

                    Overall performance

                </div>

            </div>



            <!-- AVAILABLE -->

            <div class="stat-card">

                <div class="stat-top">

                    <p class="stat-label">
                        Available Quizzes
                    </p>


                    <div class="stat-icon">

                        <i
                            class="bi bi-grid-1x2">
                        </i>

                    </div>

                </div>


                <div class="stat-value">

                    <?php

                    echo $quizzes->num_rows;

                    ?>

                </div>


                <div class="stat-sub">

                    Ready to attempt

                </div>

            </div>


        </section>



        <!-- =================================================
             AVAILABLE QUIZZES
        ================================================== -->

        <section>


            <div class="section-heading">


                <div>

                    <h2 class="section-title">

                        Available Quizzes

                    </h2>


                    <p class="section-subtitle">

                        Choose a quiz and put your
                        knowledge to the test.

                    </p>

                </div>


            </div>



            <div class="quiz-grid">


                <?php if ($quizzes->num_rows > 0): ?>


                    <?php

                    $quizNumber = 1;

                    while (
                        $quiz =
                        $quizzes->fetch_assoc()
                    ):

                    ?>


                        <article class="quiz-card">


                            <div class="quiz-top">


                                <span
                                    class="active-badge">

                                    <span
                                        class="active-dot">
                                    </span>

                                    ACTIVE

                                </span>


                                <span
                                    class="quiz-number">

                                    #<?= str_pad(
                                        $quizNumber,
                                        2,
                                        "0",
                                        STR_PAD_LEFT
                                    ) ?>

                                </span>


                            </div>



                            <h3 class="quiz-title">

                                <?php

                                echo htmlspecialchars(
                                    $quiz["title"]
                                );

                                ?>

                            </h3>



                            <p
                                class="quiz-description">

                                <?php

                                echo htmlspecialchars(
                                    $quiz["description"]
                                );

                                ?>

                            </p>



                            <div class="quiz-meta">


                                <span class="meta-item">

                                    <i
                                        class="bi bi-clock">
                                    </i>

                                    <?php

                                    echo $quiz[
                                        "duration"
                                    ];

                                    ?>

                                    min

                                </span>


                                <span class="meta-item">

                                    <i
                                        class="bi bi-patch-check">
                                    </i>

                                    Active

                                </span>


                            </div>



                            <a
                                href="../quiz/start.php?id=<?php echo $quiz["id"]; ?>"
                                class="start-btn d-flex align-items-center justify-content-center">

                                Start Quiz

                                <i
                                    class="bi bi-arrow-right ms-1">
                                </i>

                            </a>


                        </article>


                    <?php

                        $quizNumber++;

                    endwhile;

                    ?>


                <?php else: ?>


                    <div
                        class="empty-state"
                        style="grid-column: 1 / -1;">


                        <div class="empty-icon">

                            <i
                                class="bi bi-journal-x">
                            </i>

                        </div>


                        <h4>

                            No quizzes available

                        </h4>


                        <p>

                            There are currently no
                            active quizzes.
                            Please check again later.

                        </p>


                    </div>


                <?php endif; ?>


            </div>

        </section>



        <!-- =================================================
             PREVIOUS RESULTS
        ================================================== -->

        <section>


            <div class="section-heading">


                <div>

                    <h2 class="section-title">

                        Recent Results

                    </h2>


                    <p class="section-subtitle">

                        Review your previous quiz
                        performance.

                    </p>

                </div>


            </div>



            <div class="results-card">


                <div class="table-responsive">


                    <table class="table results-table">


                        <thead>

                            <tr>

                                <th>
                                    Quiz
                                </th>

                                <th>
                                    Score
                                </th>

                                <th>
                                    Percentage
                                </th>

                                <th>
                                    Attempted
                                </th>

                                <th class="text-end">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            <?php if (
                                $previousResults->num_rows > 0
                            ): ?>


                                <?php

                                while (
                                    $row =
                                    $previousResults
                                    ->fetch_assoc()
                                ):

                                ?>


                                    <tr>


                                        <td>

                                            <div
                                                class="quiz-result-name">

                                                <?php

                                                echo htmlspecialchars(
                                                    $row["title"]
                                                );

                                                ?>

                                            </div>

                                        </td>



                                        <td>

                                            <span
                                                class="score-value">

                                                <?php

                                                echo $row[
                                                    "score"
                                                ];

                                                ?>

                                                /

                                                <?php

                                                echo $row[
                                                    "total_questions"
                                                ];

                                                ?>

                                            </span>

                                        </td>



                                        <td>

                                            <span
                                                class="percentage-badge">

                                                <?php

                                                echo number_format(
                                                    (float)
                                                    $row[
                                                        "percentage"
                                                    ],
                                                    1
                                                );

                                                ?>%

                                            </span>

                                        </td>



                                        <td>

                                            <span
                                                class="date-text">

                                                <?php

                                                echo date(
                                                    "d M Y, h:i A",
                                                    strtotime(
                                                        $row[
                                                            "attempted_at"
                                                        ]
                                                    )
                                                );

                                                ?>

                                            </span>

                                        </td>



                                        <td
                                            class="text-end">

                                            <a
                                                href="../quiz/result.php?id=<?php echo $row["id"]; ?>"
                                                class="result-btn">

                                                View Result

                                                <i
                                                    class="bi bi-arrow-up-right ms-1">
                                                </i>

                                            </a>

                                        </td>


                                    </tr>


                                <?php endwhile; ?>


                            <?php else: ?>


                                <tr>

                                    <td
                                        colspan="5"
                                        class="text-center py-5">


                                        <div
                                            class="empty-icon">

                                            <i
                                                class="bi bi-bar-chart">
                                            </i>

                                        </div>


                                        <div class="mt-2">

                                            <strong>

                                                No quiz attempts yet

                                            </strong>

                                        </div>


                                        <div
                                            class="date-text mt-1">

                                            Complete your first
                                            quiz to see your
                                            results here.

                                        </div>


                                    </td>

                                </tr>


                            <?php endif; ?>


                        </tbody>

                    </table>


                </div>

            </div>


        </section>


    </div>

</main>



<!-- =====================================================
     FOOTER
====================================================== -->

<footer class="premium-footer">

    <div class="container">


        <div
            class="d-flex flex-column
                   flex-md-row
                   justify-content-between
                   align-items-center
                   gap-2">


            <div>

                <span class="footer-brand">

                    QuizCore

                </span>


                <span class="ms-2">

                    Student Online Quiz System

                </span>

            </div>


            <div>

                © <?php echo date("Y"); ?>

                QuizCore

            </div>


        </div>

    </div>

</footer>



<!-- =====================================================
     BOOTSTRAP JS
====================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>



<!-- =====================================================
     DARK / LIGHT MODE
====================================================== -->

<script>

    const themeToggle =
        document.getElementById("themeToggle");

    const themeIcon =
        document.getElementById("themeIcon");


    function updateThemeIcon(theme) {

        if (theme === "light") {

            themeIcon.className =
                "bi bi-moon-stars-fill";

            themeToggle.setAttribute(
                "title",
                "Switch to dark mode"
            );

            themeToggle.setAttribute(
                "aria-label",
                "Switch to dark mode"
            );

        } else {

            themeIcon.className =
                "bi bi-sun-fill";

            themeToggle.setAttribute(
                "title",
                "Switch to light mode"
            );

            themeToggle.setAttribute(
                "aria-label",
                "Switch to light mode"
            );

        }

    }


    function setTheme(theme) {

        document.documentElement
            .setAttribute(
                "data-theme",
                theme
            );

        localStorage.setItem(
            "quizcore-theme",
            theme
        );

        updateThemeIcon(theme);

    }


    const currentTheme =
        localStorage.getItem(
            "quizcore-theme"
        ) || "dark";


    setTheme(currentTheme);


    themeToggle.addEventListener(
        "click",
        function () {

            const activeTheme =
                document.documentElement
                    .getAttribute(
                        "data-theme"
                    );

            if (activeTheme === "dark") {

                setTheme("light");

            } else {

                setTheme("dark");

            }

        }
    );

</script>


</body>

</html>