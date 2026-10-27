<?php

session_start();

require_once "../config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare(
        "SELECT id, name, email, password, role
         FROM users
         WHERE email = ?"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["role"] = $user["role"];

            if ($user["role"] === "admin") {

                header("Location: ../admin/dashboard.php");

            } else {

                header("Location: dashboard.php");
            }

            exit;

        } else {

            $message = "Invalid email or password.";
        }

    } else {

        $message = "Invalid email or password.";
    }

    $stmt->close();
}

include "../includes/header.php";

?>

<style>

    /* =========================================================
       QUIZCORE STUDENT LOGIN
       UI ONLY — LOGIN LOGIC UNCHANGED
    ========================================================= */

    :root {
        --auth-bg: #070b14;
        --auth-surface: #101827;
        --auth-surface-2: #141e30;
        --auth-border: rgba(255,255,255,0.08);
        --auth-text: #f8fafc;
        --auth-muted: #94a3b8;
        --auth-primary: #6366f1;
        --auth-primary-2: #8b5cf6;
        --auth-cyan: #22d3ee;
    }

    html[data-theme="light"] {
        --auth-bg: #f5f7fb;
        --auth-surface: #ffffff;
        --auth-surface-2: #f8fafc;
        --auth-border: rgba(15,23,42,0.09);
        --auth-text: #0f172a;
        --auth-muted: #64748b;
        --auth-primary: #4f46e5;
        --auth-primary-2: #7c3aed;
        --auth-cyan: #0891b2;
    }

    body {
        background:
            radial-gradient(
                circle at 15% 15%,
                rgba(99,102,241,0.12),
                transparent 32%
            ),
            radial-gradient(
                circle at 85% 85%,
                rgba(34,211,238,0.08),
                transparent 30%
            ),
            var(--auth-bg);

        color: var(--auth-text);
    }

    .auth-section {
        min-height: calc(100vh - 80px);

        display: flex;
        align-items: center;

        padding:
            70px 20px;

        position: relative;

        overflow: hidden;
    }

    .auth-section::before {
        content: "";

        position: absolute;

        width: 420px;
        height: 420px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(99,102,241,0.13),
                transparent 70%
            );

        top: -180px;
        left: -180px;

        pointer-events: none;
    }

    .auth-section::after {
        content: "";

        position: absolute;

        width: 380px;
        height: 380px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(34,211,238,0.08),
                transparent 70%
            );

        bottom: -180px;
        right: -150px;

        pointer-events: none;
    }

    .auth-section .container {
        position: relative;
        z-index: 2;
    }

    /* =========================================================
       LOGIN CARD
    ========================================================= */

    .form-card {
        width: 100%;
        max-width: 480px;

        margin: auto;

        padding: 40px;

        background:
            linear-gradient(
                145deg,
                var(--auth-surface),
                var(--auth-surface-2)
            );

        border:
            1px solid
            var(--auth-border);

        border-radius: 26px;

        box-shadow:
            0 30px 80px rgba(0,0,0,0.28);

        position: relative;
        overflow: hidden;
    }

    .form-card::before {
        content: "";

        position: absolute;

        width: 200px;
        height: 200px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(99,102,241,0.13),
                transparent 70%
            );

        top: -100px;
        right: -80px;

        pointer-events: none;
    }

    .form-card::after {
        content: "";

        position: absolute;

        width: 150px;
        height: 150px;

        border-radius: 50%;

        background:
            radial-gradient(
                circle,
                rgba(34,211,238,0.07),
                transparent 70%
            );

        bottom: -90px;
        left: -80px;

        pointer-events: none;
    }

    .form-content {
        position: relative;
        z-index: 2;
    }

    /* =========================================================
       BRAND ICON
    ========================================================= */

    .auth-icon {
        width: 64px;
        height: 64px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin:
            0 auto
            20px;

        border-radius: 18px;

        background:
            linear-gradient(
                135deg,
                var(--auth-primary),
                var(--auth-primary-2)
            );

        color: white;

        font-size: 1.25rem;
        font-weight: 800;

        box-shadow:
            0 14px 35px
            rgba(99,102,241,0.30);
    }

    .form-title {
        color: var(--auth-text);

        font-size: 1.85rem;

        font-weight: 800;

        letter-spacing: -0.8px;
    }

    .form-subtitle {
        color: var(--auth-muted);

        font-size: .95rem;

        margin-bottom: 0;
    }

    /* =========================================================
       FORM
    ========================================================= */

    .form-label {
        color: var(--auth-text);

        font-size: .86rem;

        font-weight: 700;

        margin-bottom: 8px;
    }

    .input-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;

        left: 15px;
        top: 50%;

        transform: translateY(-50%);

        color: var(--auth-muted);

        pointer-events: none;

        z-index: 3;
    }

    .form-control {
        min-height: 53px;

        padding:
            12px
            46px
            12px
            44px;

        border-radius: 13px;

        border:
            1px solid
            var(--auth-border);

        background:
            rgba(255,255,255,0.035);

        color: var(--auth-text);

        transition:
            border-color .2s ease,
            box-shadow .2s ease,
            background .2s ease;
    }

    html[data-theme="light"] .form-control {
        background: #f8fafc;
    }

    .form-control::placeholder {
        color: var(--auth-muted);
        opacity: .75;
    }

    .form-control:focus {
        background: var(--auth-surface);

        color: var(--auth-text);

        border-color:
            rgba(99,102,241,0.65);

        box-shadow:
            0 0 0 4px
            rgba(99,102,241,0.10);
    }

    /* =========================================================
       PASSWORD BUTTON
    ========================================================= */

    .password-toggle {
        position: absolute;

        right: 12px;
        top: 50%;

        transform: translateY(-50%);

        border: none;

        background: transparent;

        color: var(--auth-muted);

        width: 35px;
        height: 35px;

        border-radius: 9px;

        display: flex;
        align-items: center;
        justify-content: center;

        transition: .2s ease;

        z-index: 4;
    }

    .password-toggle:hover {
        color: var(--auth-text);

        background:
            rgba(148,163,184,0.08);
    }

    /* =========================================================
       LOGIN BUTTON
    ========================================================= */

    .btn-login {
        min-height: 53px;

        border: none;

        border-radius: 13px;

        background:
            linear-gradient(
                135deg,
                var(--auth-primary),
                var(--auth-primary-2)
            );

        color: white;

        font-weight: 750;

        box-shadow:
            0 12px 30px
            rgba(99,102,241,0.24);

        transition: .25s ease;
    }

    .btn-login:hover {
        color: white;

        transform: translateY(-2px);

        box-shadow:
            0 17px 38px
            rgba(99,102,241,0.34);
    }

    /* =========================================================
       ERROR MESSAGE
    ========================================================= */

    .login-alert {
        background:
            rgba(239,68,68,0.09);

        border:
            1px solid
            rgba(239,68,68,0.22);

        color: #fca5a5;

        border-radius: 13px;

        font-size: .9rem;

        line-height: 1.5;
    }

    html[data-theme="light"] .login-alert {
        color: #b91c1c;
    }

    /* =========================================================
       REGISTER LINK
    ========================================================= */

    .register-link {
        color: var(--auth-cyan) !important;

        font-weight: 700;

        text-decoration: none;
    }

    .register-link:hover {
        text-decoration: underline;
    }

    /* =========================================================
       SECURITY NOTE
    ========================================================= */

    .security-note {
        display: flex;

        align-items: center;
        justify-content: center;

        gap: 7px;

        color: var(--auth-muted);

        font-size: .75rem;

        margin-top: 22px;
    }

    .security-note i {
        color: #22c55e;
    }

    /* =========================================================
       DEMO / INFO AREA
    ========================================================= */

    .login-info {
        display: flex;

        align-items: center;

        gap: 10px;

        padding: 13px 15px;

        margin-top: 24px;

        border-radius: 13px;

        background:
            rgba(99,102,241,0.07);

        border:
            1px solid
            rgba(99,102,241,0.12);

        color: var(--auth-muted);

        font-size: .78rem;

        line-height: 1.5;
    }

    .login-info i {
        color: #a5b4fc;

        font-size: 1rem;
    }

    @media (max-width: 576px) {

        .auth-section {
            padding:
                45px 15px;
        }

        .form-card {
            padding: 30px 22px;

            border-radius: 21px;
        }

        .form-title {
            font-size: 1.6rem;
        }

    }

