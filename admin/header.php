<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    die("Akses ditolak. Hanya untuk Admin.");
}
require_once "../config.php";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Glow-in</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; display: flex; }
        .sidebar { width: 250px; background: #333; color: white; height: 100vh; padding: 20px; }
        .sidebar a { color: white; display: block; padding: 10px; text-decoration: none; }
        .sidebar a:hover { background: #555; }
        .content { padding: 20px; flex: 1; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 5px 10px; text-decoration: none; background: #007bff; color: white; border: none; cursor: pointer; }
        .btn-danger { background: #dc3545; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 8px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="index.php">Dashboard</a>
        <a href="users.php">Kelola Users</a><a href="categories.php">Kelola Categories</a><a href="posts.php">Kelola Posts</a><a href="comments.php">Kelola Comments</a><a href="specialties.php">Kelola Specialties</a><a href="reports.php">Kelola Reports</a>
        <a href="../logout.php">Logout</a>
    </div>
    <div class="content">
