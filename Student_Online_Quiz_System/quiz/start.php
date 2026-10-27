<?php

require_once "../config/db.php";
require_once "../includes/auth.php";

requireStudent();


/*
|--------------------------------------------------------------------------
| Get Quiz ID
|--------------------------------------------------------------------------
*/

$quizId = isset($_GET["id"])
    ? intval($_GET["id"])
    : 0;


if ($quizId <= 0) {

    header("Location: ../student/dashboard.php");
    exit;

}


/*
|--------------------------------------------------------------------------
| Get Quiz
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT *
     FROM quizzes
     WHERE id = ? AND status = 'active'"
);

$stmt->bind_param("i", $quizId);
$stmt->execute();

$quizResult = $stmt->get_result();


if ($quizResult->num_rows !== 1) {

    header("Location: ../student/dashboard.php");
    exit;

}


$quiz = $quizResult->fetch_assoc();


/*
|--------------------------------------------------------------------------
| Get Questions
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT *
     FROM questions
     WHERE quiz_id = ?
     ORDER BY id"
);

$stmt->bind_param("i", $quizId);
$stmt->execute();

$questions = $stmt->get_result();


include "../includes/header.php";

?>


<style>

    /* =========================================================
       QUIZCORE QUIZ PAGE
       UI ONLY — QUIZ LOGIC UNCHANGED
    ========================================================= */

    :root {

        --quiz-bg: #070b14;

        --quiz-surface: #101827;

        --quiz-surface-2: #141e30;

        --quiz-border:
            rgba(255,255,255,0.08);

        --quiz-text: #f8fafc;

        --quiz-muted: #94a3b8;

        --quiz-primary: #6366f1;

        --quiz-primary-2: #8b5cf6;

        --quiz-cyan: #22d3ee;

        --quiz-success: #22c55e;

    }


    html[data-theme="light"] {

        --quiz-bg: #f5f7fb;

        --quiz-surface: #ffffff;

        --quiz-surface-2: #f8fafc;

        --quiz-border:
            rgba(15,23,42,0.09);

        --quiz-text: #0f172a;

        --quiz-muted: #64748b;

        --quiz-primary: #4f46e5;

        --quiz-primary-2: #7c3aed;

        --quiz-cyan: #0891b2;

        --quiz-success: #16a34a;

    }


    body {

        background:
            radial-gradient(
                circle at 10% 10%,
                rgba(99,102,241,0.08),
                transparent 30%
            ),
            radial-gradient(
                circle at 90% 80%,
                rgba(34,211,238,0.06),
                transparent 30%
            ),
            var(--quiz-bg);

        color:
            var(--quiz-text);

    }


    /* =========================================================
       MAIN QUIZ WRAPPER
    ========================================================= */

    .quiz-page {

        padding:
            45px 0 70px;

    }


    /* =========================================================
       QUIZ HEADER
    ========================================================= */

    .quiz-header-card {

        padding:
            28px 30px;

        margin-bottom:
            22px;

        background:
            linear-gradient(
                145deg,
                var(--quiz-surface),
                var(--quiz-surface-2)
            );

        border:
            1px solid
            var(--quiz-border);

        border-radius:
            22px;

        box-shadow:
            0 18px 50px
            rgba(0,0,0,0.14);

    }


    .quiz-label {

        display:
            inline-flex;

        align-items:
            center;

        gap:
            7px;

        color:
            var(--quiz-cyan);

        font-size:
            .75rem;

        font-weight:
            800;

        letter-spacing:
            1.2px;

        margin-bottom:
            9px;

    }


    .quiz-label-dot {

        width:
            7px;

        height:
            7px;

        border-radius:
            50%;

        background:
            var(--quiz-cyan);

        box-shadow:
            0 0 0 5px
            rgba(34,211,238,0.08);

    }


    .quiz-title {

        color:
            var(--quiz-text);

        font-size:
            clamp(1.7rem, 3vw, 2.3rem);

        font-weight:
            800;

        letter-spacing:
            -1px;

        margin-bottom:
            8px;

    }


    .quiz-description {

        color:
            var(--quiz-muted);

        line-height:
            1.7;

        margin-bottom:
            0;

    }


    .quiz-meta {

        display:
            flex;

        flex-wrap:
            wrap;

        gap:
            10px;

        margin-top:
            20px;

    }


    .quiz-meta-item {

        display:
            inline-flex;

        align-items:
            center;

        gap:
            7px;

        padding:
            8px 11px;

        border:
            1px solid
            var(--quiz-border);

        border-radius:
            10px;

        background:
            rgba(255,255,255,0.025);

        color:
            var(--quiz-muted);

        font-size:
            .78rem;

        font-weight:
            600;

    }


    .quiz-meta-item i {

        color:
            var(--quiz-primary);

    }


    /* =========================================================
       QUESTION CARD
    ========================================================= */

    .question-card {

        padding:
            28px;

        margin-bottom:
            18px;

        background:
            linear-gradient(
                145deg,
                var(--quiz-surface),
                var(--quiz-surface-2)
            );

        border:
            1px solid
            var(--quiz-border);

        border-radius:
            20px;

        box-shadow:
            0 14px 40px
            rgba(0,0,0,0.10);

        transition:
            border-color .25s ease,
            transform .25s ease,
            box-shadow .25s ease;

    }


    .question-card:hover {

        border-color:
            rgba(99,102,241,0.20);

    }


    .question-top {

        display:
            flex;

        align-items:
            flex-start;

        justify-content:
            space-between;

        gap:
            15px;

        margin-bottom:
            22px;

    }


    .question-number {

        flex-shrink:
            0;

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

        border-radius:
            12px;

        background:
            linear-gradient(
                135deg,
                var(--quiz-primary),
                var(--quiz-primary-2)
            );

        color:
            white;

        font-size:
            .82rem;

        font-weight:
            800;

        box-shadow:
            0 8px 22px
            rgba(99,102,241,0.20);

    }


    .question-text {

        flex:
            1;

        color:
            var(--quiz-text);

        font-size:
            1.03rem;

        font-weight:
            700;

        line-height:
            1.6;

        padding-top:
            8px;

    }


    /* =========================================================
       OPTIONS
    ========================================================= */

    .quiz-option {

        position:
            relative;

        display:
            flex;

        align-items:
            center;

        gap:
            13px;

        width:
            100%;

        min-height:
            58px;

        padding:
            13px 16px;

        margin-bottom:
            10px;

        border:
            1px solid
            var(--quiz-border);

        border-radius:
            13px;

        background:
            rgba(255,255,255,0.025);

        color:
            var(--quiz-muted);

        cursor:
            pointer;

        transition:
            background .2s ease,
            border-color .2s ease,
            transform .2s ease,
            color .2s ease;

    }


    html[data-theme="light"]
    .quiz-option {

        background:
            #f8fafc;

    }


    .quiz-option:last-child {

        margin-bottom:
            0;

    }


    .quiz-option:hover {

        color:
            var(--quiz-text);

        border-color:
            rgba(99,102,241,0.35);

        background:
            rgba(99,102,241,0.06);

        transform:
            translateX(3px);

    }


    .quiz-option input {

        position:
            absolute;

        opacity:
            0;

        pointer-events:
            none;

    }


    .option-marker {

        flex-shrink:
            0;

        width:
            36px;

        height:
            36px;

        display:
            flex;

        align-items:
            center;

        justify-content:
            center;

        border:
            1px solid
            var(--quiz-border);

        border-radius:
            10px;

        background:
            rgba(255,255,255,0.025);

        color:
            var(--quiz-muted);

        font-size:
            .78rem;

        font-weight:
            800;

        transition:
            .2s ease;

    }


    .option-text {

        flex:
            1;

        font-size:
            .91rem;

        line-height:
            1.5;

    }


    .quiz-option.selected {

        border-color:
            rgba(99,102,241,0.55);

        background:
            rgba(99,102,241,0.10);

        color:
            var(--quiz-text);

        box-shadow:
            0 8px 25px
            rgba(99,102,241,0.08);

    }


    .quiz-option.selected .option-marker {

        border-color:
            var(--quiz-primary);

        background:
            var(--quiz-primary);

        color:
            white;

    }


    /* =========================================================
       TIMER PANEL
    ========================================================= */

    .timer-sticky {

        position:
            sticky;

        top:
            92px;

    }


    .timer-box {

        padding:
            26px 22px;

        text-align:
            center;

        background:
            linear-gradient(
                145deg,
                var(--quiz-surface),
                var(--quiz-surface-2)
            );

        border:
            1px solid
            var(--quiz-border);

        border-radius:
            22px;

        box-shadow:
            0 18px 50px
            rgba(0,0,0,0.16);

    }


    .timer-label {

        color:
            var(--quiz-muted);

        font-size:
            .7rem;

        font-weight:
            800;

        letter-spacing:
            1.2px;

    }


    .timer-circle {

        width:
            145px;

        height:
            145px;

        display:
            flex;

        align-items:
            center;

        justify-content:
            center;

        margin:
            20px auto;

        border-radius:
            50%;

        background:
            conic-gradient(
                var(--quiz-primary) 0deg,
                var(--quiz-primary-2) 250deg,
                rgba(148,163,184,0.10) 250deg,
                rgba(148,163,184,0.10) 360deg
            );

        position:
            relative;

    }


    .timer-circle::before {

        content:
            "";

        position:
            absolute;

        inset:
            8px;

        border-radius:
            50%;

        background:
            var(--quiz-surface);

    }


    .timer {

        position:
            relative;

        z-index:
            2;

        color:
            var(--quiz-text);

        font-size:
            1.9rem;

        font-weight:
            800;

        letter-spacing:
            -1px;

    }


    .timer-warning {

        color:
            #f87171 !important;

    }


    .timer-info {

        color:
            var(--quiz-muted);

        font-size:
            .76rem;

        line-height:
            1.6;

    }


    /* =========================================================
       PROGRESS
    ========================================================= */

    .progress-section {

        margin-top:
            22px;

            padding-top:
            20px;

        border-top:
            1px solid
            var(--quiz-border);

    }


    .progress-label {

        display:
            flex;

        align-items:
            center;

        justify-content:
            space-between;

        margin-bottom:
            9px;

        color:
            var(--quiz-muted);

        font-size:
            .74rem;

        font-weight:
            700;

    }


    .quiz-progress {

        height:
            7px;

        overflow:
            hidden;

        border-radius:
            999px;

        background:
            rgba(148,163,184,0.10);

    }


    .quiz-progress-bar {

        height:
            100%;

        width:
            0%;

        border-radius:
            inherit;

        background:
            linear-gradient(
                90deg,
                var(--quiz-primary),
                var(--quiz-cyan)
            );

        transition:
            width .3s ease;

    }


    /* =========================================================
       SUBMIT AREA
    ========================================================= */

    .submit-area {

        padding:
            20px 0 5px;

    }


    .submit-button {

        min-height:
            54px;

        padding:
            12px 24px;

        border:
            none;

        border-radius:
            13px;

        background:
            linear-gradient(
                135deg,
                var(--quiz-primary),
                var(--quiz-primary-2)
            );

        color:
            white;

        font-weight:
            750;

        box-shadow:
            0 12px 30px
            rgba(99,102,241,0.24);

        transition:
            .25s ease;

    }


    .submit-button:hover {

        color:
            white;

        transform:
            translateY(-2px);

        box-shadow:
            0 17px 38px
            rgba(99,102,241,0.34);

    }


    /* =========================================================
       EMPTY QUIZ
    ========================================================= */

    .empty-quiz {

        padding:
            35px;

        border-radius:
            18px;

        border:
            1px solid
            rgba(245,158,11,0.20);

        background:
            rgba(245,158,11,0.07);

        color:
            #fbbf24;

    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 991px) {

        .quiz-page {

            padding:
                30px 0 55px;

        }


        .timer-sticky {

            position:
                static;

            margin-bottom:
                20px;

        }


        .timer-box {

            display:
                grid;

            grid-template-columns:
                auto 1fr;

            align-items:
                center;

            text-align:
                left;

            gap:
                15px;

        }


        .timer-circle {

            grid-row:
                span 2;

            margin:
                0;

        }

    }


    @media (max-width: 576px) {

        .quiz-header-card,
        .question-card {

            padding:
                21px;

            border-radius:
                17px;

        }


        .question-top {

            gap:
                10px;

        }


        .question-number {

            width:
                37px;

            height:
                37px;

        }


        .question-text {

            font-size:
                .94rem;

        }


        .quiz-option {

            min-height:
                55px;

            padding:
                10px 12px;

        }


        .option-marker {

            width:
                32px;

            height:
                32px;

        }


        .timer-box {

            display:
                block;

            text-align:
                center;

        }


        .timer-circle {

            margin:
                18px auto;

        }

    }

