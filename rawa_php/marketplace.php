<?php
$pageTitle = "متجر مستلزمات العناية";
require_once 'header.php';
?>

<style>
    .marketplace-page {
        max-width: 1100px;
        margin: 0 auto;
        padding: 45px 20px 60px;
    }

    .marketplace-intro {
        text-align: center;
        margin-bottom: 30px;
    }

    .marketplace-intro h1 {
        color: #2d5a27;
        margin-bottom: 8px;
        font-size: 32px;
    }

    .marketplace-intro p {
        color: #6a7a6a;
        font-size: 16px;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 22px;
    }

    .product-card {
        background: #fff;
        border: 1px solid #d4e5d2;
        border-radius: 14px;
        padding: 24px;
        text-align: center;
        box-shadow: 0 4px 16px rgba(45,90,39,0.10);
        transition: transform .2s, box-shadow .2s;
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(45,90,39,0.16);
    }

    .product-icon {
        width: 105px;
        height: 105px;
        margin: 0 auto 15px;
        border-radius: 50%;
        background: #e9f5e8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 52px;
    }

    .product-card h2 {
        color: #1e2e1e;
        font-size: 20px;
        margin-bottom: 8px;
    }

    .product-card p {
        color: #6a7a6a;
        font-size: 14px;
        min-height: 48px;
        margin-bottom: 10px;
    }

    .product-price {
        color: #3a7d34;
        font-weight: 700;
        font-size: 18px;
        margin-bottom: 15px;
    }

    .buy-button {
        display: inline-block;
        text-decoration: none;
        background: #3a7d34;
        color: #fff;
        padding: 9px 24px;
        border-radius: 50px;
        font-weight: 500;
        transition: background .2s;
    }

    .buy-button:hover {
        background: #1f4020;
        color: #fff;
    }

    .affiliate-note {
        text-align: center;
        margin-top: 35px;
        padding: 15px 20px;
        background: #e9f5e8;
        border-radius: 14px;
        color: #3a4a3a;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .products-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<main class="marketplace-page">
    <section class="marketplace-intro">
        <h1>🛒 متجر مستلزمات العناية 🌿</h1>
        <p>كل ما تحتاجه للعناية بنباتاتك في مكان واحد</p>
    </section>

    <section class="products-grid">
        <article class="product-card">
            <div class="product-icon">🪴</div>
            <h2>أصيص للنباتات</h2>
            <p>أصيص عملي وأنيق مناسب للنباتات المنزلية والداخلية.</p>
            <div class="product-price">35 ريال</div>
            <a class="buy-button" href="https://www.google.com/search?q=أصيص+نباتات" target="_blank" rel="noopener">شراء المنتج</a>
        </article>

        <article class="product-card">
            <div class="product-icon">🌱</div>
            <h2>تربة زراعية</h2>
            <p>تربة مناسبة للنباتات المنزلية تساعد على نمو صحي وتصريف جيد.</p>
            <div class="product-price">25 ريال</div>
            <a class="buy-button" href="https://www.google.com/search?q=تربة+زراعية+للنباتات" target="_blank" rel="noopener">شراء المنتج</a>
        </article>

        <article class="product-card">
            <div class="product-icon">💧</div>
            <h2>سماد للنباتات</h2>
            <p>سماد يساعد على دعم نمو النباتات والمحافظة على حيويتها.</p>
            <div class="product-price">18 ريال</div>
            <a class="buy-button" href="https://www.google.com/search?q=سماد+للنباتات" target="_blank" rel="noopener">شراء المنتج</a>
        </article>

        <article class="product-card">
            <div class="product-icon">🪴</div>
            <h2>أصيص كبير</h2>
            <p>خيار مناسب للنباتات التي تحتاج مساحة أكبر للجذور.</p>
            <div class="product-price">55 ريال</div>
            <a class="buy-button" href="https://www.google.com/search?q=أصيص+كبير+للنباتات" target="_blank" rel="noopener">شراء المنتج</a>
        </article>

        <article class="product-card">
            <div class="product-icon">🌿</div>
            <h2>تربة للنباتات الداخلية</h2>
            <p>خلطة مناسبة للنباتات الداخلية مثل البوتس والمونستيرا.</p>
            <div class="product-price">30 ريال</div>
            <a class="buy-button" href="https://www.google.com/search?q=تربة+نباتات+داخلية" target="_blank" rel="noopener">شراء المنتج</a>
        </article>

        <article class="product-card">
            <div class="product-icon">💚</div>
            <h2>سماد سائل</h2>
            <p>سماد سائل سهل الاستخدام للعناية الدورية بالنباتات.</p>
            <div class="product-price">22 ريال</div>
            <a class="buy-button" href="https://www.google.com/search?q=سماد+سائل+للنباتات" target="_blank" rel="noopener">شراء المنتج</a>
        </article>
    </section>

    <div class="affiliate-note">
        💡 المنتجات المعروضة نموذج لفكرة المتجر، ويمكن استبدال روابط الشراء بروابط المشاتل الشريكة وروابط الـ Affiliate الفعلية.
    </div>
</main>

<?php require_once 'footer.php'; ?>
