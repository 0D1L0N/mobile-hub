<?php
require_once __DIR__ . '/includes/functions.php';
$brandName = $brandName ?? ($_GET['brand'] ?? 'Samsung');
$pageTitle = $brandName . ' - Mobile Hub';
$pageDescription = 'Achetez des téléphones ' . $brandName . ' au Bénin avec paiement à la livraison.';
$products = products_by_brand($brandName);
include __DIR__ . '/includes/header.php';
?>

<section class="page-title brand-hero">
  <p class="eyebrow">Marque</p>
  <h1><?= e($brandName) ?></h1>
  <p>Sélection de téléphones <?= e($brandName) ?> disponibles avec paiement à la livraison.</p>
</section>

<section class="section">
  <div class="section-heading">
    <div>
      <p class="eyebrow">Catalogue</p>
      <h2><?= count($products) ?> modèle(s) disponible(s)</h2>
    </div>
    <a href="shop.php">Toutes les marques</a>
  </div>
  <div class="product-grid">
    <?php foreach ($products as $product): ?>
      <?php include __DIR__ . '/includes/product-card.php'; ?>
    <?php endforeach; ?>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
