<?php
include 'connection.php';
if (isset($_POST['get_recommendation'])) {
    $location_type = mysqli_real_escape_string($conn, $_POST['location_type']);
    $temp_tolerance = mysqli_real_escape_string($conn, $_POST['temp_tolerance']);
    $water_freq = mysqli_real_escape_string($conn, $_POST['water_freq']);
    // استعلام مطابقة الخصائص من جدول offer
    $query = "SELECT * FROM offer 
              WHERE location_type = '$location_type' 
              AND temp_tolerance = '$temp_tolerance' 
              AND water_freq = '$water_freq'";
    $result = mysqli_query($conn, $query);
    // عدم وجود مطابقة تامة، نستعلم عن خيارات مقاربة
    $is_fallback = false;
    if (!$result || mysqli_num_rows($result) == 0) {
        $is_fallback = true;
        $fallback_query = "SELECT * FROM offer WHERE location_type = '$location_type' LIMIT 6";
        $result = mysqli_query($conn, $fallback_query);
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>توصية النباتات | رواء</title>
    <link rel="stylesheet" href="css/rawa.css">
</head>
<body>
<?php include 'menu.php'; ?>
<style>
.results-container {
    max-width: 1100px;
    margin: 40px auto;
    padding: 20px;
    direction: rtl;
    min-height: 60vh;
}
.results-title {
    color: #2e7d32;
    text-align: center;
    font-size: 28px;
    margin-bottom: 10px;
}
.results-subtitle {
    text-align: center;
    color: #666;
    margin-bottom: 30px;
}
.plant-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}
.plant-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transition: transform 0.2s;
    text-align: center;
}
.plant-card:hover {
    transform: translateY(-5px);
}
.plant-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
}
.plant-info {
    padding: 15px;
}
.plant-title {
    font-size: 20px;
    font-weight: bold;
    color: #333;
    margin-bottom: 8px;
}
.plant-desc {
    color: #777;
    font-size: 14px;
    height: 40px;
    overflow: hidden;
    margin-bottom: 15px;
}
.btn-details {
    display: inline-block;
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    width: 80%;
}
</style>
<div class="results-container">
    <h1 class="results-title">النباتات الموصى بها لك بناءً على إجاباتك 🌿</h1>
    
    <?php if (isset($is_fallback) && $is_fallback): ?>
        <p class="results-subtitle">لم نجد خياراً يطابق جميع الشروط بدقة، ولكن إليك خيارات مقترحة تناسب بيئة المكان:</p>
    <?php else: ?>
        <p class="results-subtitle">إليك أفضل النباتات المطابقة لخياراتك تماماً:</p>
    <?php endif; ?>
    <div class="plant-grid">
        <?php 
        if (isset($result) && mysqli_num_rows($result) > 0) {
            while ($plant = mysqli_fetch_assoc($result)) {
                ?>
                <div class="plant-card">
                    <img src="<?php echo htmlspecialchars($plant['image']); ?>" alt="<?php echo htmlspecialchars($plant['title']); ?>" class="plant-image">
                    <div class="plant-info">
                        <h3 class="plant-title"><?php echo htmlspecialchars($plant['title']); ?></h3>
                        <p class="plant-desc"><?php echo htmlspecialchars($plant['description']); ?></p>
                        <a href="plant.php?id=<?php echo $plant['id']; ?>" class="btn-details">عرض التفاصيل</a>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p style='text-align:center; width:100%;'>لا توجد نتائج حالياً.</p>";
        }
        ?>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>