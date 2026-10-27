<?php

require_once "../config/db.php";
require_once "../includes/auth.php";

requireStudent();


/*
|--------------------------------------------------------------------------
| Get Result ID
|--------------------------------------------------------------------------
*/

$resultId = isset($_GET["id"])
    ? intval($_GET["id"])
    : 0;


/*
|--------------------------------------------------------------------------
| Get Result
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT
        r.*,
        q.title,
        q.description
     FROM results r
     JOIN quizzes q
        ON r.quiz_id = q.id
     WHERE r.id = ?
       AND r.user_id = ?"
);

$stmt->bind_param(
    "ii",
    $resultId,
    $_SESSION["user_id"]
);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows !== 1) {

    header("Location: ../student/dashboard.php");
    exit;

}


$data = $result->fetch_assoc();


include "../includes/header.php";

?>


<style>

    /* =========================================================
       QUIZCORE RESULT PAGE
       UI ONLY — RESULT LOGIC UNCHANGED
    ========================================================= */

    :root {

        --result-bg: #070b14;

        --result-surface: #101827;

        --result-surface-2: #141e30;

        --result-border:
            rgba(255,255,255,0.08);

        --result-text: #f8fafc;

        --result-muted: #94a3b8;

        --result-primary: #6366f1;

        --result-primary-2: #8b5cf6;

        --result-cyan: #22d3ee;

        --result-success: #22c55e;

        --result-danger: #ef4444;

    }


    html[data-theme="light"] {

        --result-bg: #f5f7fb;

        --result-surface: #ffffff;

        --result-surface-2: #f8fafc;

        --result-border:
            rgba(15,23,42,0.09);

        --result-text: #0f172a;

        --result-muted: #64748b;

        --result-primary: #4f46e5;

        --result-primary-2: #7c3aed;

        --result-cyan: #0891b2;

        --result-success: #16a34a;

        --result-danger: #dc2626;

    }


    body {

        background:
            radial-gradient(
                circle at 15% 15%,
                rgba(99,102,241,0.11),
                transparent 32%
            ),
            radial-gradient(
                circle at 85% 80%,
                rgba(34,211,238,0.07),
                transparent 30%
            ),
            var(--result-bg);

        color:
            var(--result-text);

    }


    /* =========================================================
       RESULT PAGE
    ========================================================= */

    .result-page {

        min-height:
            calc(100vh - 150px);

        padding:
            55px 0 75px;

    }


    /* =========================================================
       MAIN RESULT CARD
    ========================================================= */

    .result-card {

        position:
            relative;

        overflow:
            hidden;

        max-width:
            950px;

        margin:
            0 auto;

        padding:
            45px 40px;

        background:
            linear-gradient(
                145deg,
                var(--result-surface),
                var(--result-surface-2)
            );

        border:
            1px solid
            var(--result-border);

        border-radius:
            28px;

        box-shadow:
            0 30px 80px
            rgba(0,0,0,0.20);

    }


    .result-card::before {

        content:
            "";

        position:
            absolute;

        width:
            350px;

        height:
            350px;

        border-radius:
            50%;

        background:
            radial-gradient(
                circle,
                rgba(99,102,241,0.14),
                transparent 70%
            );

        top:
            -200px;

        right:
            -150px;

        pointer-events:
            none;

    }


    .result-card::after {

        content:
            "";

        position:
            absolute;

        width:
            280px;

        height:
            280px;

        border-radius:
            50%;

        background:
            radial-gradient(
                circle,
                rgba(34,211,238,0.07),
                transparent 70%
            );

        bottom:
            -180px;

        left:
            -130px;

        pointer-events:
            none;

    }


    .result-content {

        position:
            relative;

        z-index:
            2;

    }


    /* =========================================================
       SUCCESS ICON
    ========================================================= */

    .completion-icon {

        width:
            68px;

        height:
            68px;

        display:
            flex;

        align-items:
            center;

        justify-content:
            center;

        margin:
            0 auto 18px;

        border-radius:
            20px;

        background:
            rgba(34,197,94,0.10);

        border:
            1px solid
            rgba(34,197,94,0.18);

        color:
            #4ade80;

        font-size:
            1.6rem;

        box-shadow:
            0 12px 30px
            rgba(34,197,94,0.08);

    }


    .result-label {

        color:
            var(--result-cyan);

        font-size:
            .76rem;

        font-weight:
            800;

        letter-spacing:
            1.4px;

        margin-bottom:
            10px;

    }


    .result-title {

        color:
            var(--result-text);

        font-size:
            clamp(1.8rem, 4vw, 2.8rem);

        font-weight:
            800;

        letter-spacing:
            -1.5px;

        margin-bottom:
            10px;

    }


    .result-subtitle {

        color:
            var(--result-muted);

        line-height:
            1.7;

        margin:
            0 auto;

        max-width:
            600px;

    }


    /* =========================================================
       RESULT CIRCLE
    ========================================================= */

    .result-circle-wrapper {

        display:
            flex;

        justify-content:
            center;

        margin:
            38px 0 35px;

    }


    .result-circle {

        width:
            190px;

        height:
            190px;

        display:
            flex;

        align-items:
            center;

        justify-content:
            center;

        border-radius:
            50%;

        position:
            relative;

        background:
            conic-gradient(
                var(--result-primary),
                var(--result-primary-2),
                var(--result-cyan),
                var(--result-primary)
            );

        box-shadow:
            0 20px 50px
            rgba(99,102,241,0.18);

    }


    .result-circle::before {

        content:
            "";

        position:
            absolute;

        inset:
            10px;

        border-radius:
            50%;

        background:
            var(--result-surface);

    }


    .result-percentage {

        position:
            relative;

        z-index:
            2;

        color:
            var(--result-text);

        font-size:
            2.45rem;

        font-weight:
            850;

        letter-spacing:
            -1.5px;

    }


    /* =========================================================
       STAT CARDS
    ========================================================= */

    .result-stat {

        height:
            100%;

        padding:
            24px 20px;

        border:
            1px solid
            var(--result-border);

        border-radius:
            18px;

        background:
            rgba(255,255,255,0.025);

        transition:
            transform .25s ease,
            border-color .25s ease;

    }


    html[data-theme="light"]
    .result-stat {

        background:
            #f8fafc;

    }


    .result-stat:hover {

        transform:
            translateY(-4px);

        border-color:
            rgba(99,102,241,0.25);

    }


    .stat-icon {

        width:
            42px;

        height:
            42px;

        display:
            flex;

        align-items:
            center;

        justify-content:
            center;

        margin:
            0 auto 12px;

        border-radius:
            12px;

        background:
            rgba(99,102,241,0.10);

        color:
            #a5b4fc;

    }


    .stat-label {

        color:
            var(--result-muted);

        font-size:
            .78rem;

        font-weight:
            650;

        margin-bottom:
            7px;

    }


    .stat-number {

        color:
            var(--result-text);

        font-size:
            1.65rem;

        font-weight:
            800;

        letter-spacing:
            -.5px;

    }


    .pass-status {

        color:
            #4ade80;

        font-size:
            1.65rem;

        font-weight:
            850;

        letter-spacing:
            .5px;

    }


    .fail-status {

        color:
            #f87171;

        font-size:
            1.65rem;

        font-weight:
            850;

        letter-spacing:
            .5px;

    }


    /* =========================================================
       ATTEMPT INFO
    ========================================================= */

    .attempt-info {

        display:
            inline-flex;

        align-items:
            center;

        gap:
            8px;

        margin-top:
            28px;

        padding:
            10px 15px;

        border:
            1px solid
            var(--result-border);

        border-radius:
            11px;

        color:
            var(--result-muted);

        font-size:
            .8rem;

        background:
            rgba(255,255,255,0.025);

    }


    .attempt-info i {

        color:
            var(--result-primary);

    }


    /* =========================================================
       ACTION BUTTONS
    ========================================================= */

    .result-actions {

        display:
            flex;

        justify-content:
            center;

        flex-wrap:
            wrap;

        gap:
            12px;

        margin-top:
            30px;

    }


    .btn-result-primary {

        min-height:
            48px;

        padding:
            11px 20px;

        display:
            inline-flex;

        align-items:
            center;

        justify-content:
            center;

        border:
            none;

        border-radius:
            12px;

        background:
            linear-gradient(
                135deg,
                var(--result-primary),
                var(--result-primary-2)
            );

        color:
            white;

        font-weight:
            750;

        box-shadow:
            0 10px 25px
            rgba(99,102,241,0.22);

        transition:
            .25s ease;

    }


    .btn-result-primary:hover {

        color:
            white;

        transform:
            translateY(-2px);

        box-shadow:
            0 15px 32px
            rgba(99,102,241,0.32);

    }


    .btn-result-outline {

        min-height:
            48px;

        padding:
            11px 20px;

        display:
            inline-flex;

        align-items:
            center;

        justify-content:
            center;

        border:
            1px solid
            var(--result-border);

        border-radius:
            12px;

        background:
            var(--result-surface);

        color:
            var(--result-text);

        font-weight:
            650;

        transition:
            .25s ease;

    }


    .btn-result-outline:hover {

        color:
            var(--result-text);

        transform:
            translateY(-2px);

        border-color:
            rgba(99,102,241,0.35);

        background:
            var(--result-surface-2);

    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 767px) {

        .result-page {

            padding:
                35px 0 55px;

        }


        .result-card {

            padding:
                35px 20px;

            border-radius:
                22px;

        }


        .result-circle {

            width:
                165px;

            height:
                165px;

        }


        .result-percentage {

            font-size:
                2.1rem;

        }


        .result-stat {

            padding:
                20px 15px;

        }

    }


    @media (max-width: 480px) {

        .result-actions {

            flex-direction:
                column;

        }


        .btn-result-primary,
        .btn-result-outline {

            width:
                100%;

        }

    }

