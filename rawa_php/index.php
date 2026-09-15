<?php

// index.php — الصفحة الرئيسية لمشروع رواء
require_once 'connection.php';
$pageTitle = "رواء - تبني نبتة";
include 'header.php';
include 'menu.php';

// تحديد الاستعلام بناءً على التصنيف أو البحث أو الكل
if (isset($_GET['cat_id']) && is_numeric($_GET['cat_id'])) {
    $cat_id = (int)$_GET['cat_id'];
    $sql = "SELECT * FROM offer WHERE cat_id = $cat_id ORDER BY id DESC";
    $active_cat = $cat_id;
} elseif (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = mysqli_real_escape_string($conn, trim($_GET['search']));
    $sql = "SELECT * FROM offer WHERE title LIKE '%$search%' ORDER BY id DESC";
    $active_cat = 0;
} else {
    $sql = "SELECT * FROM offer ORDER BY id DESC";
    $active_cat = 0;
}
?>

<!-- HERO -->
<header class="hero-section">
    <div class="hero-tagline">امنحها بداية جديدة بتبنيها</div>
</header>

<!-- عداد النباتات -->
<?php
$counter_sql = "SELECT COUNT(*) as total FROM offer WHERE status = 'available'";
$counter_res = mysqli_query($conn, $counter_sql);
$counter_row = mysqli_fetch_array($counter_res);
$total_available = $counter_row['total'];
?>
<div class="counter-bar">
    <span class="counter-icon">🌿</span>
    <span class="counter-text">يوجد حالياً <strong><?php echo $total_available; ?></strong> نبتة متاحة للتبني</span>
</div>

<div style="background-color: #e8f5e9; padding: 15px; border-radius: 20px; text-align: center; margin: 20px auto; width: fit-content; border: 1px solid #c8e6c9; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    <p style="font-size: 1.2em; color: #1b5e20; margin: 0; font-family: sans-serif;">
        <?php
        // استعلام منفصل تماماً
        $conn_temp = mysqli_connect("localhost", "root", "", "rawa_db");
        $result_temp = mysqli_query($conn_temp, "SELECT COUNT(*) as total FROM reservation");
        $data = mysqli_fetch_assoc($result_temp);
        
        echo "🌿 تم تبني <span style='font-size: 1.4em; font-weight: bold; color: #2e7d32;'>" . $data['total'] . "</span>  نباتات حتى الآن ! شكرا لمساهمتكم";
        
        mysqli_close($conn_temp);
        ?>
    </p>
</div>


<!-- روابط التصنيف من قاعدة البيانات -->
<div class="category-links">
    <a href="index.php" <?php if($active_cat===0) echo 'class="active-cat"'; ?>>🌿 الكل</a>
    <?php
    $cat_sql = "SELECT * FROM category";
    $cat_res = mysqli_query($conn, $cat_sql);
    $icons   = ['🪴','☘️','🍃','🌸','🌺','🌻','🌼','💐'];
    $i = 0;
    while ($cat = mysqli_fetch_array($cat_res)) {
        $icon = $icons[$i % count($icons)];
        $active = ($active_cat == $cat['id']) ? 'class="active-cat"' : '';
        echo "<a href='index.php?cat_id={$cat['id']}' $active>$icon " . htmlspecialchars($cat['title']) . "</a>";
        $i++;
    }
    ?>
</div>

<!-- عرض النباتات -->
<?php
$result = mysqli_query($conn, $sql);
$count  = mysqli_num_rows($result);

// عنوان القسم
if (isset($_GET['cat_id'])) {
    $cat_info = mysqli_query($conn, "SELECT title FROM category WHERE id=$active_cat");
    $cat_row  = mysqli_fetch_array($cat_info);
    echo "<h2 class='section-heading'>🌿 " . htmlspecialchars($cat_row['title'] ?? '') . "</h2>";
} elseif (isset($_GET['search'])) {
    echo "<h2 class='section-heading'>🔍 نتائج البحث عن: " . htmlspecialchars($_GET['search']) . "</h2>";
} else {
    echo "<h2 class='section-heading'>🌿 جميع النباتات</h2>";
}

if ($count == 0) {
    echo "<p style='text-align:center;color:var(--text-muted);padding:40px;'>لا توجد نباتات في هذا التصنيف حالياً 🍃</p>";
} else {
    echo "<div class='plants-container'>";
    while ($plant = mysqli_fetch_array($result)) {


        // شارة الحالة
        $status_map = [
            'available' => '🌿 متاحة',
            'reserved'  => '⏳ محجوزة',
            'care'      => '⚠️ تحتاج عناية',
            'adopted'   => '✅ تم تبنّيها',
        ];
        $status_label = $status_map[$plant['status']] ?? '🌿 متاحة';

        // الصورة
        $img = !empty($plant['image']) ? htmlspecialchars($plant['image']) : 'https://via.placeholder.com/300x200?text=لا+توجد+صورة';

        echo "
        <div class='card'>
            <img src='$img' alt='" . htmlspecialchars($plant['title']) . "'>
            <h3>" . htmlspecialchars($plant['title']) . "</h3>
            <p>" . htmlspecialchars(mb_substr($plant['description'], 0, 70)) . "...</p>";



        echo "
            <span class='status-badge'>$status_label</span>
            <a href='plant.php?id={$plant['id']}' class='details-btn'>عرض التفاصيل</a>
        </div>";
    }
    echo "</div>";
}
?>

<?php include 'footer.php'; ?>
