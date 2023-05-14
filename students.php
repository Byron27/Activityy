<?php

// Database connection
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Handle CRUD operations based on HTTP method
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // GET /students -> SELECT * FROM students
    if (!isset($_GET['id'])) {
        $stmt = $conn->query('SELECT * FROM students');
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($students);
    }
    // GET /students/1 -> SELECT * FROM students WHERE id = 1
    else {
        $id = $_GET['id'];
        $stmt = $conn->prepare('SELECT * FROM students WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($student);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POST /students -> INSERT INTO
    if (!isset($_POST['_method'])) {
        $name = $_POST['name'];
        $age = $_POST['age'];
        $stmt = $conn->prepare('INSERT INTO students (name, age) VALUES (:name, :age