</style>


<script>

    /* =========================================================
       APPLY SAVED QUIZCORE THEME
    ========================================================= */

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


<div class="result-page">

    <div class="container">


        <div class="result-card">

            <div class="result-content">


                <!-- =================================================
                     COMPLETION HEADER
                ================================================= -->

                <div class="text-center">


                    <div class="completion-icon">

                        <i class="bi bi-check2"></i>

                    </div>


                    <div class="result-label">

                        QUIZ COMPLETED

                    </div>


                    <h1 class="result-title">

                        <?php

                        echo htmlspecialchars(
                            $data["title"]
                        );

                        ?>

                    </h1>


                    <p class="result-subtitle">

                        Your result has been recorded successfully.
                        Here's your performance summary.

                    </p>

                </div>


                <!-- =================================================
                     MAIN SCORE
                ================================================= -->

                <div class="result-circle-wrapper">

                    <div class="result-circle">

                        <div class="result-percentage">

                            <?php

                            echo number_format(
                                $data["percentage"],
                                1
                            );

                            ?>%

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     STATISTICS
                ================================================= -->

                <div class="row justify-content-center g-3">


                    <!-- SCORE -->

                    <div class="col-md-4">

                        <div class="result-stat text-center">


                            <div class="stat-icon">

                                <i class="bi bi-award-fill"></i>

                            </div>


                            <div class="stat-label">

                                SCORE

                            </div>


                            <div class="stat-number">

                                <?php

                                echo $data["score"];

                                ?>

                                /

                                <?php

                                echo $data["total_questions"];

                                ?>

                            </div>


                        </div>

                    </div>


                    <!-- PERCENTAGE -->

                    <div class="col-md-4">

                        <div class="result-stat text-center">


                            <div class="stat-icon">

                                <i class="bi bi-graph-up-arrow"></i>

                            </div>


                            <div class="stat-label">

                                PERCENTAGE

                            </div>


                            <div class="stat-number">

                                <?php

                                echo number_format(
                                    $data["percentage"],
                                    1
                                );

                                ?>%

                            </div>


                        </div>

                    </div>


                    <!-- STATUS -->

                    <div class="col-md-4">

                        <div class="result-stat text-center">


                            <div class="stat-icon">

                                <?php if ($data["percentage"] >= 50): ?>

                                    <i class="bi bi-check-circle-fill"></i>

                                <?php else: ?>

                                    <i class="bi bi-x-circle-fill"></i>

                                <?php endif; ?>

                            </div>


                            <div class="stat-label">

                                STATUS

                            </div>


                            <?php if ($data["percentage"] >= 50): ?>

                                <div class="pass-status">

                                    PASS

                                </div>

                            <?php else: ?>

                                <div class="fail-status">

                                    FAIL

                                </div>

                            <?php endif; ?>


                        </div>

                    </div>


                </div>


                <!-- =================================================
                     ATTEMPT TIME
                ================================================= -->

                <div class="text-center">

                    <div class="attempt-info">

                        <i class="bi bi-calendar3"></i>

                        <span>

                            Attempted on:

                            <?php

                            echo date(
                                "d M Y, h:i A",
                                strtotime(
                                    $data["attempted_at"]
                                )
                            );

                            ?>

                        </span>

                    </div>

                </div>


                <!-- =================================================
                     ACTIONS
                ================================================= -->

                <div class="result-actions">


                    <a
                        href="../student/dashboard.php"
                        class="btn-result-primary">

                        <i
                            class="bi bi-grid-1x2-fill me-2">
                        </i>

                        Back to Dashboard

                    </a>


                    <a
                        href="../index.php"
                        class="btn-result-outline">

                        <i
                            class="bi bi-house me-2">
                        </i>

                        Home

                    </a>


                </div>


            </div>

        </div>

    </div>

</div>


<?php include "../includes/footer.php"; ?>