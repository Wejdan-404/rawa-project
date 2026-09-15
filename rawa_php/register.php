<?php
require_once 'connection.php';

if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$errors   = [];
$success  = false;
$posted   = false;
$fields   = ['firstName'=>'','lastName'=>'','email'=>'','password'=>'','address'=>'','mobile'=>''];
$fieldErrors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $posted = true;
    foreach ($fields as $k => $_) {
        $fields[$k] = trim($_POST[$k] ?? '');
    }

    if (empty($fields['firstName']))  $fieldErrors['firstName'] = "الاسم الأول مطلوب.";
    if (empty($fields['lastName']))   $fieldErrors['lastName']  = "الاسم الأخير مطلوب.";
    if (empty($fields['email']))      $fieldErrors['email']     = "البريد الإلكتروني مطلوب.";
    elseif (!filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) $fieldErrors['email'] = "صيغة البريد الإلكتروني غير صحيحة.";
    if (empty($fields['password']))   $fieldErrors['password']  = "كلمة المرور مطلوبة.";
    elseif (strlen($fields['password']) < 6) $fieldErrors['password'] = "كلمة المرور يجب أن تكون 6 أحرف على الأقل.";
    if (empty($fields['address']))    $fieldErrors['address']   = "العنوان مطلوب.";
    if (empty($fields['mobile']))     $fieldErrors['mobile']    = "رقم الجوال مطلوب.";

    if (empty($fieldErrors['email']) && !empty($fields['email'])) {
        $em = mysqli_real_escape_string($conn, $fields['email']);
        if (mysqli_num_rows(mysqli_query($conn, "SELECT id FROM user WHERE email='$em'")) > 0) {
            $fieldErrors['email'] = "هذا البريد الإلكتروني مسجل مسبقاً.";
        }
    }

    if (empty($fieldErrors)) {
        $fn   = mysqli_real_escape_string($conn, $fields['firstName']);
        $ln   = mysqli_real_escape_string($conn, $fields['lastName']);
        $em   = mysqli_real_escape_string($conn, $fields['email']);
        $pw   = password_hash($fields['password'], PASSWORD_DEFAULT);
        $ad   = mysqli_real_escape_string($conn, $fields['address']);
        $mob  = mysqli_real_escape_string($conn, $fields['mobile']);
        $dt   = date('Y-m-d H:i:s');

        $sql = "INSERT INTO user (firstName, lastName, email, password, address, mobile, is_admin, creation_date)
                VALUES ('$fn','$ln','$em','$pw','$ad','$mob',0,'$dt')";

        if (mysqli_query($conn, $sql)) {
            $newId = mysqli_insert_id($conn);
            $_SESSION['user_id']   = $newId;
            $_SESSION['first_name']= $fields['firstName'];
            $_SESSION['last_name'] = $fields['lastName'];
            $_SESSION['email']     = $fields['email'];
            $_SESSION['is_admin']  = 0;
            $success = true;
        } else {
            $fieldErrors['_general'] = "حدث خطأ أثناء الحفظ، حاول مجدداً.";
        }
    }
}

$pageTitle = "إنشاء حساب";
include 'header.php';
include 'menu.php';
?>

