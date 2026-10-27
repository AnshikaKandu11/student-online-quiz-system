<?php
session_start();

require_once "../config/db.php";

if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] ?? "") !== "admin") {
    header("Location: login.php");
    exit;
}

$message = "";
$error = "";

/* =========================
   CREATE QUIZ
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["create_quiz"])) {

    $title = trim($_POST["title"] ?? "");
    $description = trim($_POST["description"] ?? "");

    $duration = (int)($_POST["duration"] ?? 10);

    if (isset($_POST["duration_type"]) && $_POST["duration_type"] === "custom") {
        $duration = (int)($_POST["custom_duration"] ?? 10);
    }

    $start_at = !empty($_POST["start_at"])
        ? $_POST["start_at"]
        : null;

    $end_at = !empty($_POST["end_at"])
        ? $_POST["end_at"]
        : null;

    $max_attempts = (int)($_POST["max_attempts"] ?? 1);

    $passing_percentage = (float)(
        $_POST["passing_percentage"] ?? 50
    );

    $random_questions = isset($_POST["random_questions"]) ? 1 : 0;
    $random_options = isset($_POST["random_options"]) ? 1 : 0;
    $show_result = isset($_POST["show_result"]) ? 1 : 0;
    $allow_review = isset($_POST["allow_review"]) ? 1 : 0;
    $negative_marking = isset($_POST["negative_marking"]) ? 1 : 0;

    $negative_marks = (float)(
        $_POST["negative_marks"] ?? 0
    );

    $status = $_POST["status"] ?? "active";


    /* =========================
       VALIDATION
    ========================= */

    if ($title === "") {
        $error = "Quiz title is required.";
    }

    elseif ($duration < 10 || $duration > 120) {
        $error = "Quiz duration must be between 10 and 120 minutes.";
    }

    elseif ($max_attempts < 1 || $max_attempts > 100) {
        $error = "Maximum attempts must be between 1 and 100.";
    }

    elseif ($passing_percentage < 0 || $passing_percentage > 100) {
        $error = "Passing percentage must be between 0 and 100.";
    }

    elseif ($negative_marks < 0 || $negative_marks > 100) {
        $error = "Negative marks must be between 0 and 100.";
    }

    elseif ($start_at && $end_at && strtotime($end_at) <= strtotime($start_at)) {
        $error = "End date/time must be after start date/time.";
    }

    else {

        $stmt = $conn->prepare(
            "INSERT INTO quizzes
            (
                title,
                description,
                duration,
                status,
                start_at,
                end_at,
                max_attempts,
                passing_percentage,
                random_questions,
                random_options,
                show_result,
                allow_review,
                negative_marking,
                negative_marks
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssisssidiiiiid",
            $title,
            $description,
            $duration,
            $status,
            $start_at,
            $end_at,
            $max_attempts,
            $passing_percentage,
            $random_questions,
            $random_options,
            $show_result,
            $allow_review,
            $negative_marking,
            $negative_marks
        );

        if ($stmt->execute()) {
            $message = "Quiz created successfully.";
        } else {
            $error = "Failed to create quiz: " . $stmt->error;
        }

        $stmt->close();
    }
}


/* =========================
   DELETE QUIZ
========================= */

if (isset($_GET["delete"])) {

    $id = (int)$_GET["delete"];

    if ($id > 0) {

        $stmt = $conn->prepare(
            "DELETE FROM quizzes WHERE id = ?"
        );

        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $message = "Quiz deleted successfully.";
        } else {
            $error = "Quiz not found.";
        }

        $stmt->close();
    }
}


/* =========================
   TOGGLE STATUS
========================= */

