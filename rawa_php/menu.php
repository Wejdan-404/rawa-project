<?php
// menu.php — شريط التنقل العلوي
require_once __DIR__ . '/connection.php';
?>
<nav class="top-nav">
    <ul>
        <div class="nav-right">
            <li><a href="index.php">الرئيسية</a></li>
            <li><a href="about.php">من نحن</a></li>
            <li><a href="myplant.php">نباتاتي</a><li>
                <li><a href="ather.php">اثر رواء</a><li>

            <?php if (isAdmin()): ?>
            <li><a href="admin.php" style="color:var(--green-dark);font-weight:700;">⚙️ لوحة التحكم</a></li>
            <?php endif; ?>
        </div>

        <div class="nav-search">
            <div class="nav-search-inner">
                <input type="text" id="plantsSearch" placeholder="ابحث عن نباتك القادم..."
                       list="plants-list" onkeydown="if(event.key==='Enter') goSearch()">
                <datalist id="plants-list">
                    <?php
                    $sq = "SELECT id, title FROM offer ORDER BY title";
                    $rs = mysqli_query($conn, $sq);
                    while ($row = mysqli_fetch_array($rs)) {
                        echo "<option value=\"" . htmlspecialchars($row['title']) . "\" data-id=\"" . $row['id'] . "\"></option>";
                    }
                    ?>
                </datalist>
                <button onclick="goSearch()">🔍</button>
            </div>
        </div>

        <div class="nav-left">
            <li><a href="support.php">الدعم</a></li>
            <?php if (isLoggedIn()): ?>
            <li>
                <a href="logout.php" class="nav-logout-btn">
                    خروج (<?php echo htmlspecialchars($_SESSION['first_name']); ?>)
                </a>
            </li>
            <?php else: ?>
            <li><a href="login.php">تسجيل الدخول</a></li>
            <li><a href="register.php">إنشاء حساب</a></li>
            <?php endif; ?>
        </div>
    </ul>
</nav>

<script>
function goSearch() {
    const val = document.getElementById('plantsSearch').value.trim();
    if (val) window.location.href = 'index.php?search=' + encodeURIComponent(val);
}
</script>
