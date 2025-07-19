<?php
include "backend/conn.php";
session_unset();
session_destroy();
header("Location: ../index.php"); // Redirect to homepage after logout
exit;