</style>


<script>

    /* =========================================================
       APPLY SAVED THEME
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


<div class="quiz-page">

    <div class="container">

        <div class="row g-4">


            <!-- =================================================
                 MAIN QUIZ
            ================================================= -->

            <div class="col-lg-9">


                <!-- QUIZ HEADER -->

                <div class="quiz-header-card">

                    <div class="quiz-label">

                        <span class="quiz-label-dot"></span>

                        ONLINE ASSESSMENT

                    </div>


                    <h1 class="quiz-title">

                        <?php
                        echo htmlspecialchars(
                            $quiz["title"]
                        );
                        ?>

                    </h1>


                    <p class="quiz-description">

                        <?php

                        echo htmlspecialchars(
                            $quiz["description"]
                        );

                        ?>

                    </p>


                    <div class="quiz-meta">

                        <span class="quiz-meta-item">

                            <i class="bi bi-question-circle"></i>

                            <span id="questionCount">

                                <?php echo $questions->num_rows; ?>

                                Questions

                            </span>

                        </span>


                        <span class="quiz-meta-item">

                            <i class="bi bi-clock"></i>

                            <?php echo $quiz["duration"]; ?>

                            Minutes

                        </span>


                        <span class="quiz-meta-item">

                            <i class="bi bi-person-check"></i>

                            Student Attempt

                        </span>

                    </div>

                </div>


                <!-- =================================================
                     QUESTIONS
                ================================================= -->

                <?php if ($questions->num_rows > 0): ?>


                    <form
                        id="quizForm"
                        method="POST"
                        action="submit.php">


                        <input
                            type="hidden"
                            name="quiz_id"
                            value="<?php echo $quizId; ?>">


                        <?php

                        $number = 1;

                        while (
                            $question =
                            $questions->fetch_assoc()
                        ):

                        ?>


                            <div
                                class="question-card"
                                data-question="<?php echo $number; ?>">


                                <div class="question-top">


                                    <div class="question-number">

                                        Q<?php echo $number; ?>

                                    </div>


                                    <div class="question-text">

                                        <?php

                                        echo htmlspecialchars(
                                            $question["question"]
                                        );

                                        ?>

                                    </div>


                                </div>


                                <!-- OPTION A -->

                                <label
                                    class="quiz-option">

                                    <input
                                        type="radio"
                                        name="answers[<?php echo $question["id"]; ?>]"
                                        value="A">


                                    <span
                                        class="option-marker">

                                        A

                                    </span>


                                    <span class="option-text">

                                        <?php

                                        echo htmlspecialchars(
                                            $question["option_a"]
                                        );

                                        ?>

                                    </span>

                                </label>


                                <!-- OPTION B -->

                                <label
                                    class="quiz-option">

                                    <input
                                        type="radio"
                                        name="answers[<?php echo $question["id"]; ?>]"
                                        value="B">


                                    <span
                                        class="option-marker">

                                        B

                                    </span>


                                    <span class="option-text">

                                        <?php

                                        echo htmlspecialchars(
                                            $question["option_b"]
                                        );

                                        ?>

                                    </span>

                                </label>


                                <!-- OPTION C -->

                                <label
                                    class="quiz-option">

                                    <input
                                        type="radio"
                                        name="answers[<?php echo $question["id"]; ?>]"
                                        value="C">


                                    <span
                                        class="option-marker">

                                        C

                                    </span>


                                    <span class="option-text">

                                        <?php

                                        echo htmlspecialchars(
                                            $question["option_c"]
                                        );

                                        ?>

                                    </span>

                                </label>


                                <!-- OPTION D -->

                                <label
                                    class="quiz-option">

                                    <input
                                        type="radio"
                                        name="answers[<?php echo $question["id"]; ?>]"
                                        value="D">


                                    <span
                                        class="option-marker">

                                        D

                                    </span>


                                    <span class="option-text">

                                        <?php

                                        echo htmlspecialchars(
                                            $question["option_d"]
                                        );

                                        ?>

                                    </span>

                                </label>


                            </div>


                            <?php

                            $number++;

                            ?>

                        <?php endwhile; ?>


                        <!-- SUBMIT -->

                        <div class="submit-area">


                            <button
                                type="submit"
                                class="submit-button">

                                <i
                                    class="bi bi-check2-circle me-2">
                                </i>

                                Submit Quiz

                            </button>


                        </div>


                    </form>


                <?php else: ?>


                    <div class="empty-quiz">

                        <i
                            class="bi bi-exclamation-triangle-fill me-2">
                        </i>

                        This quiz does not contain any questions yet.

                    </div>


                <?php endif; ?>


            </div>


            <!-- =================================================
                 TIMER
            ================================================= -->

            <div class="col-lg-3">


                <div class="timer-sticky">

                    <div class="timer-box">


                        <div>


                            <div class="timer-label">

                                TIME REMAINING

                            </div>


                            <div class="timer-circle">

                                <div
                                    class="timer"
                                    id="timer">

                                    <?php

                                    echo $quiz["duration"];

                                    ?>:00

                                </div>

                            </div>


                            <div class="timer-info">

                                <i
                                    class="bi bi-info-circle me-1">
                                </i>

                                Your quiz will be submitted
                                automatically when time runs out.

                            </div>

                        </div>


                        <!-- PROGRESS -->

                        <?php if ($questions->num_rows > 0): ?>

                            <div class="progress-section">


                                <div class="progress-label">

                                    <span>
                                        Progress
                                    </span>

                                    <span id="progressText">
                                        0%
                                    </span>

                                </div>


                                <div class="quiz-progress">

                                    <div
                                        class="quiz-progress-bar"
                                        id="progressBar">
                                    </div>

                                </div>


                                <div
                                    class="muted mt-2 small"
                                    id="answeredText">

                                    0 of
                                    <?php echo $questions->num_rows; ?>
                                    answered

                                </div>

                            </div>

                        <?php endif; ?>


                    </div>

                </div>

            </div>


        </div>

    </div>

</div>


<script>

    /* =========================================================
       TIMER
       Original timer logic preserved
    ========================================================= */

    let totalSeconds =
        <?php echo $quiz["duration"]; ?> * 60;


    const timerElement =
        document.getElementById("timer");


    const quizForm =
        document.getElementById("quizForm");


    function updateTimer() {

        if (!timerElement) {
            return;
        }


        const minutes =
            Math.floor(
                totalSeconds / 60
            );


        const seconds =
            totalSeconds % 60;


        timerElement.textContent =
            String(minutes).padStart(2, "0")
            + ":"
            + String(seconds).padStart(2, "0");


        /*
        |--------------------------------------------------------------------------
        | Visual warning when less than one minute remains
        |--------------------------------------------------------------------------
        */

        if (totalSeconds <= 60) {

            timerElement.classList.add(
                "timer-warning"
            );

        }


        if (totalSeconds <= 0) {

            clearInterval(timerInterval);


            alert(
                "Time is up! Your quiz will be submitted."
            );


            if (quizForm) {

                quizForm.submit();

            }


            return;

        }


        totalSeconds--;

    }


    updateTimer();


    const timerInterval =
        setInterval(
            updateTimer,
            1000
        );


    /* =========================================================
       ANSWER SELECTION
    ========================================================= */

    const optionLabels =
        document.querySelectorAll(
            ".quiz-option"
        );


    optionLabels.forEach(
        function (label) {

            const input =
                label.querySelector(
                    "input"
                );


            input.addEventListener(
                "change",
                function () {

                    const groupName =
                        input.name;


                    document
                        .querySelectorAll(
                            'input[name="' +
                            groupName +
                            '"]'
                        )
                        .forEach(
                            function (radio) {

                                const parent =
                                    radio.closest(
                                        ".quiz-option"
                                    );


                                if (parent) {

                                    parent.classList
                                        .remove(
                                            "selected"
                                        );

                                }

                            }
                        );


                    label.classList.add(
                        "selected"
                    );


                    updateProgress();

                }
            );

        }
    );


    /* =========================================================
       PROGRESS
    ========================================================= */

    function updateProgress() {

        const allQuestions =
            document.querySelectorAll(
                ".question-card"
            );


        const answered =
            document.querySelectorAll(
                ".quiz-option input:checked"
            );


        const total =
            allQuestions.length;


        const count =
            answered.length;


        const percentage =
            total > 0
                ? Math.round(
                    (count / total) * 100
                )
                : 0;


        const progressBar =
            document.getElementById(
                "progressBar"
            );


        const progressText =
            document.getElementById(
                "progressText"
            );


        const answeredText =
            document.getElementById(
                "answeredText"
            );


        if (progressBar) {

            progressBar.style.width =
                percentage + "%";

        }


        if (progressText) {

            progressText.textContent =
                percentage + "%";

        }


        if (answeredText) {

            answeredText.textContent =
                count +
                " of " +
                total +
                " answered";

        }

    }


    updateProgress();


    /* =========================================================
       SUBMIT CONFIRMATION
    ========================================================= */

    if (quizForm) {

        quizForm.addEventListener(
            "submit",
            function (event) {

                const total =
                    document.querySelectorAll(
                        ".question-card"
                    ).length;


                const answered =
                    document.querySelectorAll(
                        ".quiz-option input:checked"
                    ).length;


                if (
                    answered < total &&
                    total > 0
                ) {

                    const unanswered =
                        total - answered;


                    const confirmed =
                        confirm(
                            "You have " +
                            unanswered +
                            " unanswered question(s).\n\n" +
                            "Are you sure you want to submit the quiz?"
                        );


                    if (!confirmed) {

                        event.preventDefault();

                    }

                }

            }
        );

    }

</script>


<?php include "../includes/footer.php"; ?>