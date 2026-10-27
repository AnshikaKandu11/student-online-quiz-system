<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>QuizCore | Student Online Quiz System</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="assets/css/style.css">

    <style>

        /* =========================================================
           QUIZCORE HOMEPAGE
           Premium UI + Dark / Light Mode
           Logic and links unchanged
        ========================================================= */

        :root {
            --bg: #070b14;
            --bg-secondary: #0b1120;
            --surface: #101827;
            --surface-2: #141e30;
            --border: rgba(255,255,255,0.08);
            --text: #f8fafc;
            --muted: #94a3b8;
            --primary: #6366f1;
            --primary-2: #8b5cf6;
            --cyan: #22d3ee;
            --success: #22c55e;
            --shadow: 0 20px 60px rgba(0,0,0,0.30);
        }

        html[data-theme="light"] {
            --bg: #f5f7fb;
            --bg-secondary: #ffffff;
            --surface: #ffffff;
            --surface-2: #f8fafc;
            --border: rgba(15,23,42,0.09);
            --text: #0f172a;
            --muted: #64748b;
            --primary: #4f46e5;
            --primary-2: #7c3aed;
            --cyan: #0891b2;
            --success: #16a34a;
            --shadow: 0 20px 50px rgba(15,23,42,0.10);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;

            background:
                radial-gradient(
                    circle at 10% 10%,
                    rgba(99,102,241,0.12),
                    transparent 30%
                ),
                radial-gradient(
                    circle at 90% 20%,
                    rgba(34,211,238,0.08),
                    transparent 28%
                ),
                var(--bg);

            color: var(--text);
            transition:
                background .3s ease,
                color .3s ease;
        }

        /* =========================================================
           NAVBAR
        ========================================================= */

        .tech-navbar {
            background: rgba(7,11,20,0.78) !important;
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            padding: 16px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        html[data-theme="light"] .tech-navbar {
            background: rgba(255,255,255,0.82) !important;
        }

        .navbar-brand {
            color: var(--text) !important;
            font-size: 1.25rem;
            letter-spacing: -0.4px;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;

            margin-right: 8px;

            border-radius: 11px;

            background:
                linear-gradient(
                    135deg,
                    var(--primary),
                    var(--primary-2)
                );

            color: white;
            font-weight: 800;

            box-shadow:
                0 8px 25px rgba(99,102,241,0.35);
        }

        .nav-link {
            color: var(--muted) !important;
            font-weight: 500;
            margin: 0 4px;
            transition: .25s ease;
        }

        .nav-link:hover {
            color: var(--text) !important;
        }

        .btn-tech {
            border: none;
            color: white !important;

            background:
                linear-gradient(
                    135deg,
                    var(--primary),
                    var(--primary-2)
                );

            padding: 11px 20px;
            border-radius: 12px;

            font-weight: 700;

            box-shadow:
                0 10px 28px rgba(99,102,241,0.25);

            transition: .25s ease;
        }

        .btn-tech:hover {
            transform: translateY(-2px);

            box-shadow:
                0 15px 35px rgba(99,102,241,0.35);
        }

        .btn-outline-tech {
            color: var(--text);
            border: 1px solid var(--border);
            background: var(--surface);
            padding: 11px 20px;
            border-radius: 12px;
            font-weight: 600;
            transition: .25s ease;
        }

        .btn-outline-tech:hover {
            color: var(--text);
            border-color: rgba(99,102,241,0.45);
            transform: translateY(-2px);
            background: var(--surface-2);
        }

        .theme-toggle {
            width: 42px;
            height: 42px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border-radius: 12px;

            border: 1px solid var(--border);
            background: var(--surface);

            color: var(--text);

            margin-left: 8px;

            transition: .25s ease;
        }

        .theme-toggle:hover {
            transform: translateY(-2px);
            border-color: rgba(99,102,241,0.4);
        }

        /* =========================================================
           HERO
        ========================================================= */

        .hero {
            min-height: 720px;
            display: flex;
            align-items: center;

            padding:
                90px 0
                80px;

            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: "";
            position: absolute;

            width: 500px;
            height: 500px;

            border-radius: 50%;

            background:
                radial-gradient(
                    circle,
                    rgba(99,102,241,0.16),
                    transparent 70%
                );

            top: -180px;
            left: -180px;

            pointer-events: none;
        }

        .hero::after {
            content: "";
            position: absolute;

            width: 450px;
            height: 450px;

            border-radius: 50%;

            background:
                radial-gradient(
                    circle,
                    rgba(34,211,238,0.09),
                    transparent 70%
                );

            right: -150px;
            bottom: -150px;

            pointer-events: none;
        }

        .hero .container {
            position: relative;
            z-index: 2;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;

            padding: 8px 14px;

            border: 1px solid var(--border);
            border-radius: 999px;

            background: var(--surface);

            color: var(--muted);

            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .8px;

            box-shadow: var(--shadow);
        }

        .status-dot {
            width: 7px;
            height: 7px;

            border-radius: 50%;

            background: var(--success);

            box-shadow:
                0 0 0 5px rgba(34,197,94,0.10);
        }

        .hero h1 {
            margin-top: 22px;

            font-size:
                clamp(3rem, 6vw, 5.4rem);

            line-height: 1.02;

            letter-spacing: -3px;

            font-weight: 800;

            max-width: 800px;
        }

        .gradient-text {
            background:
                linear-gradient(
                    135deg,
                    #818cf8,
                    #a78bfa,
                    #22d3ee
                );

            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .hero-description {
            color: var(--muted);
            font-size: 1.12rem;
            line-height: 1.8;
            max-width: 680px;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 30px;
        }

        .hero-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 22px;

            margin-top: 28px;

            color: var(--muted);
            font-size: .9rem;
        }

        .hero-meta span {
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }

        .hero-meta i {
            color: var(--cyan);
        }

        /* =========================================================
           QUIZ PREVIEW
        ========================================================= */

        .hero-preview {
            position: relative;

            background:
                linear-gradient(
                    145deg,
                    var(--surface),
                    var(--surface-2)
                );

            border:
                1px solid
                var(--border);

            border-radius: 24px;

            padding: 28px;

            box-shadow:
                0 30px 80px rgba(0,0,0,0.30);

            overflow: hidden;
        }

        .hero-preview::before {
            content: "";

            position: absolute;

            width: 180px;
            height: 180px;

            border-radius: 50%;

            background:
                rgba(99,102,241,0.12);

            top: -90px;
            right: -70px;
        }

        .preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .preview-label {
            color: var(--muted);
            font-size: .72rem;
            letter-spacing: 1.2px;
            font-weight: 700;
        }

        .preview-title {
            font-weight: 700;
            margin-top: 4px;
        }

        .active-badge {
            padding: 7px 11px;
            border-radius: 999px;

            color: #86efac;
            background: rgba(34,197,94,0.10);

            border:
                1px solid
                rgba(34,197,94,0.20);

            font-size: .72rem;
            font-weight: 800;
        }

        .preview-bar {
            height: 8px;

            background:
                rgba(148,163,184,0.12);

            border-radius: 99px;

            overflow: hidden;
        }

        .preview-progress {
            width: 68%;
            height: 100%;

            background:
                linear-gradient(
                    90deg,
                    var(--primary),
                    var(--cyan)
                );

            border-radius: inherit;
        }

        .preview-question {
            font-size: 1.1rem;
            line-height: 1.6;
            font-weight: 700;
        }

        .quiz-option {
            padding: 14px 16px;

            border: 1px solid var(--border);
            border-radius: 13px;

            background:
                rgba(255,255,255,0.025);

            margin-bottom: 10px;

            color: var(--muted);

            transition: .2s ease;
        }

        html[data-theme="light"] .quiz-option {
            background: #f8fafc;
        }

        .quiz-option:hover {
            border-color:
                rgba(99,102,241,0.40);

            color: var(--text);

            transform: translateX(3px);
        }

        .quiz-option.active {
            color: var(--text);

            border-color:
                rgba(99,102,241,0.45);

            background:
                rgba(99,102,241,0.10);
        }

        .option-radio {
            width: 17px;
            height: 17px;

            border: 2px solid #64748b;

            border-radius: 50%;

            display: inline-block;

            margin-right: 10px;

            vertical-align: -3px;
        }

        .quiz-option.active .option-radio {
            border-color: var(--primary);
            box-shadow:
                inset 0 0 0 4px var(--surface);
            background: var(--primary);
        }

        /* =========================================================
           STATS
        ========================================================= */

        .stats-section {
            padding: 20px 0 80px;
        }

        .stat-card {
            position: relative;

            padding: 30px 25px;

            background:
                linear-gradient(
                    145deg,
                    var(--surface),
                    var(--surface-2)
                );

            border: 1px solid var(--border);

            border-radius: 20px;

            box-shadow: var(--shadow);

            transition: .25s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color:
                rgba(99,102,241,0.25);
        }

        .stat-icon {
            width: 46px;
            height: 46px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border-radius: 14px;

            margin-bottom: 16px;

            color: #a5b4fc;

            background:
                rgba(99,102,241,0.10);

            font-size: 1.25rem;
        }

        .big-number {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .muted {
            color: var(--muted) !important;
        }

        /* =========================================================
           SECTIONS
        ========================================================= */

        .section-space {
            padding: 100px 0;
        }

        .section-label {
            color: var(--cyan);

            font-size: .78rem;
            font-weight: 800;

            letter-spacing: 1.5px;

            margin-bottom: 12px;
        }

        .section-title {
            font-size:
                clamp(2rem, 4vw, 3rem);

            font-weight: 800;

            letter-spacing: -1.5px;
        }

        .section-description {
            color: var(--muted);
            max-width: 650px;
            margin: 15px auto 0;
            line-height: 1.7;
        }

        /* =========================================================
           FEATURE CARDS
        ========================================================= */

        .tech-card {
            height: 100%;

            padding: 28px;

            background:
                linear-gradient(
                    145deg,
                    var(--surface),
                    var(--surface-2)
                );

            border: 1px solid var(--border);

            border-radius: 20px;

            box-shadow:
                0 15px 40px rgba(0,0,0,0.12);

            transition:
                transform .25s ease,
                border-color .25s ease,
                box-shadow .25s ease;
        }

        .tech-card:hover {
            transform: translateY(-6px);

            border-color:
                rgba(99,102,241,0.30);

            box-shadow:
                0 25px 60px rgba(0,0,0,0.18);
        }

        .feature-icon {
            width: 52px;
            height: 52px;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border-radius: 15px;

            margin-bottom: 20px;

            font-size: 1.35rem;

            color: #c4b5fd;

            background:
                linear-gradient(
                    135deg,
                    rgba(99,102,241,0.15),
                    rgba(139,92,246,0.08)
                );

            border:
                1px solid
                rgba(99,102,241,0.15);
        }

        .tech-card h4 {
            font-weight: 750;
            margin-bottom: 12px;
        }

        .tech-card p {
            line-height: 1.7;
            margin-bottom: 0;
        }

        /* =========================================================
           HOW IT WORKS
        ========================================================= */

        .step-card {
            position: relative;
        }

        .auth-icon {
            width: 58px;
            height: 58px;

            display: flex;
            align-items: center;
            justify-content: center;

            margin:
                0 auto
                22px;

            border-radius: 17px;

            background:
                linear-gradient(
                    135deg,
                    var(--primary),
                    var(--primary-2)
                );

            color: white;

            font-size: 1.15rem;
            font-weight: 800;

            box-shadow:
                0 12px 30px
                rgba(99,102,241,0.25);
        }

        .step-number {
            position: absolute;

            top: 18px;
            right: 18px;

            color: var(--muted);

            font-size: .72rem;
            font-weight: 800;

            letter-spacing: 1px;
        }

        /* =========================================================
           CTA
        ========================================================= */

        .cta-card {
            position: relative;
            overflow: hidden;

            padding: 70px 35px;

            border:
                1px solid
                rgba(99,102,241,0.20);

            border-radius: 28px;

            background:
                radial-gradient(
                    circle at 20% 20%,
                    rgba(99,102,241,0.18),
                    transparent 35%
                ),
                radial-gradient(
                    circle at 80% 80%,
                    rgba(34,211,238,0.10),
                    transparent 35%
                ),
                var(--surface);

            box-shadow: var(--shadow);
        }

        .cta-card h2 {
            position: relative;
            z-index: 2;
        }

        .cta-card p {
            position: relative;
            z-index: 2;
        }

        .cta-card .btn {
            position: relative;
            z-index: 2;
        }

        /* =========================================================
           FOOTER
        ========================================================= */

        .tech-footer {
            border-top: 1px solid var(--border);

            background:
                rgba(0,0,0,0.10);

            padding: 45px 0;

            color: var(--muted);
        }

        .tech-footer h5 {
            color: var(--text);
            font-weight: 750;
        }

        .tech-footer p {
            margin-bottom: 8px;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 991px) {

            .navbar-collapse {
                padding-top: 18px;
            }

            .theme-toggle {
                margin-left: 0;
                margin-top: 10px;
            }

            .hero {
                min-height: auto;
                padding: 70px 0;
            }

            .hero h1 {
                letter-spacing: -2px;
            }

        }

        @media (max-width: 767px) {

            .hero {
                padding: 55px 0;
            }

            .hero h1 {
                font-size: 3rem;
            }

            .hero-preview {
                margin-top: 10px;
            }

            .section-space {
                padding: 70px 0;
            }

            .cta-card {
                padding: 50px 24px;
            }

            .tech-footer {
                text-align: center;
            }

            .tech-footer .text-md-end {
                text-align: center !important;
                margin-top: 20px;
            }

        }

    </style>

    <!-- Apply saved theme before page renders -->
    <script>

        (function () {

            const savedTheme =
                localStorage.getItem("quizcore-theme");

            if (savedTheme === "light") {

                document.documentElement
                    .setAttribute("data-theme", "light");

            } else {

                document.documentElement
                    .setAttribute("data-theme", "dark");

            }

        })();

    </script>

</head>


<body>


<!-- =========================================================
     NAVBAR
========================================================= -->

<nav class="navbar navbar-expand-lg navbar-dark tech-navbar">

    <div class="container">

        <a
            class="navbar-brand fw-bold"
            href="index.php">

            <span class="brand-icon">
                Q
            </span>

            QuizCore

        </a>


        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNav">

            <span class="navbar-toggler-icon"></span>

        </button>


        <div
            class="collapse navbar-collapse"
            id="mainNav">

            <ul
                class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="#features">

                        Features

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="#how">

                        How It Works

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="admin/login.php">

                        Admin

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="student/login.php">

                        Student Login

                    </a>

                </li>


                <li class="nav-item ms-lg-2">

                    <a
                        class="btn btn-tech"
                        href="student/register.php">

                        Get Started

                    </a>

                </li>


                <li class="nav-item">

                    <button
                        type="button"
                        class="theme-toggle"
                        id="themeToggle"
                        aria-label="Toggle theme">

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
     HERO
========================================================= -->

<section class="hero">

    <div class="container">

        <div class="row align-items-center g-5">


            <div class="col-lg-7">


                <span class="hero-badge">

                    <span class="status-dot"></span>

                    ONLINE ASSESSMENT PLATFORM

                </span>


                <h1>

                    Test Your Knowledge.

                    <span class="gradient-text">

                        Track Your Progress.

                    </span>

                </h1>


                <p class="hero-description mt-4">

                    A modern Student Online Quiz System that allows
                    students to attempt quizzes online and receive
                    instant results while administrators manage
                    questions, quizzes and student performance.

                </p>


                <div class="hero-actions">

                    <a
                        href="student/register.php"
                        class="btn btn-tech">

                        Start Learning

                        <i class="bi bi-arrow-right ms-2"></i>

                    </a>


                    <a
                        href="#features"
                        class="btn btn-outline-tech">

                        Explore Features

                        <i class="bi bi-grid ms-2"></i>

                    </a>

                </div>


                <div class="hero-meta">

                    <span>

                        <i class="bi bi-check-circle-fill"></i>

                        Instant Results

                    </span>


                    <span>

                        <i class="bi bi-shield-check"></i>

                        Secure Authentication

                    </span>


                    <span>

                        <i class="bi bi-clock"></i>

                        Timed Assessments

                    </span>

                </div>


            </div>



            <!-- QUIZ PREVIEW -->

            <div class="col-lg-5">

                <div class="hero-preview">


                    <div class="preview-header mb-4">

                        <div>

                            <div class="preview-label">

                                CURRENT QUIZ

                            </div>

                            <div class="preview-title">

                                Computer Science

                            </div>

                        </div>


                        <span class="active-badge">

                            <i class="bi bi-circle-fill me-1"></i>

                            ACTIVE

                        </span>

                    </div>


                    <div class="preview-bar mb-4">

                        <div class="preview-progress"></div>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="muted small">

                            Question 17 of 25

                        </span>

                        <span class="muted small">

                            68%

                        </span>

                    </div>


                    <p class="preview-question">

                        Which language is used to structure
                        web pages?

                    </p>


                    <div class="mt-4">


                        <div class="quiz-option">

                            <span class="option-radio"></span>

                            Python

                        </div>


                        <div class="quiz-option active">

                            <span class="option-radio"></span>

                            HTML

                        </div>


                        <div class="quiz-option">

                            <span class="option-radio"></span>

                            MySQL

                        </div>


                        <div class="quiz-option">

                            <span class="option-radio"></span>

                            PHP

                        </div>


                    </div>


                </div>

            </div>

        </div>

    </div>

</section>



<!-- =========================================================
     STATS
========================================================= -->

<section class="stats-section">

    <div class="container">

        <div class="row g-4">


            <div class="col-md-4">

                <div class="stat-card text-center">

                    <div class="stat-icon mx-auto">

                        <i class="bi bi-check2-circle"></i>

                    </div>

                    <div class="big-number">

                        100%

                    </div>

                    <p class="muted mb-0">

                        Digital Evaluation

                    </p>

                </div>

            </div>



            <div class="col-md-4">

                <div class="stat-card text-center">

                    <div class="stat-icon mx-auto">

                        <i class="bi bi-calendar-check"></i>

                    </div>

                    <div class="big-number">

                        24/7

                    </div>

                    <p class="muted mb-0">

                        Quiz Accessibility

                    </p>

                </div>

            </div>



            <div class="col-md-4">

                <div class="stat-card text-center">

                    <div class="stat-icon mx-auto">

                        <i class="bi bi-lightning-charge-fill"></i>

                    </div>

                    <div class="big-number">

                        Instant

                    </div>

                    <p class="muted mb-0">

                        Result Generation

                    </p>

                </div>

            </div>


        </div>

    </div>

</section>



<!-- =========================================================
     FEATURES
========================================================= -->

<section
    id="features"
    class="section-space">

    <div class="container">


        <div class="text-center mb-5">

            <p class="section-label">

                PLATFORM FEATURES

            </p>


            <h2 class="section-title">

                Everything Needed for Online Quizzes

            </h2>


            <p class="section-description">

                A complete platform for students and administrators.

            </p>

        </div>



        <div class="row g-4">


            <div class="col-md-4">

                <div class="tech-card">

                    <div class="feature-icon">

                        <i class="bi bi-journal-text"></i>

                    </div>


                    <h4>

                        Online Quizzes

                    </h4>


                    <p class="muted">

                        Students can access available quizzes,
                        answer MCQ questions and submit their
                        responses online.

                    </p>

                </div>

            </div>



            <div class="col-md-4">

                <div class="tech-card">

                    <div class="feature-icon">

                        <i class="bi bi-lightning-charge"></i>

                    </div>


                    <h4>

                        Instant Results

                    </h4>


                    <p class="muted">

                        Answers are automatically evaluated and
                        the student's score and percentage are
                        generated immediately.

                    </p>

                </div>

            </div>



            <div class="col-md-4">

                <div class="tech-card">

                    <div class="feature-icon">

                        <i class="bi bi-stopwatch"></i>

                    </div>


                    <h4>

                        Timer-Based Tests

                    </h4>


                    <p class="muted">

                        Each quiz can have a predefined duration
                        to provide a structured online assessment.

                    </p>

                </div>

            </div>



            <div class="col-md-4">

                <div class="tech-card">

                    <div class="feature-icon">

                        <i class="bi bi-person-badge"></i>

                    </div>


                    <h4>

                        Admin Management

                    </h4>


                    <p class="muted">

                        Administrators can create quizzes,
                        manage questions and view student records.

                    </p>

                </div>

            </div>



            <div class="col-md-4">

                <div class="tech-card">

                    <div class="feature-icon">

                        <i class="bi bi-bar-chart-line"></i>

                    </div>


                    <h4>

                        Performance Tracking

                    </h4>


                    <p class="muted">

                        Student attempts and scores are stored
                        digitally for performance analysis.

                    </p>

                </div>

            </div>



            <div class="col-md-4">

                <div class="tech-card">

                    <div class="feature-icon">

                        <i class="bi bi-shield-lock"></i>

                    </div>


                    <h4>

                        Secure Login

                    </h4>


                    <p class="muted">

                        Separate authentication is provided for
                        students and administrators.

                    </p>

                </div>

            </div>


        </div>

    </div>

</section>



<!-- =========================================================
     HOW IT WORKS
========================================================= -->

<section
    id="how"
    class="section-space">

    <div class="container">


        <div class="text-center mb-5">

            <p class="section-label">

                HOW IT WORKS

            </p>


            <h2 class="section-title">

                Simple Three-Step Process

            </h2>

        </div>



        <div class="row g-4">


            <div class="col-md-4">

                <div class="tech-card text-center step-card">

                    <span class="step-number">

                        STEP 01

                    </span>


                    <div class="auth-icon">

                        1

                    </div>


                    <h4>

                        Register

                    </h4>


                    <p class="muted">

                        Create a student account using your
                        name, email and password.

                    </p>

                </div>

            </div>



            <div class="col-md-4">

                <div class="tech-card text-center step-card">

                    <span class="step-number">

                        STEP 02

                    </span>


                    <div class="auth-icon">

                        2

                    </div>


                    <h4>

                        Attempt Quiz

                    </h4>


                    <p class="muted">

                        Select an available quiz and answer
                        the questions within the given time.

                    </p>

                </div>

            </div>



            <div class="col-md-4">

                <div class="tech-card text-center step-card">

                    <span class="step-number">

                        STEP 03

                    </span>


                    <div class="auth-icon">

                        3

                    </div>


                    <h4>

                        Get Result

                    </h4>


                    <p class="muted">

                        Submit your answers and receive your
                        score and percentage instantly.

                    </p>

                </div>

            </div>


        </div>

    </div>

</section>



<!-- =========================================================
     CTA
========================================================= -->

<section class="section-space pt-0">

    <div class="container">

        <div class="cta-card text-center">

            <h2 class="section-title">

                Ready to Test Your Knowledge?

            </h2>


            <p class="muted mt-3">

                Register today and start attempting online quizzes.

            </p>


            <a
                href="student/register.php"
                class="btn btn-tech mt-3">

                Create Student Account

                <i class="bi bi-arrow-right ms-2"></i>

            </a>

        </div>

    </div>

</section>



<!-- =========================================================
     FOOTER
========================================================= -->

<footer class="tech-footer">

    <div class="container">

        <div class="row align-items-center">


            <div class="col-md-6">

                <h5>

                    <span class="brand-icon">

                        Q

                    </span>

                    QuizCore

                </h5>


                <p>

                    Student Online Quiz System

                </p>

            </div>


            <div class="col-md-6 text-md-end">

                <p>

                    HTML • CSS • JavaScript • Bootstrap • PHP • MySQL

                </p>


                <p class="mb-0">

                    © <?php echo date("Y"); ?> QuizCore

                </p>

            </div>


        </div>

    </div>

</footer>



<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


<script>

    /* =========================================================
       DARK / LIGHT MODE
    ========================================================= */

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

        } else {

            themeIcon.className =
                "bi bi-sun-fill";

            themeToggle.setAttribute(
                "title",
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
        localStorage.getItem("quizcore-theme")
        || "dark";


    setTheme(currentTheme);


    themeToggle.addEventListener(
        "click",
        function () {

            const activeTheme =
                document.documentElement
                    .getAttribute("data-theme");


            setTheme(
                activeTheme === "dark"
                    ? "light"
                    : "dark"
            );

        }
    );

</script>


</body>

</html>