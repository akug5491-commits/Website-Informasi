<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "hsn"; // ganti sesuai nama DB kamu (boleh pakai DB lama)

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { die("Koneksi gagal: " . $conn->connect_error); }
$conn->set_charset("utf8mb4");
