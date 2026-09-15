<?php
require_once 'connection.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id     = (int)$_GET['id'];
$sql    = "SELECT o.*, c.title AS cat_title FROM offer o
           LEFT JOIN category c ON o.cat_id = c.id
           WHERE o.id = $id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit;
}

$plant = mysqli_fetch_assoc($result);

$care_map   = ['easy'=>'سهل ⭐','medium'=>'متوسط ⭐⭐','hard'=>'يحتاج خبرة ⭐⭐⭐'];
$care_label = $care_map[$plant['care_level']] ?? 'سهل ⭐';

// تحويل الكتالوج والمميزات من نص مفصول بـ | إلى مصفوفة
$features    = !empty($plant['features'])    ? array_filter(explode('|', $plant['features']))    : [];
$care_steps  = !empty($plant['care_catalog']) ? array_filter(explode('|', $plant['care_catalog'])) : [];

// ===== حالة الحجز للمستخدم الحالي =====
$userAlreadyReserved = false;
$reservationMsg      = '';

if (isLoggedIn()) {
    $uid = currentUserId();
    $chk = mysqli_query($conn, "SELECT id FROM reservation WHERE user_id = $uid");
    if (mysqli_num_rows($chk) > 0) {
        $userAlreadyReserved = true;
    }
}

// ===== معالجة طلب الحجز =====
$bookingSuccess = false;
$bookingError   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reserve') {

    if (!isLoggedIn()) {
        $bookingError = "يجب تسجيل الدخول أولاً لإتمام الحجز.";
    } elseif ($userAlreadyReserved) {
        $bookingError = "نعتذر منك، لقد قمت بطلب نبتة مسبقاً. هدفنا أن تصل النباتات للجميع. 🌿";
    } else {
        $uid = currentUserId();
        // تحقق من المخزون لحظياً من قاعدة البيانات
        $freshRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT quantity, status FROM offer WHERE id = $id"));

        if (!$freshRow || $freshRow['quantity'] <= 0 || $freshRow['status'] === 'adopted') {
            $bookingError = "عذراً، لا يوجد مخزون متاح من هذه النبتة حالياً.";
        } else {
            // إدراج الحجز
            $dt  = date('Y-m-d H:i:s');
            $ins = mysqli_query($conn,
                "INSERT INTO reservation (user_id, offer_id, quantity, status, creation_date)
                 VALUES ($uid, $id, 1, 'pending', '$dt')"
            );

            if ($ins) {
                // تقليل المخزون
                $newQty = (int)$freshRow['quantity'] - 1;
                $newStatus = $newQty <= 0 ? 'adopted' : 'available';
                mysqli_query($conn, "UPDATE offer SET quantity = $newQty, status = '$newStatus' WHERE id = $id");

                // التوجيه لصفحة الشكر الخارجية
                header("Location: thank.php");
                exit;
            } else {
                // UNIQUE KEY منع الحجز المزدوج
                if (mysqli_errno($conn) == 1062) {
                    $bookingError = "نعتذر منك، لقد قمت بطلب نبتة مسبقاً. هدفنا أن تصل النباتات للجميع. 🌿";
                } else {
                    $bookingError = "حدث خطأ أثناء الحجز، يرجى المحاولة مجدداً.";
                }
            }
        }
    }
}

$pageTitle = htmlspecialchars($plant['title']);
include 'header.php';
include 'menu.php';
?>

<div class="page-hero">
    <h1>🌿 <?php echo htmlspecialchars($plant['title']); ?></h1>
    <p><?php echo htmlspecialchars($plant['cat_title'] ?? ''); ?></p>
</div>

