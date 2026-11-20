<?php
session_start();
include "../config/database.php";

$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";
$confirmPassword = $_POST["confirm_password"] ?? "";

if ($password !== $confirmPassword) {
    die("Passwords do not match!");
}

/* Check whether the email already exists */
$checkUser = $conn->prepare("SELECT id FROM users WHERE email = ?");
$checkUser->bind_param("s", $email);
$checkUser->execute();

$result = $checkUser->get_result();

if ($result->num_rows > 0) {
    /* This account exists. The user must sign in instead. */
    header("Location: auth.html#signin");
    exit();
}

/* Create the account */
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$createUser = $conn->prepare(
    "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
);

$createUser->bind_param("sss", $name, $email, $hashedPassword);

if ($createUser->execute()) {
    $_SESSION["user_id"] = $conn->insert_id;
    $_SESSION["username"] = $name;

    /* Go from auth/ up one folder, then into dsh/ */
    header("Location: ../dsh/dsh.html");
    exit();
}

die("Registration failed!");
?>