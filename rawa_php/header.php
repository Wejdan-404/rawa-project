<?php
// header.php — رأس الصفحة HTML (يُضمَّن في كل الصفحات)
// المتغير $pageTitle يُضبط قبل include في كل صفحة
if (!isset($pageTitle)) $pageTitle = "رواء 🌿";
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> | رواء</title>
    <link rel="stylesheet" href="css/rawa.css">
</head>
<body>
