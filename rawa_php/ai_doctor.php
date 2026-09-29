<?php
include('connection.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('header.php');

$result = null;
$error = null;

// ضعي مفتاح Gemini API الخاص بكِ هنا
$gemini_api_key = "AQ.Ab8RN6LAxKHzlBzxIYaFr85J3bE24ya4Skjm6v3c9Sm2icARxA";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['plant_image'])) {
    $file = $_FILES['plant_image'];
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (in_array($file_extension, $allowed_extensions)) {
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        $new_filename = 'plant_doc_' . time() . '_' . rand(100, 999) . '.' . $file_extension;
        $target_path = 'uploads/' . $new_filename;

        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            $raw_file = file_get_contents($target_path);
            $image_data = base64_encode($raw_file);
            $mime_type = ($file_extension === 'png') ? 'image/png' : 'image/jpeg';

            $prompt = "أنت خبير زراعي متخصص في تشخيص أمراض النباتات. افحص صورة ورقة النبتة وقدم تشخيصاً دقيقاً باللغة العربية حصراً بصيغة JSON بدون أي علامات markdown:
            {
              \"status\": \"سليمة وصحية 🌱 أو مصابة أو تحتاج عناية\",
              \"disease\": \"اسم المشكلة أو 'لا توجد إصابة'\",
              \"treatment\": \"نصائح العلاج وخطة العناية المقترحة للنبتة بشكل عملي ومفصل\"
            }";

            // استخدام النموذج المعتمد: gemini-2.5-flash
            $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent?key=" . trim($gemini_api_key);

            $payload = [
                "contents" => [
                    [
                        "parts" => [
                            ["text" => $prompt],
                            [
                                "inline_data" => [
                                    "mime_type" => $mime_type,
                                    "data" => $image_data
                                ]
                            ]
                        ]
                    ]
                ],
                "generationConfig" => [
                    "response_mime_type" => "application/json"
                ]
            ];

            $ch = curl_init($endpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code === 200 && $response) {
                $res_json = json_decode($response, true);
                $ai_raw_text = $res_json['candidates'][0]['content']['parts'][0]['text'] ?? '';
                $diagnosis_data = json_decode($ai_raw_text, true);

                if ($diagnosis_data) {
                    $result = [
                        'image' => $target_path,
                        'status' => $diagnosis_data['status'] ?? 'تم الفحص',
                        'disease' => $diagnosis_data['disease'] ?? 'تشخيص تلقائي',
                        'treatment' => $diagnosis_data['treatment'] ?? 'يرجى مراجعة المرشد الزراعي'
                    ];

                    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
                    $stmt = $conn->prepare("INSERT INTO plant_doctor_logs (user_id, image_path, diagnosis_status, disease_name, treatment) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("issss", $user_id, $target_path, $result['status'], $result['disease'], $result['treatment']);
                    $stmt->execute();
                } else {
                    $error = "تعذر تحليل النتيجة، يرجى المحاولة بصورة أوضح للورقة.";
                }
            } else {
                $error = "رمز الخطأ ($http_code): " . htmlspecialchars($response);
            }
        } else {
            $error = "تعذر رفع الصورة، تأكد من صلاحيات المجلد.";
        }
    } else {
        $error = "يرجى اختيار صورة بصيغة JPG أو PNG فقط.";
    }
}
?>

