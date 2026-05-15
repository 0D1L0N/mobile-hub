<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Marques - Mobile Hub';
$pageDescription = 'Choisissez une marque de téléphone Android disponible chez Mobile Hub au Bénin.';
include __DIR__ . '/includes/header.php';
?>

<section class="page-title">
  <p class="eyebrow">Marques</p>
  <h1>Choisir une marque</h1>
  <p>Parcourez les marques Android les plus pertinentes pour un stock local au Bénin.</p>
</section>

<section class="section">
  <div class="brand-grid">
    <?php foreach (brands() as $brand): ?>
      <a class="brand-card" href="<?= e(brand_slug($brand)) ?>">
        <span><?= e(substr($brand, 0, 1)) ?></span>
        <strong><?= e($brand) ?></strong>
        <small><?= count(products_by_brand($brand)) ?> modèle(s)</small>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
