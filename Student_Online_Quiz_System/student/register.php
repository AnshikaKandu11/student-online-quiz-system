<?php

require_once "../config/db.php";

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    if (
        empty($name) ||
        empty($email) ||
        empty($password)
    ) {

        $message = "Please fill in all required fields.";
        $messageType = "danger";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $messageType = "danger";

    } elseif (strlen($password) < 6) {

        $message = "Password must contain at least 6 characters.";
        $messageType = "danger";

    } elseif ($password !== $confirmPassword) {

        $message = "Passwords do not match.";
        $messageType = "danger";

    } else {

        $check = $conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $message = "An account with this email already exists.";
            $messageType = "warning";

        } else {

            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $conn->prepare(
                "INSERT INTO users
                (name, email, password, role)
                VALUES (?, ?, ?, 'student')"
            );

            $stmt->bind_param(
                "sss",
                $name,
                $email,
                $hashedPassword
            );

            if ($stmt->execute()) {

                $message = "Registration successful! You can now login.";
                $messageType = "success";

            } else {

                $message = "Registration failed. Please try again.";
                $messageType = "danger";
            }

            $stmt->close();
        }

        $check->close();
    }
}

include "../includes/header.php";

?>

<style>

    /* =========================================================
       QUIZCORE REGISTRATION PAGE
       UI ONLY — PHP LOGIC UNCHANGED
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

    .form-card {
        width: 100%;
        max-width: 520px;

        margin: auto;

        padding: 38px;

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

        width: 190px;
        height: 190px;

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

    .form-header {
        position: relative;
        z-index: 2;
    }

    .auth-icon {
        width: 62px;
        height: 62px;

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

        font-size: 1.8rem;

        font-weight: 800;

        letter-spacing: -0.7px;
    }

    .form-subtitle {
        color: var(--auth-muted);

        font-size: .95rem;

        margin-bottom: 0;
    }

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
        min-height: 52px;

        padding:
            12px
            45px
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
        background: rgba(148,163,184,0.08);
    }

    .btn-register {
        min-height: 52px;

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

    .btn-register:hover {
        color: white;

        transform: translateY(-2px);

        box-shadow:
            0 17px 38px
            rgba(99,102,241,0.34);
    }

    .register-alert {
        border-radius: 13px;

        border: 1px solid;

        font-size: .9rem;

        line-height: 1.5;
    }

    .alert-danger {
        background: rgba(239,68,68,0.09);
        border-color: rgba(239,68,68,0.22);
        color: #fca5a5;
    }

    .alert-warning {
        background: rgba(245,158,11,0.09);
        border-color: rgba(245,158,11,0.22);
        color: #fcd34d;
    }

    .alert-success {
        background: rgba(34,197,94,0.09);
        border-color: rgba(34,197,94,0.22);
        color: #86efac;
    }

    html[data-theme="light"] .alert-danger {
        color: #b91c1c;
    }

    html[data-theme="light"] .alert-warning {
        color: #a16207;
    }

    html[data-theme="light"] .alert-success {
        color: #15803d;
    }

    .password-hint {
        color: var(--auth-muted);

        font-size: .76rem;

        margin-top: 7px;
    }

    .login-link {
        color: var(--auth-cyan) !important;

        font-weight: 700;

        text-decoration: none;
    }

    .login-link:hover {
        text-decoration: underline;
    }

    .security-note {
        display: flex;
        align-items: center;
        justify-content: center;

        gap: 7px;

        color: var(--auth-muted);

        font-size: .75rem;

        margin-top: 20px;
    }

    .security-note i {
        color: #22c55e;
    }

    @media (max-width: 576px) {

        .auth-section {
            padding:
                45px 15px;
        }

        .form-card {
            padding: 28px 22px;

            border-radius: 21px;
        }

        .form-title {
            font-size: 1.55rem;
        }

    }

</style>


<!-- Apply the same theme used by the rest of QuizCore -->

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


            <!-- HEADER -->

            <div class="text-center form-header">

                <div class="auth-icon">

                    Q

                </div>


                <h2 class="form-title">

                    Create Your Account

                </h2>


                <p class="form-subtitle">

                    Join QuizCore as a student

                </p>

            </div>


            <div class="mt-4">


                <!-- MESSAGE -->

                <?php if ($message): ?>

                    <div
                        class="alert alert-<?php echo $messageType; ?> register-alert mb-4">

                        <?php if ($messageType === "success"): ?>

                            <i class="bi bi-check-circle-fill me-2"></i>

                        <?php elseif ($messageType === "warning"): ?>

                            <i class="bi bi-exclamation-triangle-fill me-2"></i>

                        <?php else: ?>

                            <i class="bi bi-exclamation-circle-fill me-2"></i>

                        <?php endif; ?>

                        <?php echo htmlspecialchars($message); ?>

                    </div>

                <?php endif; ?>


                <!-- FORM -->

                <form method="POST">


                    <!-- NAME -->

                    <div class="mb-3">

                        <label class="form-label">

                            Full Name

                        </label>


                        <div class="input-wrapper">

                            <i
                                class="bi bi-person input-icon">
                            </i>


                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                placeholder="Enter your full name"
                                autocomplete="name"
                                required>

                        </div>

                    </div>


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
                                placeholder="Enter your email address"
                                autocomplete="email"
                                required>

                        </div>

                    </div>


                    <!-- PASSWORD -->

                    <div class="mb-3">

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
                                id="password"
                                class="form-control"
                                placeholder="Minimum 6 characters"
                                autocomplete="new-password"
                                required>


                            <button
                                type="button"
                                class="password-toggle"
                                onclick="togglePassword('password', 'passwordIcon')"
                                aria-label="Show password">

                                <i
                                    class="bi bi-eye"
                                    id="passwordIcon">
                                </i>

                            </button>

                        </div>


                        <div class="password-hint">

                            <i class="bi bi-info-circle me-1"></i>

                            Password must contain at least 6 characters.

                        </div>

                    </div>


                    <!-- CONFIRM PASSWORD -->

                    <div class="mb-4">

                        <label class="form-label">

                            Confirm Password

                        </label>


                        <div class="input-wrapper">

                            <i
                                class="bi bi-shield-lock input-icon">
                            </i>


                            <input
                                type="password"
                                name="confirm_password"
                                id="confirmPassword"
                                class="form-control"
                                placeholder="Confirm your password"
                                autocomplete="new-password"
                                required>


                            <button
                                type="button"
                                class="password-toggle"
                                onclick="togglePassword('confirmPassword', 'confirmPasswordIcon')"
                                aria-label="Show password">

                                <i
                                    class="bi bi-eye"
                                    id="confirmPasswordIcon">
                                </i>

                            </button>

                        </div>

                    </div>


                    <!-- SUBMIT -->

                    <button
                        type="submit"
                        class="btn btn-register w-100">

                        <i class="bi bi-person-plus-fill me-2"></i>

                        Create Student Account

                    </button>


                </form>


                <!-- LOGIN -->

                <p class="text-center muted mt-4 mb-0">

                    Already have an account?

                    <a
                        href="login.php"
                        class="login-link">

                        Login

                    </a>

                </p>


                <div class="security-note">

                    <i class="bi bi-shield-check"></i>

                    Your password is securely encrypted.

                </div>


            </div>

        </div>

    </div>

</section>


<script>

    /* =========================================================
       PASSWORD VISIBILITY
    ========================================================= */

    function togglePassword(
        inputId,
        iconId
    ) {

        const input =
            document.getElementById(inputId);

        const icon =
            document.getElementById(iconId);


        if (input.type === "password") {

            input.type = "text";

            icon.className =
                "bi bi-eye-slash";

        } else {

            input.type = "password";

            icon.className =
                "bi bi-eye";

        }

    }


    /* =========================================================
       THEME TOGGLE
       Header may already contain a theme button.
       This keeps the registration page synchronized.
    ========================================================= */

    function updateThemeFromStorage() {

        const savedTheme =
            localStorage.getItem("quizcore-theme")
            || "dark";

        document.documentElement
            .setAttribute(
                "data-theme",
                savedTheme
            );

    }


    updateThemeFromStorage();

</script>


<?php include "../includes/footer.php"; ?>