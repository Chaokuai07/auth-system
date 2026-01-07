<?php
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "dev") {
    header("Location: ../login.php");
    exit();
}
?>
<h1>Welcome Developer: <?= $_SESSION["name"] ?></h1>
<a href="../logout.php">Logout</a>