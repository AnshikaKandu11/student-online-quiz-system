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
         WHERE email = ? AND role = 'admin'"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin["password"])) {

            $_SESSION["user_id"] = $admin["id"];
            $_SESSION["name"] = $admin["name"];
            $_SESSION["role"] = "admin";

            header("Location: dashboard.php");
            exit;

        } else {

            $message = "Invalid admin credentials.";
        }

    } else {

        $message = "Invalid admin credentials.";
    }

    $stmt->close();
}

include "../includes/header.php";

?>

<section class="auth-section">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-5 col-md-7">

                <div class="tech-card form-card p-4 p-md-5">

                    <!-- Header -->

                    <div class="text-center mb-4">

                        <div class="auth-icon">

                            <i class="bi bi-shield-lock-fill"></i>

                        </div>

                        <span class="badge-tech mb-3">

                            <i class="bi bi-shield-check"></i>

                            ADMINISTRATOR

                        </span>

                        <h2 class="fw-bold mt-3 mb-2">

                            Administrator Login

                        </h2>

                        <p class="muted mb-0">

                            Access the QuizCore administration panel.

                        </p>

                    </div>


                    <!-- Error Message -->

                    <?php if ($message): ?>

                        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">

                            <i class="bi bi-exclamation-triangle-fill"></i>

                            <span>

                                <?php echo htmlspecialchars($message); ?>

                            </span>

                        </div>

                    <?php endif; ?>


                    <!-- Login Form -->

                    <form method="POST">

                        <!-- Email -->

                        <div class="mb-4">

                            <label class="form-label">

                                <i class="bi bi-envelope me-1"></i>

                                Admin Email

                            </label>

                            <div class="position-relative">

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="admin@example.com"
                                    autocomplete="email"
                                    required>

                            </div>

                        </div>


                        <!-- Password -->

                        <div class="mb-4">

                            <div class="d-flex justify-content-between align-items-center">

                                <label class="form-label mb-0">

                                    <i class="bi bi-key me-1"></i>

                                    Password

                                </label>

                            </div>

                            <div class="input-group mt-2">

                                <input
                                    type="password"
                                    name="password"
                                    id="adminPassword"
                                    class="form-control"
                                    placeholder="Enter admin password"
                                    autocomplete="current-password"
                                    required>

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    id="toggleAdminPassword"
                                    aria-label="Show password">

                                    <i class="bi bi-eye"></i>

                                </button>

                            </div>

                        </div>


                        <!-- Login Button -->

                        <button
                            type="submit"
                            class="btn btn-tech w-100 py-3">

                            <i class="bi bi-box-arrow-in-right"></i>

                            Sign In to Admin Panel

                        </button>

                    </form>


                    <!-- Security Information -->

                    <div class="tech-divider"></div>

                    <div class="text-center">

                        <div class="d-flex justify-content-center align-items-center gap-2 mb-2">

                            <i class="bi bi-shield-check text-success"></i>

                            <span class="fw-semibold">

                                Secure Administrator Access

                            </span>

                        </div>

                        <p class="muted small mb-0">

                            This area is restricted to authorized administrators.

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<script>

document.addEventListener("DOMContentLoaded", function () {

    const passwordInput =
        document.getElementById("adminPassword");

    const toggleButton =
        document.getElementById("toggleAdminPassword");

    if (passwordInput && toggleButton) {

        toggleButton.addEventListener("click", function () {

            const icon =
                toggleButton.querySelector("i");

            if (passwordInput.type === "password") {

                passwordInput.type = "text";

                icon.className = "bi bi-eye-slash";

                toggleButton.setAttribute(
                    "aria-label",
                    "Hide password"
                );

            } else {

                passwordInput.type = "password";

                icon.className = "bi bi-eye";

                toggleButton.setAttribute(
                    "aria-label",
                    "Show password"
                );
            }

        });

    }

});

</script>


<?php include "../includes/footer.php"; ?>