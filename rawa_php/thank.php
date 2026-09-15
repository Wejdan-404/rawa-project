<?php
// thank.php — صفحة شكراً بعد التبني (صفحة مستقلة كاملة)
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم التبني بنجاح | رواء</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2d6a4f;
            --secondary-color: #74c69d;
            --bg-color: #f8f9fa;
            --white: #ffffff;
            --accent-color: #ffb703;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
            padding: 20px;
        }

        .thank-you-card {
            background-color: var(--white);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            text-align: center;
            border-top: 8px solid var(--primary-color);
        }

        .icon-container {
            font-size: 60px;
            color: var(--secondary-color);
            margin-bottom: 20px;
        }

        h1 {
            color: var(--primary-color);
            margin-bottom: 10px;
            font-size: 24px;
        }

        .message {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .location-box {
            background-color: #f0f7ff;
            border: 1px dashed #2d6a4f;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 25px;
            text-align: right;
        }

        .location-box h3 {
            margin-top: 0;
            font-size: 1.1rem;
        }

        .location-box p {
            margin: 5px 0;
            font-size: 0.95rem;
        }

        .care-notice {
            background-color: #e9f5ee;
            padding: 15px;
            border-radius: 10px;
            font-weight: bold;
            color: #1b4332;
            margin-bottom: 30px;
            line-height: 1.5;
        }

        .hadith-box {
            border-right: 4px solid var(--accent-color);
            padding: 20px;
            background-color: #fffcf2;
            font-style: italic;
            margin-bottom: 30px;
            text-align: right;
        }

        .hadith-title {
            display: block;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 8px;
            font-style: normal;
        }

        .btn-home {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 12px 35px;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s ease;
            display: inline-block;
            font-weight: bold;
        }

        .btn-home:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        footer {
            margin-top: 20px;
            font-size: 0.9rem;
            color: #888;
        }
    </style>
</head>
<body>

    <div class="thank-you-card">
        <div class="icon-container">🌿</div>

        <h1>شكراً لتبنيكم!</h1>
        <p class="message">لقد ساهمتم للتو في جعل العالم مكاناً أكثر اخضراراً بتبنيكم صديقاً جديداً للطبيعة.</p>

        <div class="location-box">
            <h3 style="color: #2d6a4f;">📍 موقع استلام النبتة</h3>
            <p><strong>المدينة:</strong> الطائف</p>
            <p><strong>الوصف:</strong> حي الهدا، بالقرب من منتزه النقبة الحمراء، "مشتل رواء" - المبنى الأخضر.</p>
        </div>

        <div class="care-notice">
            🌱 الرجاء الحرص على رعاية النبتة: <br>
            تذكروا أنها كائن حي يحتاج لاهتمامكم بالري، الإضاءة، والكثير من المحبة.
        </div>

        <div class="hadith-box">
            <span class="hadith-title">قال رسول الله ﷺ:</span>
            "ما من مسلم يغرس غرساً، أو يزرع زرعاً، فيأكل منه طير أو إنسان أو بهيمة، إلا كان له به صدقة"
        </div>

        <a href="index.php" class="btn-home">العودة للرئيسية</a>

        <footer>شكراً لكونكم جزءاً من <b>مشروع رواء</b></footer>
    </div>

</body>
</html>