<style>
.doctor-outer {
    max-width: 600px;
    margin: 40px auto 60px;
    padding: 0 15px;
    direction: rtl;
    text-align: center;
    font-family: 'Tajawal', sans-serif;
}
.doctor-title {
    font-size: 26px;
    font-weight: 700;
    color: #2d5a27;
    margin-bottom: 6px;
}
.doctor-sub {
    font-size: 14px;
    color: #6a7a6a;
    margin-bottom: 24px;
}
.doctor-box {
    background: #ffffff;
    border: 1.5px solid #d4e5d2;
    border-radius: 18px;
    box-shadow: 0 6px 20px rgba(45, 90, 39, 0.08);
    padding: 30px 24px;
}
.custom-dropzone {
    border: 2px dashed #3a7d34;
    background-color: #f5f9f4;
    border-radius: 12px;
    padding: 26px 15px;
    cursor: pointer;
    display: block;
    margin-bottom: 18px;
    transition: 0.2s;
}
.custom-dropzone:hover {
    background-color: #e9f5e8;
}
.drop-icon {
    font-size: 38px;
    margin-bottom: 8px;
    display: block;
}
.drop-text {
    font-size: 15px;
    font-weight: 700;
    color: #2d5a27;
}
.drop-hint {
    font-size: 12px;
    color: #777;
    margin-top: 3px;
}
.file-name-label {
    margin-top: 10px;
    font-size: 13px;
    font-weight: 600;
    color: #3a7d34;
    display: none;
}
.btn-submit-action {
    width: 100%;
    background-color: #3a7d34;
    color: #ffffff;
    border: none;
    padding: 13px;
    border-radius: 50px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    font-family: 'Tajawal', sans-serif;
    transition: 0.2s;
}
.btn-submit-action:hover {
    background-color: #1f4020;
}
.result-container {
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1.5px solid #d4e5d2;
}
.res-img {
    max-width: 200px;
    border-radius: 12px;
    border: 1px solid #d4e5d2;
    margin-bottom: 12px;
}
.badge-state {
    display: inline-block;
    padding: 5px 18px;
    border-radius: 50px;
    font-size: 15px;
    font-weight: 700;
    margin-bottom: 15px;
}
.badge-green { background: #e8f5e9; color: #27ae60; border: 1px solid #a5d6a7; }
.badge-red   { background: #fff3f3; color: #c0392b; border: 1px solid #e57373; }

.info-details {
    background: #f5f9f4;
    border: 1px solid #d4e5d2;
    border-right: 4px solid #3a7d34;
    border-radius: 10px;
    padding: 16px;
    text-align: right;
    line-height: 1.7;
    font-size: 13.5px;
}
.info-details p { margin: 0 0 6px 0; }
.info-details p:last-child { margin: 0; }
.error-msg-box {
    background: #ffebee;
    color: #c62828;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 16px;
    font-size: 13px;
    text-align: right;
    direction: ltr;
    word-break: break-all;
}
</style>

<div class="doctor-outer">
    <div class="doctor-title">🩺 طبيب النباتات بالذكاء الاصطناعي</div>
    <div class="doctor-sub">ارفع صورة لورقة النبتة لفحصها وتشخيص حالتها واقتراح العلاج المناسب فوراً.</div>

    <?php if ($error): ?>
        <div class="error-msg-box">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <div class="doctor-box">
        <form method="POST" enctype="multipart/form-data">
            <label class="custom-dropzone" for="plant_img_input">
                <span class="drop-icon">🌿</span>
                <div class="drop-text">اضغط هنا لاختيار صورة الورقة</div>
                <div class="drop-hint">الصيغ المدعومة: JPG, PNG, WEBP</div>
                <div id="file_selected_text" class="file-name-label"></div>
                <input type="file" id="plant_img_input" name="plant_image" accept="image/*" required style="display:none;" onchange="fileChosen(this)">
            </label>

            <button type="submit" class="btn-submit-action">
                🔍 ابدأ الفحص والتشخيص الآن
            </button>
        </form>

        <?php if ($result): ?>
            <div class="result-container">
                <img src="<?= htmlspecialchars($result['image']) ?>" alt="صورة النبتة" class="res-img">
                <br>
                <div class="badge-state <?= (strpos($result['status'], 'سليم') !== false) ? 'badge-green' : 'badge-red' ?>">
                    الحالة: <?= htmlspecialchars($result['status']) ?>
                </div>

                <div class="info-details">
                    <p><strong>المشكلة أو التشخيص:</strong> <?= htmlspecialchars($result['disease']) ?></p>
                    <p><strong>خطة العلاج المقترحة:</strong><br><?= nl2br(htmlspecialchars($result['treatment'])) ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function fileChosen(input) {
    var txt = document.getElementById('file_selected_text');
    if (input.files && input.files[0]) {
        txt.style.display = 'block';
        txt.textContent = '📄 تم اختيار: ' + input.files[0].name;
    }
}
</script>

<?php include('footer.php'); ?>
