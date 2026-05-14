<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Mobile Hub - Téléphones Android au Bénin';
$pageDescription = 'Commandez des téléphones Android au Bénin avec paiement à la livraison. Samsung, Tecno, Infinix, Xiaomi et Oppo disponibles.';
$products = get_products();
$trending = array_slice(array_values(array_filter($products, fn ($product) => !empty($product['trending']))), 0, 9);
$recent = array_slice(array_reverse($products), 0, 6);
$featuredBrands = array_slice(brands(), 0, 12);
include __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <div class="hero-content">
    <p class="eyebrow">Paiement à la livraison au Bénin</p>
    <h1>Votre nouveau téléphone, livré sans stress.</h1>
    <p>Choisissez un modèle, laissez vos coordonnées, puis Mobile Hub vous appelle pour confirmer la livraison. Simple, rapide, sans paiement en ligne.</p>
    <div class="hero-actions">
      <a class="btn large" href="shop.php">Acheter maintenant</a>
      <a class="btn ghost large" href="#marques">Voir les marques</a>
    </div>
    <div class="hero-stats">
      <div><strong><?= count($products) ?>+</strong><span>modèles prêts</span></div>
      <div><strong>COD</strong><span>paiement livraison</span></div>
      <div><strong>24h</strong><span>rappel rapide</span></div>
    </div>
  </div>
  <div class="hero-panel">
    <?php $heroProduct = $trending[0] ?? $products[0] ?? null; ?>
    <?php if ($heroProduct): ?>
      <img src="<?= e($heroProduct['image']) ?>" alt="<?= e($heroProduct['name']) ?>">
      <span class="badge hot">Tendance</span>
      <h2><?= e($heroProduct['name']) ?></h2>
      <p>Stock disponible, confirmation par appel et paiement à la livraison.</p>
      <strong><?= format_price($heroProduct['price']) ?></strong>
      <a class="btn order-btn" href="checkout.php?product=<?= (int) $heroProduct['id'] ?>">Commander</a>
    <?php endif; ?>
  </div>
</section>

<section class="trust-strip" aria-label="Avantages Mobile Hub">
  <div>
    <strong>Paiement à la livraison</strong>
    <span>Aucun paiement en ligne avant réception.</span>
  </div>
  <div>
    <strong>Confirmation par appel</strong>
    <span>Nous validons le modèle et l’adresse.</span>
  </div>
  <div>
    <strong>Stock clair</strong>
    <span>Les quantités sont visibles sur chaque produit.</span>
  </div>
</section>

<section class="section">
  <div class="section-heading">
    <div>
      <p class="eyebrow">Populaire</p>
      <h2>Téléphones en vogue</h2>
    </div>
    <a href="shop.php">Tout voir</a>
  </div>
  <div class="product-grid">
    <?php foreach ($trending as $product): ?>
      <?php include __DIR__ . '/includes/product-card.php'; ?>
    <?php endforeach; ?>
  </div>
</section>

<section class="section" id="marques">
  <div class="section-heading">
    <div>
      <p class="eyebrow">Marques</p>
      <h2>Acheter par marque</h2>
    </div>
  </div>
  <div class="brand-grid">
    <?php foreach ($featuredBrands as $brand): ?>
      <a class="brand-card" href="<?= e(brand_slug($brand)) ?>">
        <span><?= e(substr($brand, 0, 1)) ?></span>
        <strong><?= e($brand) ?></strong>
        <small><?= count(products_by_brand($brand)) ?> modèles</small>
      </a>
    <?php endforeach; ?>
  </div>
  <div class="section-more">
    <a class="btn ghost" href="marques.php">Voir toutes les marques</a>
  </div>
</section>

<section class="promo-strip">
  <div>
    <p class="eyebrow">Promo</p>
    <h2>Réduction spéciale sur les modèles sélectionnés</h2>
    <p>Repérez le badge Promo et confirmez votre commande sans paiement en ligne.</p>
  </div>
  <a class="btn" href="shop.php?promo=1">Voir les promos</a>
</section>

<section class="section">
  <div class="section-heading">
    <div>
      <p class="eyebrow">Nouveautés</p>
      <h2>Produits récents</h2>
    </div>
  </div>
  <div class="product-grid">
    <?php foreach ($recent as $product): ?>
      <?php include __DIR__ . '/includes/product-card.php'; ?>
    <?php endforeach; ?>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
