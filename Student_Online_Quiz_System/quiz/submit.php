<?php

require_once "../config/db.php";
require_once "../includes/auth.php";

requireStudent();


/*
|--------------------------------------------------------------------------
| Only POST requests are allowed
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: ../student/dashboard.php");
    exit;

}


/*
|--------------------------------------------------------------------------
| Get Quiz Data
|--------------------------------------------------------------------------
*/

$quizId = intval($_POST["quiz_id"] ?? 0);

$answers = $_POST["answers"] ?? [];


if ($quizId <= 0) {

    header("Location: ../student/dashboard.php");
    exit;

}


/*
|--------------------------------------------------------------------------
| Get Correct Answers
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare(
    "SELECT id, correct_answer
     FROM questions
     WHERE quiz_id = ?"
);

$stmt->bind_param(
    "i",
    $quizId
);

$stmt->execute();

$result = $stmt->get_result();


/*
|--------------------------------------------------------------------------
| Calculate Score
|--------------------------------------------------------------------------
*/

$score = 0;

$totalQuestions = 0;


while ($question = $result->fetch_assoc()) {

    $totalQuestions++;

    $questionId =
        $question["id"];

    $correctAnswer =
        $question["correct_answer"];

    $selectedAnswer =
        $answers[$questionId] ?? "";


    if ($selectedAnswer === $correctAnswer) {

        $score++;

    }

}


/*
|--------------------------------------------------------------------------
| Calculate Percentage
|--------------------------------------------------------------------------
*/

$percentage = 0;


if ($totalQuestions > 0) {

    $percentage =
        ($score / $totalQuestions) * 100;

}


/*
|--------------------------------------------------------------------------
| Save Result
|--------------------------------------------------------------------------
*/

$userId =
    $_SESSION["user_id"];


$stmt = $conn->prepare(
    "INSERT INTO results
    (
        user_id,
        quiz_id,
        score,
        total_questions,
        percentage
    )
    VALUES (?, ?, ?, ?, ?)"
);


$stmt->bind_param(
    "iiiid",
    $userId,
    $quizId,
    $score,
    $totalQuestions,
    $percentage
);


$stmt->execute();


/*
|--------------------------------------------------------------------------
| Get Newly Created Result ID
|--------------------------------------------------------------------------
*/

$resultId =
    $conn->insert_id;


/*
|--------------------------------------------------------------------------
| Redirect to Result
|--------------------------------------------------------------------------
*/

header(
    "Location: result.php?id=" . $resultId
);

exit;

?>