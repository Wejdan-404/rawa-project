<?php
require_once 'connection.php';

// إذا مسجل دخول وجّهه للرئيسية
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$error   = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = "يرجى إدخال البريد الإلكتروني وكلمة المرور.";
    } else {
        $em  = mysqli_real_escape_string($conn, $email);
        $res = mysqli_query($conn, "SELECT * FROM user WHERE email='$em'");

        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id']   = $row['id'];
                $_SESSION['first_name']= $row['firstName'];
                $_SESSION['last_name'] = $row['lastName'];
                $_SESSION['email']     = $row['email'];
                $_SESSION['is_admin']  = $row['is_admin'];
                $success = true;
            } else {
                $error = "البريد الإلكتروني أو كلمة المرور غير صحيحة.";
            }
        } else {
            $error = "البريد الإلكتروني أو كلمة المرور غير صحيحة.";
        }
    }
}

$pageTitle = "تسجيل الدخول";
include 'header.php';
include 'menu.php';
?>

<div class="login-wrapper">
    <div class="login-container">
        <h2>🌿 تسجيل الدخول</h2>

        <?php if ($success): ?>
        <div class="alert-success">
            ✅ أهلاً بك مجدداً في رواء! 🌿<br>
            <?php if (isAdmin()): ?>
            <a href="admin.php" style="color:var(--green-dark);font-weight:700;">الذهاب للوحة التحكم</a>
            <?php else: ?>
            <a href="index.php" style="color:var(--green-dark);font-weight:700;">العودة للرئيسية</a>
            <?php endif; ?>
        </div>
        <?php else: ?>

        <?php if ($error): ?>
        <div class="alert-error"><p>⚠️ <?php echo $error; ?></p></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="input-group">
                <label>البريد الإلكتروني</label>
                <input type="email" name="email"
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                       placeholder="example@mail.com">
            </div>
            <div class="input-group">
                <label>كلمة المرور</label>
                <input type="password" name="password" placeholder="••••••••">
            </div>
            <button type="submit" class="login-btn">تسجيل الدخول</button>
            <div class="footer-link">
                ليس لديك حساب؟ <a href="register.php">إنشاء حساب جديد</a>
            </div>
        </form>

        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