<div class="plant-details-card">

    <div style="text-align:center;margin-bottom:20px;">
        <small style="color:var(--text-muted);">
            <a href="index.php">الرئيسية</a> ← <?php echo htmlspecialchars($plant['title']); ?>
        </small>
    </div>

    <?php if (!empty($plant['image'])): ?>
    <div style="text-align:center;margin-bottom:24px;">
        <img src="<?php echo htmlspecialchars($plant['image']); ?>"
             alt="<?php echo htmlspecialchars($plant['title']); ?>"
             style="max-width:380px;width:100%;border-radius:16px;box-shadow:var(--shadow-soft);">
    </div>
    <?php endif; ?>

    <h1>🌿 نبتة: <?php echo htmlspecialchars($plant['title']); ?></h1>

    <p><strong>الوصف:</strong> <?php echo htmlspecialchars($plant['description']); ?></p>
    <p><strong>التصنيف:</strong> <?php echo htmlspecialchars($plant['cat_title'] ?? '—'); ?></p>
    <p><strong>مستوى العناية:</strong> <?php echo $care_label; ?></p>

    <!-- ===== المميزات والصفات ===== -->
    <?php if (!empty($features)): ?>
    <div class="catalog-section">
        <h3>✨ المميزات والصفات</h3>
        <ul class="catalog-list features-list">
            <?php foreach ($features as $feat): ?>
            <li>🌟 <?php echo htmlspecialchars(trim($feat)); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <!-- ===== كتالوج العناية ===== -->
    <?php if (!empty($care_steps)): ?>
    <div class="catalog-section">
        <h3>📋 كتالوج العناية</h3>
        <ul class="catalog-list care-list">
            <?php foreach ($care_steps as $step): ?>
            <li>🌱 <?php echo htmlspecialchars(trim($step)); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <!-- ===== صندوق الحجز ===== -->
    <div class="adoption-container">

        <!-- عداد المخزون -->
        <div class="stock-display">
            <?php $qty = (int)$plant['quantity']; ?>
            <?php if ($qty > 0 && $plant['status'] !== 'adopted'): ?>
                <span class="stock-count-badge stock-ok">
                    📦 المتبقي: <?php echo $qty; ?> <?php echo $qty === 1 ? 'نبتة' : 'نباتات'; ?>
                </span>
            <?php else: ?>
                <span class="stock-count-badge stock-empty">📦 نفذ المخزون</span>
            <?php endif; ?>
        </div>

        <?php if ($bookingSuccess): ?>
        <!-- نجاح الحجز -->
        <div class="booking-success-box">
            <div style="font-size:48px;margin-bottom:12px;">🌿</div>
            <h3>تم تسجيل حجزك بنجاح!</h3>
            <p>يمكنك استلام نبتتك من المتجر في الطائف.</p>
            <p style="margin-top:8px;font-size:13px;color:var(--text-muted);">
                📍 الطائف، المملكة العربية السعودية
            </p>
        </div>

        <?php elseif (!empty($bookingError)): ?>
        <!-- خطأ في الحجز -->
        <div class="booking-error-box">
            <div style="font-size:40px;margin-bottom:10px;">🌱</div>
            <p><?php echo $bookingError; ?></p>
        </div>

        <?php elseif ($plant['status'] === 'adopted' || $plant['quantity'] <= 0): ?>
        <!-- نفذ المخزون -->
        <p style="color:#c0392b;font-weight:700;font-size:16px;">❌ انتهت هذه النبتة - محجوزة بالكامل</p>

        <?php elseif (!isLoggedIn()): ?>
        <!-- غير مسجل دخول -->
        <p style="color:var(--text-muted);margin-bottom:14px;">سجّل دخولك لتتمكن من حجز هذه النبتة 🌿</p>
        <a href="login.php" class="confirm-btn" style="display:inline-block;text-decoration:none;">تسجيل الدخول للحجز</a>

        <?php elseif ($userAlreadyReserved): ?>
        <!-- حجز سابق -->
        <div class="booking-error-box">
            <div style="font-size:36px;margin-bottom:8px;">🌱</div>
            <p>نعتذر منك، لقد قمت بطلب نبتة مسبقاً.<br>هدفنا أن تصل النباتات للجميع. 🌿</p>
        </div>

        <?php else: ?>
        <!-- زر الحجز -->
        <p style="margin-bottom:16px;color:var(--text-body);">
            التبني مجاني تماماً 🎁 — استلم نبتتك من المتجر بالطائف
        </p>
        <form method="POST" action="plant.php?id=<?php echo $plant['id']; ?>">
            <input type="hidden" name="action" value="reserve">
            <div class="quantity-controls">
                <button type="button" onclick="changeQty(1)">+</button>
                <span id="chosen-quantity">1</span>
                <button type="button" onclick="changeQty(-1)">-</button>
            </div>
            <button type="submit" class="confirm-btn">تأكيد التبني</button>
        </form>
        <script>
        let qty = 1;
        const maxStock = <?php echo (int)$plant['quantity']; ?>;
        function changeQty(change) {
            let newQty = qty + change;
            if (newQty >= 1 && newQty <= maxStock) {
                qty = newQty;
                document.getElementById('chosen-quantity').innerText = qty;
            } else if (newQty > maxStock) {
                alert("عذراً، الكمية المطلوبة غير متوفرة في المخزن حالياً.");
            }
        }
        </script>

        <?php endif; ?>
    </div>

    <div style="text-align:center;margin-top:20px;">
        <a href="index.php" class="back-btn">← رجوع للرئيسية</a>
    </div>
</div>

<?php include 'footer.php'; ?>
