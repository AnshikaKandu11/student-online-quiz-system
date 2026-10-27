<?php

require_once "../config/db.php";
require_once "../includes/auth.php";

requireAdmin();

$students = $conn->query(
    "SELECT COUNT(*) AS total
     FROM users
     WHERE role = 'student'"
)->fetch_assoc()["total"];

$quizzes = $conn->query(
    "SELECT COUNT(*) AS total
     FROM quizzes"
)->fetch_assoc()["total"];

$questions = $conn->query(
    "SELECT COUNT(*) AS total
     FROM questions"
)->fetch_assoc()["total"];

$attempts = $conn->query(
    "SELECT COUNT(*) AS total
     FROM results"
)->fetch_assoc()["total"];

include "../includes/header.php";

?>

<div class="container">

    <!-- =====================================================
         DASHBOARD HEADER
         ===================================================== -->

    <div class="mb-5">

        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">

            <div>

                <div class="d-flex align-items-center gap-2 mb-3">

                    <span class="badge-tech">

                        <i class="bi bi-shield-check"></i>

                        ADMINISTRATION PANEL

                    </span>

                </div>

                <h1 class="fw-bold mb-2">

                    Admin Dashboard

                </h1>

                <p class="muted mb-0">

                    Manage quizzes, questions and student activity
                    from one central dashboard.

                </p>

            </div>


            <div class="badge-tech">

                <i class="bi bi-activity"></i>

                System Overview

            </div>

        </div>

    </div>


    <!-- =====================================================
         STATISTICS
         ===================================================== -->

    <div class="row g-4 mb-5">


        <!-- Students -->

        <div class="col-md-6 col-lg-3">

            <div class="stat-card">

                <div class="d-flex justify-content-between align-items-start">

                    <div>

                        <p class="muted mb-2">

                            Students

                        </p>

                        <div class="stat-number">

                            <?php echo $students; ?>

                        </div>

                    </div>


                    <div class="feature-icon mb-0"
                         style="width:48px;height:48px;font-size:1.3rem;border-radius:13px;">

                        <i class="bi bi-people-fill"></i>

                    </div>

                </div>


                <div class="mt-3 small muted">

                    <i class="bi bi-person-check me-1"></i>

                    Registered accounts

                </div>

            </div>

        </div>


        <!-- Quizzes -->

        <div class="col-md-6 col-lg-3">

            <div class="stat-card">

                <div class="d-flex justify-content-between align-items-start">

                    <div>

                        <p class="muted mb-2">

                            Quizzes

                        </p>

                        <div class="stat-number">

                            <?php echo $quizzes; ?>

                        </div>

                    </div>


                    <div class="feature-icon mb-0"
                         style="width:48px;height:48px;font-size:1.3rem;border-radius:13px;">

                        <i class="bi bi-collection-play-fill"></i>

                    </div>

                </div>


                <div class="mt-3 small muted">

                    <i class="bi bi-grid-3x3-gap me-1"></i>

                    Available quiz sets

                </div>

            </div>

        </div>


        <!-- Questions -->

        <div class="col-md-6 col-lg-3">

            <div class="stat-card">

                <div class="d-flex justify-content-between align-items-start">

                    <div>

                        <p class="muted mb-2">

                            Questions

                        </p>

                        <div class="stat-number">

                            <?php echo $questions; ?>

                        </div>

                    </div>


                    <div class="feature-icon mb-0"
                         style="width:48px;height:48px;font-size:1.3rem;border-radius:13px;">

                        <i class="bi bi-question-circle-fill"></i>

                    </div>

                </div>


                <div class="mt-3 small muted">

                    <i class="bi bi-list-check me-1"></i>

                    Questions in bank

                </div>

            </div>

        </div>


        <!-- Attempts -->

        <div class="col-md-6 col-lg-3">

            <div class="stat-card">

                <div class="d-flex justify-content-between align-items-start">

                    <div>

                        <p class="muted mb-2">

                            Attempts

                        </p>

                        <div class="stat-number">

                            <?php echo $attempts; ?>

                        </div>

                    </div>


                    <div class="feature-icon mb-0"
                         style="width:48px;height:48px;font-size:1.3rem;border-radius:13px;">

                        <i class="bi bi-bar-chart-fill"></i>

                    </div>

                </div>


                <div class="mt-3 small muted">

                    <i class="bi bi-graph-up-arrow me-1"></i>

                    Total quiz attempts

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         MANAGEMENT SECTION
         ===================================================== -->

    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                Management

            </h2>

            <p class="muted mb-0">

                Quickly access the main administration tools.

            </p>

        </div>


        <span class="badge-tech">

            <i class="bi bi-grid-fill"></i>

            3 Management Modules

        </span>

    </div>


    <div class="row g-4">


        <!-- =================================================
             QUIZ MANAGEMENT
             ================================================= -->

        <div class="col-md-4">

            <div class="tech-card">

                <div class="feature-icon">

                    <i class="bi bi-collection-play-fill"></i>

                </div>

                <h4 class="fw-bold mb-2">

                    Quiz Management

                </h4>

                <p class="muted">

                    Create quizzes, configure their settings,
                    set duration and activate or deactivate them.

                </p>


                <div class="tech-divider"></div>


                <div class="d-flex align-items-center justify-content-between gap-3">

                    <span class="small muted">

                        <i class="bi bi-database me-1"></i>

                        <?php echo $quizzes; ?> quizzes

                    </span>

                    <a
                        href="quizzes.php"
                        class="btn btn-tech">

                        <i class="bi bi-arrow-right"></i>

                        Manage

                    </a>

                </div>

            </div>

        </div>


        <!-- =================================================
             QUESTION BANK
             ================================================= -->

        <div class="col-md-4">

            <div class="tech-card">

                <div class="feature-icon">

                    <i class="bi bi-question-square-fill"></i>

                </div>

                <h4 class="fw-bold mb-2">

                    Question Bank

                </h4>

                <p class="muted">

                    Add, review and remove multiple-choice
                    questions for each quiz.

                </p>


                <div class="tech-divider"></div>


                <div class="d-flex align-items-center justify-content-between gap-3">

                    <span class="small muted">

                        <i class="bi bi-list-check me-1"></i>

                        <?php echo $questions; ?> questions

                    </span>

                    <a
                        href="questions.php"
                        class="btn btn-tech">

                        <i class="bi bi-arrow-right"></i>

                        Manage

                    </a>

                </div>

            </div>

        </div>


        <!-- =================================================
             STUDENT RECORDS
             ================================================= -->

        <div class="col-md-4">

            <div class="tech-card">

                <div class="feature-icon">

                    <i class="bi bi-people-fill"></i>

                </div>

                <h4 class="fw-bold mb-2">

                    Student Records

                </h4>

                <p class="muted">

                    View registered students and monitor
                    their quiz participation and attempts.

                </p>


                <div class="tech-divider"></div>


                <div class="d-flex align-items-center justify-content-between gap-3">

                    <span class="small muted">

                        <i class="bi bi-person-check me-1"></i>

                        <?php echo $students; ?> students

                    </span>

                    <a
                        href="students.php"
                        class="btn btn-tech">

                        <i class="bi bi-arrow-right"></i>

                        View

                    </a>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         SYSTEM OVERVIEW
         ===================================================== -->

    <div class="tech-card mt-5">

        <div class="row align-items-center g-4">

            <div class="col-lg-8">

                <div class="d-flex align-items-start gap-3">

                    <div class="feature-icon mb-0">

                        <i class="bi bi-speedometer2"></i>

                    </div>

                    <div>

                        <h4 class="fw-bold mb-2">

                            QuizCore Administration

                        </h4>

                        <p class="muted mb-0">

                            Use the management modules above to maintain
                            quizzes, build your question bank and monitor
                            student participation.

                        </p>

                    </div>

                </div>

            </div>


            <div class="col-lg-4">

                <div class="d-flex flex-wrap justify-content-lg-end gap-2">

                    <span class="badge-tech">

                        <i class="bi bi-people"></i>

                        <?php echo $students; ?> Students

                    </span>

                    <span class="badge-tech">

                        <i class="bi bi-collection-play"></i>

                        <?php echo $quizzes; ?> Quizzes

                    </span>

                    <span class="badge-tech">

                        <i class="bi bi-pencil-square"></i>

                        <?php echo $attempts; ?> Attempts

                    </span>

                </div>

            </div>

        </div>

    </div>

</div>


<?php include "../includes/footer.php"; ?>