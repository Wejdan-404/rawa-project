<?php
require_once 'connection.php';

// حماية لوحة التحكم — أدمن فقط
if (!isLoggedIn() || !isAdmin()) {
    header("Location: index.php");
    exit;
}

$msg = '';
$msgType = '';

// ===== حذف نبتة =====
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $did = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM offer WHERE id = $did");
    mysqli_query($conn, "DELETE FROM reservation WHERE offer_id = $did");
    $msg = "تم حذف النبتة بنجاح.";
    $msgType = 'success';
}

// ===== إضافة / تعديل نبتة =====
$editPlant   = null;
$fieldErrors = [];
$posted      = false;

$fields = [
    'offerTitle'   => '',
    'offerDesc'    => '',
    'features'     => '',
    'careCatalog'  => '',
    'originalPrice'=> '0',
    'discount'     => '0',
    'category'     => '',
    'quantity'     => '1',
    'status'       => 'available',
    'careLevel'    => 'easy',
    'imageUrl'     => '',
];

// وضع التعديل — جلب بيانات النبتة
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $eid = (int)$_GET['edit'];
    $er  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM offer WHERE id = $eid"));
    if ($er) {
        $editPlant = $er;
        $fields['offerTitle']    = $er['title'];
        $fields['offerDesc']     = $er['description'];
        $fields['features']      = $er['features'] ?? '';
        $fields['careCatalog']   = $er['care_catalog'] ?? '';
        $fields['originalPrice'] = $er['org_price'];
        $fields['discount']      = $er['discount'];
        $fields['category']      = $er['cat_id'];
        $fields['quantity']      = $er['quantity'];
        $fields['status']        = $er['status'];
        $fields['careLevel']     = $er['care_level'];
        $fields['imageUrl']      = $er['image'] ?? '';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_plant'])) {
    $posted = true;
    foreach ($fields as $k => $_) {
        $fields[$k] = trim($_POST[$k] ?? '');
    }
    $editId = isset($_POST['edit_id']) && is_numeric($_POST['edit_id']) ? (int)$_POST['edit_id'] : 0;

    // تحقق
    if (empty($fields['offerTitle'])) $fieldErrors['offerTitle'] = "عنوان النبتة مطلوب.";
    if (empty($fields['offerDesc']))  $fieldErrors['offerDesc']  = "الوصف مطلوب.";
    if (empty($fields['category']))   $fieldErrors['category']   = "يرجى اختيار التصنيف.";
    if (!is_numeric($fields['quantity']) || (int)$fields['quantity'] < 0)
                                      $fieldErrors['quantity']   = "الكمية يجب أن تكون 0 أو أكثر.";

    // رفع صورة أو استخدام URL
    $image = $fields['imageUrl'];
    if (isset($_FILES['offerImage']) && $_FILES['offerImage']['error'] === UPLOAD_ERR_OK) {
        $file    = $_FILES['offerImage'];
        $maxSize = 5 * 1024 * 1024;
        $allowed = ['image/jpeg','image/jpg','image/png','image/gif','image/webp'];
        if ($file['size'] <= $maxSize && in_array($file['type'], $allowed)) {
            $ext     = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newName = 'plant_' . time() . '_' . rand(100,999) . '.' . $ext;
            if (move_uploaded_file($file['tmp_name'], 'uploads/' . $newName)) {
                $image = 'uploads/' . $newName;
            }
        }
    }

    if (empty($fieldErrors)) {
        $t   = mysqli_real_escape_string($conn, $fields['offerTitle']);
        $d   = mysqli_real_escape_string($conn, $fields['offerDesc']);
        $ft  = mysqli_real_escape_string($conn, $fields['features']);
        $cc  = mysqli_real_escape_string($conn, $fields['careCatalog']);
        $p   = (float)$fields['originalPrice'];
        $dis = (int)$fields['discount'];
        $cat = (int)$fields['category'];
        $qty = (int)$fields['quantity'];
        $st  = mysqli_real_escape_string($conn, $fields['status']);
        $cl  = mysqli_real_escape_string($conn, $fields['careLevel']);
        $img = mysqli_real_escape_string($conn, $image);
        $dt  = date('Y-m-d H:i:s');

        // تعديل حالة المخزون تلقائياً
        if ($qty <= 0) $st = 'adopted';

        if ($editId > 0) {
            $sql = "UPDATE offer SET
                        title='$t', description='$d', features='$ft', care_catalog='$cc',
                        org_price=$p, discount=$dis, cat_id=$cat, quantity=$qty,
                        status='$st', care_level='$cl', image='$img'
                    WHERE id=$editId";
            mysqli_query($conn, $sql);
            $msg = "✅ تم تعديل النبتة بنجاح.";
        } else {
            $sql = "INSERT INTO offer
                        (title, image, description, features, care_catalog, org_price, discount,
                         cat_id, user_id, quantity, status, care_level, creation_date)
                    VALUES ('$t','$img','$d','$ft','$cc',$p,$dis,$cat,1,$qty,'$st','$cl','$dt')";
            mysqli_query($conn, $sql);
            $msg = "✅ تمت إضافة النبتة بنجاح.";
        }

        $msgType  = 'success';
        $editPlant = null;
        foreach ($fields as $k => $_) $fields[$k] = ($k === 'quantity') ? '1' : (($k === 'discount' || $k === 'originalPrice') ? '0' : ($k === 'status' ? 'available' : ($k === 'careLevel' ? 'easy' : '')));
        $posted = false;
    } else {
        $msgType = 'error';
    }
}

