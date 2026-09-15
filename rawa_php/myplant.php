<?php
// تضمين ملفات النظام الأساسية
include_once 'header.php';
include_once 'menu.php';
require_once 'connection.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

$sql = "SELECT user.firstName, offer.id, offer.title, offer.image 
        FROM reservation 
        JOIN offer ON reservation.offer_id = offer.id 
        JOIN user ON reservation.user_id = user.id 
        WHERE reservation.user_id = '$user_id'";

$result = mysqli_query($conn, $sql);

// --- الإضافة المهمة هنا ---
$user_name = ""; 
if(mysqli_num_rows($result) > 0) {
    // نأخذ الاسم من أول صف فقط
    $row_temp = mysqli_fetch_assoc($result);
    $user_name = $row_temp['firstName'] . " ";
    // نعيد المؤشر للبداية ليقرأ الـ while loop البيانات من جديد
    mysqli_data_seek($result, 0); 
}
// --------------------------
?>

<div style="width: 100%; min-height: 600px; padding: 50px 20px; background-color: #f9fbf9; text-align: center; font-family: sans-serif;">
    <h1 style="color: #1b5e20; font-size: 2.5em; margin-bottom: 30px;">🌿 نباتات <?php echo $user_name; ?>المتبناة</h1>
    
    <div style="max-width: 900px; margin: 0 auto; background: white; padding: 40px; border-radius: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 2px dashed #c8e6c9;">
        
        <?php if(mysqli_num_rows($result) > 0): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; text-align: center;">
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div style="border: 1px solid #e8f5e9; padding: 15px; border-radius: 20px; background: #fff;">
                        <img src="<?php echo $row['image']; ?>" style="width: 100%; height: 150px; object-fit: cover; border-radius: 15px;">
                        <h3 style="color: #2e7d32;"><?php echo $row['title']; ?></h3>
                        <a href="plant.php?id=<?php echo $row['id']; ?>" style="display: block; padding: 10px; background: #4caf50; color: white; text-decoration: none; border-radius: 10px; font-weight: bold;">كتالوج العناية</a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p style="font-size: 1.5em; color: #4caf50;">لم تتبنَّ شيئاً حتى الآن! 🪴</p>
            <br>
            <a href="index.php" style="padding: 15px 30px; background: #2e7d32; color: white; text-decoration: none; border-radius: 50px; font-weight: bold;">تصفح النباتات المتاحة</a>
        <?php endif; ?>
        
    </div>
</div> <?php include_once 'footer.php'; ?>