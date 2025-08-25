<?php
require_once "../app/includes/session.php";
session_destroy();
header("Location: login.php");
exit;