// جلب بيانات للعرض
$cats    = mysqli_query($conn, "SELECT * FROM category");
$plants  = mysqli_query($conn, "SELECT o.*, c.title AS cat_title FROM offer o LEFT JOIN category c ON o.cat_id=c.id ORDER BY o.id DESC");
$resCount= mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM reservation"))['cnt'];
$plantCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM offer WHERE status != 'adopted'"))['cnt'];
$userCount  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM user WHERE is_admin=0"))['cnt'];

$pageTitle = "لوحة التحكم";
include 'header.php';
include 'menu.php';
?>

<div class="page-hero">
    <h1>⚙️ لوحة تحكم رواء</h1>
    <p>إدارة النباتات والحجوزات</p>
</div>

<div class="admin-wrapper">

<?php if ($msg): ?>
<div class="<?php echo $msgType==='success' ? 'alert-success' : 'alert-error'; ?>" style="margin-bottom:20px;">
    <?php echo $msg; ?>
</div>
<?php endif; ?>

<!-- ===== إحصائيات سريعة ===== -->
<div class="admin-stats">
    <div class="stat-card">
        <div class="stat-num"><?php echo $plantCount; ?></div>
        <div class="stat-label">نبتة متاحة</div>
    </div>
    <div class="stat-card">
        <div class="stat-num"><?php echo $resCount; ?></div>
        <div class="stat-label">حجز مسجّل</div>
    </div>
    <div class="stat-card">
        <div class="stat-num"><?php echo $userCount; ?></div>
        <div class="stat-label">مستخدم مسجّل</div>
    </div>
</div>

