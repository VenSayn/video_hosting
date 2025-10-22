<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "video_hosting";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Ошибка подключения к базе: " . $conn->connect_error]));
}
$conn->set_charset("utf8mb4");
?>