if (isset($_GET["toggle"])) {

    $id = (int)$_GET["toggle"];

    $stmt = $conn->prepare(
        "UPDATE quizzes
         SET status =
         CASE
            WHEN status = 'active' THEN 'inactive'
            ELSE 'active'
         END
         WHERE id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $message = "Quiz status updated.";

    $stmt->close();
}


/* =========================
   FETCH QUIZZES
========================= */

$quizzes = $conn->query(
    "SELECT *
     FROM quizzes
     ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Quiz Management</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<style>

body {
    background: #07111f;
    color: #eaf4ff;
    font-family: "Segoe UI", Arial, sans-serif;
}

.container {
    max-width: 1250px;
}

.page-title {
    font-size: 32px;
    font-weight: 800;
}

.subtitle {
    color: #8da5bd;
}

.card {
    background: #0d1b2e;
    border: 1px solid rgba(0, 200, 255, .15);
    border-radius: 16px;
    color: #eaf4ff;
}

.card-header {
    background: transparent;
    border-bottom: 1px solid rgba(255,255,255,.08);
}

.form-control,
.form-select {
    background: #081525;
    border: 1px solid #243a52;
    color: white;
}

.form-control:focus,
.form-select:focus {
    background: #081525;
    color: white;
    border-color: #00bfff;
    box-shadow: 0 0 0 .2rem rgba(0,191,255,.15);
}

.form-control::placeholder {
    color: #71859a;
}

.form-check-input {
    background-color: #16283d;
    border-color: #46627c;
}

.form-check-input:checked {
    background-color: #00bfff;
    border-color: #00bfff;
}

.btn-primary {
    background: linear-gradient(135deg,#008cff,#00c8a0);
    border: none;
    font-weight: 700;
}

.btn-primary:hover {
    opacity: .9;
}

.quiz-card {
    transition: .2s;
}

.quiz-card:hover {
    transform: translateY(-2px);
    border-color: rgba(0,200,255,.35);
}

.badge-active {
    background: #087f5b;
}

.badge-inactive {
    background: #495057;
}

.setting-box {
    background: #091728;
    border: 1px solid rgba(255,255,255,.06);
    border-radius: 12px;
    padding: 18px;
}

.section-title {
    font-size: 15px;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #00c8ff;
    font-weight: 800;
    margin-bottom: 15px;
}

.custom-duration {
    display: none;
}

.quiz-description {
    color: #91a7bc;
}

.small-muted {
    color: #7890a8;
}

</style>

</head>

<body>

<div class="container py-5">

    <!-- HEADER -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <div class="page-title">
                Quiz Management
            </div>

            <div class="subtitle">
                Create, schedule and manage advanced quizzes
            </div>
        </div>

        <a
        href="dashboard.php"
        class="btn btn-outline-light">
            Dashboard
        </a>

    </div>


    <!-- ALERTS -->

    <?php if ($message): ?>

        <div class="alert alert-success">
            <?= htmlspecialchars($message) ?>
        </div>

    <?php endif; ?>


    <?php if ($error): ?>

        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>


    <!-- CREATE QUIZ -->

    <div class="card mb-5">

        <div class="card-header p-4">

            <h4 class="mb-1">
                Create New Quiz
            </h4>

            <div class="small-muted">
                Configure timing, attempts, scoring and quiz behavior.
            </div>

        </div>


        <div class="card-body p-4">

            <form method="POST">

                <input
                type="hidden"
                name="create_quiz"
                value="1">


                <!-- BASIC INFORMATION -->

                <div class="section-title">
                    Basic Information
                </div>

                <div class="row g-3 mb-4">

                    <div class="col-md-8">

                        <label class="form-label">
                            Quiz Title
                        </label>

                        <input
                        type="text"
                        name="title"
                        class="form-control"
                        placeholder="Example: Computer Networks Fundamentals"
                        required>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                        name="status"
                        class="form-select">

                            <option value="active">
                                Active
                            </option>

                            <option value="inactive">
                                Inactive
                            </option>

                        </select>

                    </div>


                    <div class="col-12">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                        name="description"
                        class="form-control"
                        rows="3"
                        placeholder="Enter quiz instructions or description..."></textarea>

                    </div>

                </div>


                <!-- TIMING -->

                <div class="setting-box mb-4">

                    <div class="section-title">
                        Timing & Availability
                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label">
                                Duration
                            </label>

                            <select
                            name="duration"
                            id="duration"
                            class="form-select">

                                <option value="10">
                                    10 Minutes
                                </option>

                                <option value="15">
                                    15 Minutes
                                </option>

                                <option value="20">
                                    20 Minutes
                                </option>

                                <option value="30" selected>
                                    30 Minutes
                                </option>

                                <option value="45">
                                    45 Minutes
                                </option>

                                <option value="60">
                                    60 Minutes
                                </option>

                                <option value="90">
                                    90 Minutes
                                </option>

                                <option value="120">
                                    120 Minutes
                                </option>

                                <option value="custom">
                                    Custom Duration
                                </option>

                            </select>

                        </div>


                        <div
                        class="col-md-6 custom-duration"
                        id="customDurationBox">

                            <label class="form-label">
                                Custom Duration (10–120 minutes)
                            </label>

                            <input
                            type="number"
                            name="custom_duration"
                            class="form-control"
                            min="10"
                            max="120"
                            value="30">

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Start Date & Time
                            </label>

                            <input
                            type="datetime-local"
                            name="start_at"
                            class="form-control">

                            <div class="small-muted mt-1">
                                Leave empty for immediate availability.
                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                End Date & Time
                            </label>

                            <input
                            type="datetime-local"
                            name="end_at"
                            class="form-control">

                            <div class="small-muted mt-1">
                                Leave empty for no expiry.
                            </div>

                        </div>

                    </div>

                </div>


                <!-- ATTEMPTS & SCORING -->

                <div class="setting-box mb-4">

                    <div class="section-title">
                        Attempts & Scoring
                    </div>

                    <div class="row g-3">

                        <div class="col-md-4">

                            <label class="form-label">
                                Maximum Attempts
                            </label>

                            <input
                            type="number"
                            name="max_attempts"
                            class="form-control"
                            min="1"
                            max="100"
                            value="1">

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Passing Percentage
                            </label>

                            <div class="input-group">

                                <input
                                type="number"
                                name="passing_percentage"
                                class="form-control"
                                min="0"
                                max="100"
                                step="0.01"
                                value="50">

                                <span class="input-group-text">
                                    %
                                </span>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Negative Marks
                            </label>

                            <input
                            type="number"
                            name="negative_marks"
                            class="form-control"
                            min="0"
                            max="100"
                            step="0.01"
                            value="0">

                        </div>

                    </div>

                </div>


                <!-- BEHAVIOR -->

                <div class="setting-box mb-4">

                    <div class="section-title">
                        Quiz Behavior
                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">

                            <div class="form-check form-switch mb-3">

                                <input
                                class="form-check-input"
                                type="checkbox"
                                name="random_questions"
                                id="randomQuestions">

                                <label
                                class="form-check-label"
                                for="randomQuestions">

                                    Randomize Questions

                                </label>

                            </div>


                            <div class="form-check form-switch mb-3">

                                <input
                                class="form-check-input"
                                type="checkbox"
                                name="random_options"
                                id="randomOptions">

                                <label
                                class="form-check-label"
                                for="randomOptions">

                                    Randomize Answer Options

                                </label>

                            </div>


                            <div class="form-check form-switch">

                                <input
                                class="form-check-input"
                                type="checkbox"
                                name="negative_marking"
                                id="negativeMarking">

                                <label
                                class="form-check-label"
                                for="negativeMarking">

                                    Enable Negative Marking

                                </label>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="form-check form-switch mb-3">

                                <input
                                class="form-check-input"
                                type="checkbox"
                                name="show_result"
                                id="showResult"
                                checked>

                                <label
                                class="form-check-label"
                                for="showResult">

                                    Show Result Immediately

                                </label>

                            </div>


                            <div class="form-check form-switch">

                                <input
                                class="form-check-input"
                                type="checkbox"
                                name="allow_review"
                                id="allowReview"
                                checked>

                                <label
                                class="form-check-label"
                                for="allowReview">

                                    Allow Answer Review

                                </label>

                            </div>

                        </div>

                    </div>

                </div>


                <button
                type="submit"
                class="btn btn-primary btn-lg px-5">

                    Create Quiz

                </button>

            </form>

        </div>

    </div>


    <!-- QUIZ LIST -->

    <div class="card">

        <div class="card-header p-4">

            <h4 class="mb-0">
                Existing Quizzes
            </h4>

        </div>


        <div class="card-body p-4">

            <?php if ($quizzes && $quizzes->num_rows > 0): ?>

                <div class="row g-4">

                <?php while ($quiz = $quizzes->fetch_assoc()): ?>

                    <div class="col-lg-6">

                        <div class="card quiz-card h-100">

                            <div class="card-body p-4">

                                <div class="d-flex justify-content-between">

                                    <h5 class="mb-2">

                                        <?= htmlspecialchars(
                                            $quiz["title"]
                                        ) ?>

                                    </h5>


                                    <?php if ($quiz["status"] === "active"): ?>

                                        <span class="badge badge-active">
                                            Active
                                        </span>

                                    <?php else: ?>

                                        <span class="badge badge-inactive">
                                            Inactive
                                        </span>

                                    <?php endif; ?>

                                </div>


                                <p class="quiz-description">

                                    <?= htmlspecialchars(
                                        $quiz["description"] ?? ""
                                    ) ?>

                                </p>


                                <div class="row g-2 mb-3">

                                    <div class="col-6">

                                        <small class="small-muted">
                                            Duration
                                        </small>

                                        <div>
                                            <?= (int)$quiz["duration"] ?> min
                                        </div>

                                    </div>


                                    <div class="col-6">

                                        <small class="small-muted">
                                            Attempts
                                        </small>

                                        <div>
                                            <?= (int)$quiz["max_attempts"] ?>
                                        </div>

                                    </div>


                                    <div class="col-6">

                                        <small class="small-muted">
                                            Passing
                                        </small>

                                        <div>
                                            <?= htmlspecialchars(
                                                $quiz["passing_percentage"]
                                            ) ?>%
                                        </div>

                                    </div>


                                    <div class="col-6">

                                        <small class="small-muted">
                                            Negative Marking
                                        </small>

                                        <div>

                                            <?php if ($quiz["negative_marking"]): ?>

                                                <?= htmlspecialchars(
                                                    $quiz["negative_marks"]
                                                ) ?>

                                            <?php else: ?>

                                                Disabled

                                            <?php endif; ?>

                                        </div>

                                    </div>

                                </div>


                                <div class="d-flex gap-2 flex-wrap">

                                    <a
                                    href="questions.php?quiz_id=<?= $quiz["id"] ?>"
                                    class="btn btn-primary">

                                        Questions

                                    </a>


                                    <a
                                    href="?toggle=<?= $quiz["id"] ?>"
                                    class="btn btn-outline-info">

                                        Toggle Status

                                    </a>


                                    <a
                                    href="?delete=<?= $quiz["id"] ?>"
                                    class="btn btn-outline-danger"
                                    onclick="return confirm('Delete this quiz and all its questions/results?');">

                                        Delete

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php endwhile; ?>

                </div>

            <?php else: ?>

                <div class="text-center py-5">

                    <h5>
                        No quizzes created yet
                    </h5>

                    <p class="small-muted">
                        Create your first quiz using the form above.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>


<script>

const durationSelect =
    document.getElementById("duration");

const customBox =
    document.getElementById("customDurationBox");

function updateDurationBox() {

    if (durationSelect.value === "custom") {

        customBox.style.display = "block";

    } else {

        customBox.style.display = "none";

    }

}

durationSelect.addEventListener(
    "change",
    updateDurationBox
);

updateDurationBox();

</script>

</body>

</html>