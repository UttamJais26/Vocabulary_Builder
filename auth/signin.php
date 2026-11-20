<?php
session_start();
include "../config/database.php";

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

$findUser = $conn->prepare(
    "SELECT id, username, password FROM users WHERE email = ?"
);

$findUser->bind_param("s", $email);
$findUser->execute();

$result = $findUser->get_result();

if ($result->num_rows !== 1) {
    die("Email not registered!");
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user["password"])) {
    die("Incorrect password!");
}

$_SESSION["user_id"] = $user["id"];
$_SESSION["username"] = $user["username"];

/* This must match your real dashboard filename */
header("Location: ../dsh/dsh.html");
exit();
?>