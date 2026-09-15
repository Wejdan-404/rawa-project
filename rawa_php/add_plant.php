<?php
// add_plant.php — توجيه مباشر للأدمن فقط (المستخدمون العاديون لا يضيفون)
require_once 'connection.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

if (!isAdmin()) {
    header("Location: index.php");
    exit;
}

header("Location: admin.php");
exit;
