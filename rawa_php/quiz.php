<?php 
include 'connection.php';
include 'header.php';
?>
<style>
.quiz-container {
    max-width: 650px;
    margin: 50px auto;
    background: #ffffff;
    padding: 35px;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    border: 1px solid #e0e0e0;
    direction: rtl;
}
.quiz-container h2 {
    color: #2e7d32;
    text-align: center;
    margin-bottom: 10px;
}
.quiz-container p.subtitle {
    text-align: center;
    color: #666;
    margin-bottom: 30px;
}
.quiz-group {
    margin-bottom: 25px;
}
.quiz-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
}
.quiz-group select {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    background-color: #f9f9f9;
    outline: none;
}
.quiz-btn {
    width: 100%;
    background-color: #2e7d32;
    color: #ffffff;
    padding: 14px;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
}
</style>
<div class="quiz-container">
    <h2>استبيان توصية النبات المناسب 🌿</h2>
    <p class="subtitle">إجابتك على هذه الأسئلة ستساعدنا في ترشيح أفضل النباتات لبيئتك</p>
    
    <form action="recommendation.php" method="POST">
        <div class="quiz-group">
            <label>1. أين ترغب في وضع النبات؟</label>
            <select name="location_type" required>
                <option value="" disabled selected>اختر المكان...</option>
                <option value="indoor">داخلي (داخل المنزل/المكتب)</option>
                <option value="outdoor">خارجي (في الحديقة/الشرفة)</option>
            </select>
        </div>
        <div class="quiz-group">
            <label>2. ما هو مستوى تحمل الحرارة المطلوب؟</label>
            <select name="temp_tolerance" required>
                <option value="" disabled selected>اختر المستوى...</option>
                <option value="low">منخفض (بيئة مكيّفة/معتدلة)</option>
                <option value="medium">متوسط (تحمل معتدل)</option>
                <option value="high">عالي (تحمل درجات حرارة عالية/شمس)</option>
            </select>
        </div>
        <div class="quiz-group">
            <label>3. ما هو جدول الري المناسب لك؟</label>
            <select name="water_freq" required>
                <option value="" disabled selected>اختر تكرار الري...</option>
                <option value="low">قليل (ري كل أسبوعين أو أكثر)</option>
                <option value="medium">متوسط (ري أسبوعي)</option>
                <option value="high">مكثف (ري متكرر)</option>
            </select>
        </div>
        <button type="submit" name="get_recommendation" class="quiz-btn">عرض الترشيحات المناسبة 🔍</button>
    </form>
</div>
<?php include 'footer.php'; ?>