<!-- ===== نموذج الإضافة / التعديل ===== -->
<div class="admin-form-card">
    <h2><?php echo $editPlant ? '✏️ تعديل النبتة' : '➕ إضافة نبتة جديدة'; ?></h2>

    <?php if (!empty($fieldErrors['_general'])): ?>
    <div class="alert-error"><?php echo $fieldErrors['_general']; ?></div>
    <?php endif; ?>

    <form method="POST" action="admin.php" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="save_plant" value="1">
        <?php if ($editPlant): ?>
        <input type="hidden" name="edit_id" value="<?php echo $editPlant['id']; ?>">
        <?php endif; ?>

        <!-- عنوان النبتة -->
        <div class="fgroup">
            <label>اسم النبتة <span class="req">*</span></label>
            <input type="text" name="offerTitle"
                   class="<?php echo isset($fieldErrors['offerTitle']) ? 'input-invalid' : ''; ?>"
                   value="<?php echo htmlspecialchars($fields['offerTitle']); ?>"
                   placeholder="مثال: نبتة الياسمين">
            <?php if (isset($fieldErrors['offerTitle'])): ?>
            <span class="field-error">⚠️ <?php echo $fieldErrors['offerTitle']; ?></span>
            <?php endif; ?>
        </div>

        <!-- الوصف -->
        <div class="fgroup">
            <label>وصف النبتة <span class="req">*</span></label>
            <textarea name="offerDesc"
                      class="<?php echo isset($fieldErrors['offerDesc']) ? 'input-invalid' : ''; ?>"
                      placeholder="وصف مختصر يظهر في البطاقة..."><?php echo htmlspecialchars($fields['offerDesc']); ?></textarea>
            <?php if (isset($fieldErrors['offerDesc'])): ?>
            <span class="field-error">⚠️ <?php echo $fieldErrors['offerDesc']; ?></span>
            <?php endif; ?>
        </div>

        <!-- المميزات والصفات -->
        <div class="fgroup">
            <label>المميزات والصفات
                <span class="hint-label">(افصل بين كل ميزة بـ | مثال: رائحة جميلة|سريع النمو|سهل العناية)</span>
            </label>
            <textarea name="features" rows="3"
                      placeholder="رائحة جميلة|سريع النمو|تنقي الهواء|مناسبة للمبتدئين"><?php echo htmlspecialchars($fields['features']); ?></textarea>
        </div>

        <!-- كتالوج العناية -->
        <div class="fgroup">
            <label>كتالوج العناية
                <span class="hint-label">(افصل بين كل خطوة بـ | مثال: الري: يومياً|الضوء: شمس مباشرة)</span>
            </label>
            <textarea name="careCatalog" rows="4"
                      placeholder="الري: مرتين أسبوعياً|الضوء: ضوء غير مباشر|التربة: جيدة الصرف"><?php echo htmlspecialchars($fields['careCatalog']); ?></textarea>
        </div>

        <!-- صورة النبتة -->
        <div class="fgroup">
            <label>صورة النبتة</label>
            <div class="frow">
                <div>
                    <input type="text" name="imageUrl"
                           value="<?php echo htmlspecialchars($fields['imageUrl']); ?>"
                           placeholder="رابط الصورة (URL) أو ارفع ملف ↓">
                </div>
                <div>
                    <input type="file" name="offerImage" accept="image/*" style="padding:8px;">
                </div>
            </div>
        </div>

        <!-- السعر والخصم -->
        <div class="frow">
            <div class="fgroup">
                <label>السعر الأصلي (SAR)</label>
                <input type="number" name="originalPrice"
                       value="<?php echo htmlspecialchars($fields['originalPrice']); ?>"
                       placeholder="0" min="0" step="0.5">
            </div>
            <div class="fgroup">
                <label>نسبة الخصم (%)</label>
                <input type="number" name="discount"
                       value="<?php echo htmlspecialchars($fields['discount']); ?>"
                       placeholder="0" min="0" max="100">
            </div>
        </div>

        <!-- التصنيف والكمية -->
        <div class="frow">
            <div class="fgroup">
                <label>التصنيف <span class="req">*</span></label>
                <select name="category"
                        class="<?php echo isset($fieldErrors['category']) ? 'input-invalid' : ''; ?>">
                    <option value="" disabled <?php echo empty($fields['category']) ? 'selected' : ''; ?>>اختر التصنيف</option>
                    <?php
                    mysqli_data_seek($cats, 0);
                    while ($cat = mysqli_fetch_array($cats)) {
                        $sel = ($fields['category'] == $cat['id']) ? 'selected' : '';
                        echo "<option value='{$cat['id']}' $sel>" . htmlspecialchars($cat['title']) . "</option>";
                    }
                    ?>
                </select>
                <?php if (isset($fieldErrors['category'])): ?>
                <span class="field-error">⚠️ <?php echo $fieldErrors['category']; ?></span>
                <?php endif; ?>
            </div>
            <div class="fgroup">
                <label>الكمية المتاحة <span class="req">*</span></label>
                <input type="number" name="quantity"
                       class="<?php echo isset($fieldErrors['quantity']) ? 'input-invalid' : ''; ?>"
                       value="<?php echo htmlspecialchars($fields['quantity']); ?>"
                       placeholder="0" min="0">
                <?php if (isset($fieldErrors['quantity'])): ?>
                <span class="field-error">⚠️ <?php echo $fieldErrors['quantity']; ?></span>
                <?php endif; ?>
            </div>
        </div>

        <!-- الحالة ومستوى العناية -->
        <div class="frow">
            <div class="fgroup">
                <label>حالة النبتة</label>
                <select name="status">
                    <option value="available" <?php echo $fields['status']==='available'?'selected':''; ?>>🌿 متاحة</option>
                    <option value="care"      <?php echo $fields['status']==='care'     ?'selected':''; ?>>⚠️ تحتاج عناية</option>
                    <option value="reserved"  <?php echo $fields['status']==='reserved' ?'selected':''; ?>>⏳ محجوزة</option>
                    <option value="adopted"   <?php echo $fields['status']==='adopted'  ?'selected':''; ?>>✅ منتهية</option>
                </select>
            </div>
            <div class="fgroup">
                <label>مستوى العناية</label>
                <select name="careLevel">
                    <option value="easy"   <?php echo $fields['careLevel']==='easy'  ?'selected':''; ?>>⭐ سهل</option>
                    <option value="medium" <?php echo $fields['careLevel']==='medium'?'selected':''; ?>>⭐⭐ متوسط</option>
                    <option value="hard"   <?php echo $fields['careLevel']==='hard'  ?'selected':''; ?>>⭐⭐⭐ يحتاج خبرة</option>
                </select>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="confirm-btn" style="width:auto;padding:12px 44px;">
                <?php echo $editPlant ? '💾 حفظ التعديلات' : '🌿 إضافة النبتة'; ?>
            </button>
            <?php if ($editPlant): ?>
            <a href="admin.php" class="back-btn">إلغاء التعديل</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- ===== جدول النباتات ===== -->