</style>


<!-- =========================================================
     LOAD SAVED THEME
========================================================= -->

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


<section class="auth-section">

    <div class="container">

        <div class="form-card">

            <div class="form-content">


                <!-- =================================================
                     HEADER
                ================================================= -->

                <div class="text-center">

                    <div class="auth-icon">

                        Q

                    </div>


                    <h2 class="form-title">

                        Welcome Back

                    </h2>


                    <p class="form-subtitle">

                        Login to continue to QuizCore

                    </p>

                </div>


                <!-- =================================================
                     ERROR MESSAGE
                ================================================= -->

                <?php if ($message): ?>

                    <div
                        class="alert login-alert mt-4 mb-4">

                        <i
                            class="bi bi-exclamation-circle-fill me-2">
                        </i>

                        <?php echo htmlspecialchars($message); ?>

                    </div>

                <?php endif; ?>


                <!-- =================================================
                     LOGIN FORM
                ================================================= -->

                <form
                    method="POST"
                    class="mt-4">


                    <!-- EMAIL -->

                    <div class="mb-3">

                        <label class="form-label">

                            Email Address

                        </label>


                        <div class="input-wrapper">

                            <i
                                class="bi bi-envelope input-icon">
                            </i>


                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="student@example.com"
                                autocomplete="email"
                                required>

                        </div>

                    </div>


                    <!-- PASSWORD -->

                    <div class="mb-4">

                        <label class="form-label">

                            Password

                        </label>


                        <div class="input-wrapper">

                            <i
                                class="bi bi-lock input-icon">
                            </i>


                            <input
                                type="password"
                                name="password"
                                id="loginPassword"
                                class="form-control"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required>


                            <button
                                type="button"
                                class="password-toggle"
                                onclick="toggleLoginPassword()"
                                aria-label="Show password">

                                <i
                                    class="bi bi-eye"
                                    id="loginPasswordIcon">
                                </i>

                            </button>

                        </div>

                    </div>


                    <!-- LOGIN BUTTON -->

                    <button
                        type="submit"
                        class="btn btn-login w-100">

                        <i
                            class="bi bi-box-arrow-in-right me-2">
                        </i>

                        Login to QuizCore

                    </button>


                </form>


                <!-- =================================================
                     REGISTER
                ================================================= -->

                <p class="text-center muted mt-4 mb-0">

                    Don't have an account?

                    <a
                        href="register.php"
                        class="register-link">

                        Create an account

                    </a>

                </p>


                <!-- =================================================
                     INFO
                ================================================= -->

                <div class="login-info">

                    <i class="bi bi-info-circle-fill"></i>

                    <span>
                        Use your registered student email and
                        password to access your quizzes and results.
                    </span>

                </div>


                <!-- =================================================
                     SECURITY
                ================================================= -->

                <div class="security-note">

                    <i class="bi bi-shield-check"></i>

                    Secure student authentication

                </div>


            </div>

        </div>

    </div>

</section>


<script>

    /* =========================================================
       PASSWORD VISIBILITY
    ========================================================= */

    function toggleLoginPassword() {

        const password =
            document.getElementById("loginPassword");

        const icon =
            document.getElementById("loginPasswordIcon");


        if (password.type === "password") {

            password.type = "text";

            icon.className =
                "bi bi-eye-slash";

        } else {

            password.type = "password";

            icon.className =
                "bi bi-eye";

        }

    }


    /* =========================================================
       KEEP QUIZCORE THEME
    ========================================================= */

    function syncQuizCoreTheme() {

        const savedTheme =
            localStorage.getItem("quizcore-theme")
            || "dark";

        document.documentElement
            .setAttribute(
                "data-theme",
                savedTheme
            );

    }


    syncQuizCoreTheme();

</script>


<?php include "../includes/footer.php"; ?>