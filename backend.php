<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$pass = ""; // your MySQL password
$db = "registration_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Registration
    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['college']) && isset($_POST['roll']) && isset($_POST['password'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $college = $conn->real_escape_string($_POST['college']);
        $roll = $conn->real_escape_string($_POST['roll']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, college, roll, password) VALUES ('$name', '$email', '$college', '$roll', '$password')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Registration successful!');window.location.href='registration.html';</script>";
        } else {
            echo "<script>alert('Email already registered or error occurred.');window.location.href='registration.html';</script>";
        }
    }
    // Login
    elseif (isset($_POST['loginEmail']) && isset($_POST['loginPassword'])) {
        $email = $conn->real_escape_string($_POST['loginEmail']);
        $password = $_POST['loginPassword'];

        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['user'] = $row['name'];
                echo "<script>alert('Login successful! Welcome, " . htmlspecialchars($row['name']) . "');window.location.href='registration.html';</script>";
            } else {
                echo "<script>alert('Invalid password.');window.location.href='registration.html';</script>";
            }
        } else {
            echo "<script>alert('No user found with this email.');window.location.href='registration.html';</script>";
        }
    }
}
$conn->close();
?>