<div class="admin-table-card">
    <h2>🌿 قائمة النباتات</h2>
    <div style="overflow-x:auto;">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>الصورة</th>
                <th>الاسم</th>
                <th>التصنيف</th>
                <th>المخزون</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($pl = mysqli_fetch_assoc($plants)): ?>
        <tr>
            <td><?php echo $pl['id']; ?></td>
            <td>
                <?php if (!empty($pl['image'])): ?>
                <img src="<?php echo htmlspecialchars($pl['image']); ?>" alt="" style="width:52px;height:44px;object-fit:cover;border-radius:8px;">
                <?php else: ?>—<?php endif; ?>
            </td>
            <td><strong><?php echo htmlspecialchars($pl['title']); ?></strong></td>
            <td><?php echo htmlspecialchars($pl['cat_title'] ?? '—'); ?></td>
            <td>
                <span class="<?php echo $pl['quantity'] > 0 ? 'stock-ok' : 'stock-empty'; ?> stock-count-badge" style="font-size:13px;">
                    <?php echo (int)$pl['quantity']; ?>
                </span>
            </td>
            <td>
                <?php
                $badges = [
                    'available'=>'<span style="color:#27ae60;">🌿 متاحة</span>',
                    'care'     =>'<span style="color:#e67e22;">⚠️ عناية</span>',
                    'reserved' =>'<span style="color:#2980b9;">⏳ محجوزة</span>',
                    'adopted'  =>'<span style="color:#7f8c8d;">✅ منتهية</span>',
                ];
                echo $badges[$pl['status']] ?? $pl['status'];
                ?>
            </td>
            <td>
                <a href="admin.php?edit=<?php echo $pl['id']; ?>" class="admin-btn-edit">✏️ تعديل</a>
                <a href="plant.php?id=<?php echo $pl['id']; ?>" class="admin-btn-view" target="_blank">👁️ عرض</a>
                <a href="admin.php?delete=<?php echo $pl['id']; ?>" class="admin-btn-del"
                   onclick="return confirm('هل تريد حذف هذه النبتة نهائياً؟')">🗑️ حذف</a>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    </div>
</div>

<!-- ===== جدول الحجوزات ===== -->
<div class="admin-table-card">
    <h2>📋 سجل الحجوزات</h2>
    <div style="overflow-x:auto;">
    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>المستخدم</th>
                <th>البريد</th>
                <th>الجوال</th>
                <th>النبتة</th>
                <th>تاريخ الحجز</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $reservations = mysqli_query($conn,
            "SELECT r.*, u.firstName, u.lastName, u.email, u.mobile, o.title AS plant_title
             FROM reservation r
             JOIN user u ON r.user_id = u.id
             JOIN offer o ON r.offer_id = o.id
             ORDER BY r.creation_date DESC"
        );
        while ($rv = mysqli_fetch_assoc($reservations)):
        ?>
        <tr>
            <td><?php echo $rv['id']; ?></td>
            <td><?php echo htmlspecialchars($rv['firstName'] . ' ' . $rv['lastName']); ?></td>
            <td><?php echo htmlspecialchars($rv['email']); ?></td>
            <td><?php echo htmlspecialchars($rv['mobile']); ?></td>
            <td><?php echo htmlspecialchars($rv['plant_title']); ?></td>
            <td><?php echo date('Y/m/d', strtotime($rv['creation_date'])); ?></td>
            <td><span style="color:#27ae60;">⏳ بانتظار الاستلام</span></td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    </div>
</div>

</div><!-- /admin-wrapper -->

<?php include 'footer.php'; ?>
