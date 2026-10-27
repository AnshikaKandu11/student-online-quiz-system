<?php

require_once "../config/db.php";

$name = "Administrator";
$email = "admin@example.com";
$password = "admin123";

$hashedPassword = password_hash(
    $password,
    PASSWORD_DEFAULT
);

$stmt = $conn->prepare(
    "SELECT id FROM users WHERE email = ?"
);

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $stmt = $conn->prepare(
        "UPDATE users
         SET password = ?, role = 'admin', name = ?
         WHERE email = ?"
    );

    $stmt->bind_param(
        "sss",
        $hashedPassword,
        $name,
        $email
    );

    $stmt->execute();

    echo "Admin account updated successfully.";

} else {

    $stmt = $conn->prepare(
        "INSERT INTO users
        (name, email, password, role)
        VALUES (?, ?, ?, 'admin')"
    );

    $stmt->bind_param(
        "sss",
        $name,
        $email,
        $hashedPassword
    );

    $stmt->execute();

    echo "Admin account created successfully.";
}

echo "<br><br>";
echo "Email: admin@example.com";
echo "<br>";
echo "Password: admin123";

?>