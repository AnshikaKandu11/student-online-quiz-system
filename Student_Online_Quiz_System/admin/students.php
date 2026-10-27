<?php

require_once "../config/db.php";
require_once "../includes/auth.php";

requireAdmin();

$students = $conn->query(
    "SELECT
        u.id,
        u.name,
        u.email,
        u.created_at,
        COUNT(r.id) AS attempts
     FROM users u
     LEFT JOIN results r
        ON u.id = r.user_id
     WHERE u.role = 'student'
     GROUP BY
        u.id,
        u.name,
        u.email,
        u.created_at
     ORDER BY u.id DESC"
);

include "../includes/header.php";

?>

<div class="container">

    <!-- Page Header -->
    <div class="mb-5">

        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">

            <div>

                <div class="d-flex align-items-center gap-2 mb-3">

                    <span class="badge-tech">
                        <i class="bi bi-people-fill"></i>
                        ADMIN PANEL
                    </span>

                </div>

                <h1 class="fw-bold mb-2">
                    Student Records
                </h1>

                <p class="muted mb-0">
                    View registered students and their quiz activity.
                </p>

            </div>

            <div class="d-flex align-items-center gap-2">

                <div class="tech-card py-2 px-3">

                    <div class="d-flex align-items-center gap-2">

                        <div class="feature-icon mb-0"
                             style="width:42px;height:42px;font-size:1.2rem;border-radius:11px;">

                            <i class="bi bi-person-check-fill"></i>

                        </div>

                        <div>

                            <div class="small muted">
                                Total Students
                            </div>

                            <div class="fw-bold fs-5">

                                <?php echo $students->num_rows; ?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- Students Table -->
    <div class="tech-card p-0 overflow-hidden">

        <!-- Table Header -->
        <div class="p-4 border-bottom"
             style="border-color: var(--border) !important;">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                <div>

                    <h5 class="fw-bold mb-1">

                        <i class="bi bi-person-lines-fill me-2 text-info"></i>

                        Registered Students

                    </h5>

                    <p class="muted small mb-0">

                        Student accounts and quiz participation records.

                    </p>

                </div>

                <span class="badge-tech">

                    <i class="bi bi-database-check"></i>

                    Live Records

                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-tech mb-0">

                <thead>

                    <tr>

                        <th class="ps-4">
                            #
                        </th>

                        <th>
                            Student
                        </th>

                        <th>
                            Email
                        </th>

                        <th>
                            Registered
                        </th>

                        <th>
                            Attempts
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php if ($students->num_rows > 0): ?>

                        <?php while ($student = $students->fetch_assoc()): ?>

                            <?php

                            $studentName = htmlspecialchars(
                                $student["name"]
                            );

                            $studentEmail = htmlspecialchars(
                                $student["email"]
                            );

                            $initial = strtoupper(
                                substr(
                                    $student["name"],
                                    0,
                                    1
                                )
                            );

                            ?>

                            <tr>

                                <!-- ID -->
                                <td class="ps-4">

                                    <span class="muted fw-semibold">

                                        #<?php echo $student["id"]; ?>

                                    </span>

                                </td>


                                <!-- Student -->
                                <td>

                                    <div class="d-flex align-items-center gap-3">

                                        <div class="user-avatar">

                                            <?php echo htmlspecialchars($initial); ?>

                                        </div>

                                        <div>

                                            <div class="fw-semibold">

                                                <?php echo $studentName; ?>

                                            </div>

                                            <div class="small muted">

                                                Student Account

                                            </div>

                                        </div>

                                    </div>

                                </td>


                                <!-- Email -->
                                <td>

                                    <div class="d-flex align-items-center gap-2">

                                        <i class="bi bi-envelope text-info"></i>

                                        <span>

                                            <?php echo $studentEmail; ?>

                                        </span>

                                    </div>

                                </td>


                                <!-- Registered -->
                                <td>

                                    <div class="d-flex align-items-center gap-2">

                                        <i class="bi bi-calendar3 text-info"></i>

                                        <span>

                                            <?php
                                            echo date(
                                                "d M Y",
                                                strtotime(
                                                    $student["created_at"]
                                                )
                                            );
                                            ?>

                                        </span>

                                    </div>

                                </td>


                                <!-- Attempts -->
                                <td>

                                    <span class="badge-tech">

                                        <i class="bi bi-pencil-square"></i>

                                        <?php
                                        echo $student["attempts"];
                                        ?>

                                        <?php
                                        echo (
                                            $student["attempts"] == 1
                                                ? "Attempt"
                                                : "Attempts"
                                        );
                                        ?>

                                    </span>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td
                                colspan="5"
                                class="text-center py-5">

                                <div class="empty-state border-0">

                                    <i class="bi bi-people"></i>

                                    <h5 class="fw-bold mt-2">

                                        No Students Yet

                                    </h5>

                                    <p class="muted mb-0">

                                        No student accounts have been registered yet.

                                    </p>

                                </div>

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>


<?php include "../includes/footer.php"; ?>