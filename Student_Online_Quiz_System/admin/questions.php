<?php

require_once "../config/db.php";
require_once "../includes/auth.php";

requireAdmin();

$message = "";
$messageType = "";

$quizId = isset($_GET["quiz_id"])
    ? intval($_GET["quiz_id"])
    : 0;


/* ADD QUESTION */

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST["add_question"])
) {

    $quizId = intval($_POST["quiz_id"]);
    $question = trim($_POST["question"]);
    $a = trim($_POST["option_a"]);
    $b = trim($_POST["option_b"]);
    $c = trim($_POST["option_c"]);
    $d = trim($_POST["option_d"]);
    $correct = $_POST["correct_answer"];

    $stmt = $conn->prepare(
        "INSERT INTO questions
        (
            quiz_id,
            question,
            option_a,
            option_b,
            option_c,
            option_d,
            correct_answer
        )
        VALUES (?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "issssss",
        $quizId,
        $question,
        $a,
        $b,
        $c,
        $d,
        $correct
    );

    if ($stmt->execute()) {

        $message = "Question added successfully.";
        $messageType = "success";

    } else {

        $message = "Unable to add question.";
        $messageType = "danger";
    }
}


/* DELETE QUESTION */

if (isset($_GET["delete"])) {

    $id = intval($_GET["delete"]);

    $stmt = $conn->prepare(
        "DELETE FROM questions WHERE id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    header(
        "Location: questions.php?quiz_id=" . $quizId
    );

    exit;
}


$quizzes = $conn->query(
    "SELECT *
     FROM quizzes
     ORDER BY title"
);


$questions = null;

if ($quizId > 0) {

    $stmt = $conn->prepare(
        "SELECT *
         FROM questions
         WHERE quiz_id = ?
         ORDER BY id"
    );

    $stmt->bind_param("i", $quizId);
    $stmt->execute();

    $questions = $stmt->get_result();
}


include "../includes/header.php";

?>

<div class="container">

    <!-- =====================================================
         PAGE HEADER
         ===================================================== -->

    <div class="mb-5">

        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">

            <div>

                <div class="d-flex align-items-center gap-2 mb-3">

                    <span class="badge-tech">

                        <i class="bi bi-question-circle-fill"></i>

                        ADMIN PANEL

                    </span>

                </div>

                <h1 class="fw-bold mb-2">

                    Question Bank

                </h1>

                <p class="muted mb-0">

                    Create, review and manage multiple-choice questions.

                </p>

            </div>


            <?php if ($quizId > 0): ?>

                <div class="badge-tech">

                    <i class="bi bi-check2-circle"></i>

                    Quiz Selected

                </div>

            <?php endif; ?>

        </div>

    </div>


    <!-- =====================================================
         ALERT MESSAGE
         ===================================================== -->

    <?php if ($message): ?>

        <div class="alert alert-<?php echo $messageType; ?> d-flex align-items-center gap-2 mb-4">

            <?php if ($messageType === "success"): ?>

                <i class="bi bi-check-circle-fill"></i>

            <?php else: ?>

                <i class="bi bi-exclamation-triangle-fill"></i>

            <?php endif; ?>

            <span>

                <?php echo htmlspecialchars($message); ?>

            </span>

        </div>

    <?php endif; ?>


    <!-- =====================================================
         SELECT QUIZ
         ===================================================== -->

    <div class="tech-card mb-5">

        <div class="d-flex align-items-start gap-3 mb-4">

            <div class="feature-icon mb-0">

                <i class="bi bi-collection-play-fill"></i>

            </div>

            <div>

                <h3 class="mb-1">

                    Select Quiz

                </h3>

                <p class="muted mb-0">

                    Choose the quiz where you want to manage questions.

                </p>

            </div>

        </div>


        <form method="GET">

            <div class="row g-3 align-items-end">

                <div class="col-lg-8">

                    <label class="form-label">

                        <i class="bi bi-list-check me-1"></i>

                        Quiz

                    </label>

                    <select
                        name="quiz_id"
                        class="form-select"
                        required>

                        <option value="">

                            Select a quiz

                        </option>

                        <?php while ($quiz = $quizzes->fetch_assoc()): ?>

                            <option
                                value="<?php echo $quiz["id"]; ?>"
                                <?php
                                echo $quizId == $quiz["id"]
                                    ? "selected"
                                    : "";
                                ?>>

                                <?php
                                echo htmlspecialchars(
                                    $quiz["title"]
                                );
                                ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>


                <div class="col-lg-4">

                    <button
                        type="submit"
                        class="btn btn-tech w-100">

                        <i class="bi bi-arrow-right-circle-fill"></i>

                        Load Questions

                    </button>

                </div>

            </div>

        </form>

    </div>


    <?php if ($quizId > 0): ?>


        <!-- =================================================
             ADD QUESTION
             ================================================= -->

        <div class="tech-card mb-5">

            <div class="d-flex align-items-start gap-3 mb-4">

                <div class="feature-icon mb-0">

                    <i class="bi bi-plus-circle-fill"></i>

                </div>

                <div>

                    <h3 class="mb-1">

                        Add Question

                    </h3>

                    <p class="muted mb-0">

                        Create a new multiple-choice question for this quiz.

                    </p>

                </div>

            </div>


            <form method="POST">

                <input
                    type="hidden"
                    name="quiz_id"
                    value="<?php echo $quizId; ?>">


                <!-- Question -->

                <div class="mb-4">

                    <label class="form-label">

                        <i class="bi bi-chat-square-text me-1"></i>

                        Question

                    </label>

                    <textarea
                        name="question"
                        class="form-control"
                        rows="4"
                        placeholder="Enter your question here..."
                        required></textarea>

                </div>


                <!-- Options -->

                <div class="mb-3">

                    <h6 class="fw-bold mb-3">

                        <i class="bi bi-ui-radios-grid me-1 text-info"></i>

                        Answer Options

                    </h6>

                </div>


                <div class="row g-4">

                    <!-- Option A -->

                    <div class="col-md-6">

                        <label class="form-label">

                            <span class="badge rounded-pill text-bg-primary me-1">
                                A
                            </span>

                            Option A

                        </label>

                        <input
                            type="text"
                            name="option_a"
                            class="form-control"
                            placeholder="Enter option A"
                            required>

                    </div>


                    <!-- Option B -->

                    <div class="col-md-6">

                        <label class="form-label">

                            <span class="badge rounded-pill text-bg-primary me-1">
                                B
                            </span>

                            Option B

                        </label>

                        <input
                            type="text"
                            name="option_b"
                            class="form-control"
                            placeholder="Enter option B"
                            required>

                    </div>


                    <!-- Option C -->

                    <div class="col-md-6">

                        <label class="form-label">

                            <span class="badge rounded-pill text-bg-primary me-1">
                                C
                            </span>

                            Option C

                        </label>

                        <input
                            type="text"
                            name="option_c"
                            class="form-control"
                            placeholder="Enter option C"
                            required>

                    </div>


                    <!-- Option D -->

                    <div class="col-md-6">

                        <label class="form-label">

                            <span class="badge rounded-pill text-bg-primary me-1">
                                D
                            </span>

                            Option D

                        </label>

                        <input
                            type="text"
                            name="option_d"
                            class="form-control"
                            placeholder="Enter option D"
                            required>

                    </div>


                    <!-- Correct Answer -->

                    <div class="col-md-6">

                        <label class="form-label">

                            <i class="bi bi-check-circle me-1"></i>

                            Correct Answer

                        </label>

                        <select
                            name="correct_answer"
                            class="form-select"
                            required>

                            <option value="A">
                                Option A
                            </option>

                            <option value="B">
                                Option B
                            </option>

                            <option value="C">
                                Option C
                            </option>

                            <option value="D">
                                Option D
                            </option>

                        </select>

                    </div>

                </div>


                <!-- Submit -->

                <div class="d-flex justify-content-end mt-4">

                    <button
                        type="submit"
                        name="add_question"
                        class="btn btn-tech">

                        <i class="bi bi-plus-lg"></i>

                        Add Question

                    </button>

                </div>

            </form>

        </div>


        <!-- =================================================
             EXISTING QUESTIONS
             ================================================= -->

        <div class="tech-card">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

                <div>

                    <h3 class="mb-1">

                        Existing Questions

                    </h3>

                    <p class="muted mb-0">

                        Review all questions currently assigned to this quiz.

                    </p>

                </div>


                <?php if ($questions): ?>

                    <span class="badge-tech">

                        <i class="bi bi-list-ol"></i>

                        <?php echo $questions->num_rows; ?>

                        <?php
                        echo $questions->num_rows == 1
                            ? "Question"
                            : "Questions";
                        ?>

                    </span>

                <?php endif; ?>

            </div>


            <?php if ($questions && $questions->num_rows > 0): ?>

                <?php
                $number = 1;
                ?>

                <?php while ($question = $questions->fetch_assoc()): ?>


                    <!-- Question Card -->

                    <div class="tech-card mb-4">

                        <!-- Question Header -->

                        <div class="d-flex justify-content-between align-items-start gap-3">

                            <div class="d-flex gap-3">

                                <div
                                    class="user-avatar flex-shrink-0"
                                    style="width:42px;height:42px;border-radius:12px;">

                                    <?php echo $number; ?>

                                </div>


                                <div>

                                    <div class="small muted mb-1">

                                        QUESTION <?php echo $number; ?>

                                    </div>

                                    <h5 class="fw-bold mb-0">

                                        <?php
                                        echo htmlspecialchars(
                                            $question["question"]
                                        );
                                        ?>

                                    </h5>

                                </div>

                            </div>


                            <a
                                href="?quiz_id=<?php echo $quizId; ?>&delete=<?php echo $question["id"]; ?>"
                                class="btn btn-sm btn-outline-danger flex-shrink-0"
                                onclick="return confirm('Delete this question?');">

                                <i class="bi bi-trash3"></i>

                                <span class="d-none d-sm-inline">
                                    Delete
                                </span>

                            </a>

                        </div>


                        <div class="tech-divider"></div>


                        <!-- Options -->

                        <div class="row g-3">

                            <div class="col-md-6">

                                <div class="quiz-option mb-0">

                                    <span class="fw-bold text-info me-2">
                                        A.
                                    </span>

                                    <?php
                                    echo htmlspecialchars(
                                        $question["option_a"]
                                    );
                                    ?>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="quiz-option mb-0">

                                    <span class="fw-bold text-info me-2">
                                        B.
                                    </span>

                                    <?php
                                    echo htmlspecialchars(
                                        $question["option_b"]
                                    );
                                    ?>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="quiz-option mb-0">

                                    <span class="fw-bold text-info me-2">
                                        C.
                                    </span>

                                    <?php
                                    echo htmlspecialchars(
                                        $question["option_c"]
                                    );
                                    ?>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="quiz-option mb-0">

                                    <span class="fw-bold text-info me-2">
                                        D.
                                    </span>

                                    <?php
                                    echo htmlspecialchars(
                                        $question["option_d"]
                                    );
                                    ?>

                                </div>

                            </div>

                        </div>


                        <!-- Correct Answer -->

                        <div class="mt-4">

                            <span class="badge bg-success px-3 py-2">

                                <i class="bi bi-check-circle-fill me-1"></i>

                                Correct Answer:
                                <?php echo $question["correct_answer"]; ?>

                            </span>

                        </div>

                    </div>


                    <?php $number++; ?>

                <?php endwhile; ?>


            <?php else: ?>


                <!-- Empty State -->

                <div class="empty-state">

                    <i class="bi bi-question-circle"></i>

                    <h5 class="fw-bold">

                        No Questions Yet

                    </h5>

                    <p class="muted mb-0">

                        No questions have been added to this quiz yet.

                        Use the form above to create the first question.

                    </p>

                </div>


            <?php endif; ?>

        </div>


    <?php endif; ?>

</div>


<?php include "../includes/footer.php"; ?>