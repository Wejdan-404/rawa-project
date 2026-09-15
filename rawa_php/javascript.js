// رواء - JavaScript مساعد
// الحجز الآن يتم عبر PHP مباشرة

// دالة البحث (مستخدمة في menu.php)
function goSearch() {
    const val = document.getElementById('plantsSearch').value.trim();
    if (val) window.location.href = 'index.php?search=' + encodeURIComponent(val);
}