<div class="login-wrapper">
  <div class="login-container" style="max-width:520px;">

    <?php if ($success): ?>
    <div class="success-card">
      <div class="success-icon">🌱</div>
      <h2>تم إنشاء حسابك بنجاح!</h2>
      <p>أهلاً بك في مجتمع رواء 🌿<br>يمكنك الآن تصفح النباتات وتبنّيها.</p>
      <div class="success-actions">
        <a href="index.php" class="confirm-btn">🏠 الصفحة الرئيسية</a>
      </div>
    </div>

    <?php else: ?>
    <h2>🌱 إنشاء حساب جديد</h2>

    <?php if (!empty($fieldErrors['_general'])): ?>
    <div class="alert-error"><p>⚠️ <?php echo $fieldErrors['_general']; ?></p></div>
    <?php endif; ?>

    <form method="POST" action="register.php" novalidate>

      <div class="frow">
        <div class="input-group">
          <label>الاسم الأول <span class="req">*</span></label>
          <input type="text" name="firstName"
                 class="<?php echo isset($fieldErrors['firstName']) ? 'input-invalid' : ($posted && empty($fieldErrors['firstName']) && !empty($fields['firstName']) ? 'input-valid' : ''); ?>"
                 value="<?php echo htmlspecialchars($fields['firstName']); ?>"
                 placeholder="مثال: نورة">
          <?php if (isset($fieldErrors['firstName'])): ?>
          <span class="field-error">⚠️ <?php echo $fieldErrors['firstName']; ?></span>
          <?php endif; ?>
        </div>
        <div class="input-group">
          <label>الاسم الأخير <span class="req">*</span></label>
          <input type="text" name="lastName"
                 class="<?php echo isset($fieldErrors['lastName']) ? 'input-invalid' : ($posted && !empty($fields['lastName']) ? 'input-valid' : ''); ?>"
                 value="<?php echo htmlspecialchars($fields['lastName']); ?>"
                 placeholder="مثال: العمري">
          <?php if (isset($fieldErrors['lastName'])): ?>
          <span class="field-error">⚠️ <?php echo $fieldErrors['lastName']; ?></span>
          <?php endif; ?>
        </div>
      </div>

      <div class="input-group">
        <label>البريد الإلكتروني <span class="req">*</span></label>
        <input type="email" name="email"
               class="<?php echo isset($fieldErrors['email']) ? 'input-invalid' : ($posted && !empty($fields['email']) ? 'input-valid' : ''); ?>"
               value="<?php echo htmlspecialchars($fields['email']); ?>"
               placeholder="example@mail.com">
        <?php if (isset($fieldErrors['email'])): ?>
        <span class="field-error">⚠️ <?php echo $fieldErrors['email']; ?></span>
        <?php endif; ?>
      </div>

      <div class="input-group">
        <label>كلمة المرور <span class="req">*</span></label>
        <input type="password" name="password"
               class="<?php echo isset($fieldErrors['password']) ? 'input-invalid' : ($posted && !empty($fields['password']) ? 'input-valid' : ''); ?>"
               placeholder="6 أحرف على الأقل">
        <?php if (isset($fieldErrors['password'])): ?>
        <span class="field-error">⚠️ <?php echo $fieldErrors['password']; ?></span>
        <?php endif; ?>
      </div>

      <div class="input-group">
        <label>العنوان <span class="req">*</span></label>
        <input type="text" name="address"
               class="<?php echo isset($fieldErrors['address']) ? 'input-invalid' : ($posted && !empty($fields['address']) ? 'input-valid' : ''); ?>"
               value="<?php echo htmlspecialchars($fields['address']); ?>"
               placeholder="مثال: الطائف، حي الفيصلية">
        <?php if (isset($fieldErrors['address'])): ?>
        <span class="field-error">⚠️ <?php echo $fieldErrors['address']; ?></span>
        <?php endif; ?>
      </div>

      <div class="input-group">
        <label>رقم الجوال <span class="req">*</span></label>
        <input type="tel" name="mobile"
               class="<?php echo isset($fieldErrors['mobile']) ? 'input-invalid' : ($posted && !empty($fields['mobile']) ? 'input-valid' : ''); ?>"
               value="<?php echo htmlspecialchars($fields['mobile']); ?>"
               placeholder="05XXXXXXXX" maxlength="10">
        <?php if (isset($fieldErrors['mobile'])): ?>
        <span class="field-error">⚠️ <?php echo $fieldErrors['mobile']; ?></span>
        <?php endif; ?>
      </div>

      <button type="submit" class="login-btn">إنشاء الحساب 🌿</button>

      <div class="footer-link">
        لديك حساب بالفعل؟ <a href="login.php">تسجيل الدخول</a>
      </div>

    </form>
    <?php endif; ?>

  </div>
</div>

<?php include 'footer.php'; ?>
