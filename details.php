<?php
require_once __DIR__ . '/includes/functions.php';
$product = find_product((int) ($_GET['id'] ?? 0));
if (!$product) {
    http_response_code(404);
    $pageTitle = 'Produit introuvable - Mobile Hub';
    include __DIR__ . '/includes/header.php';
    echo '<section class="page-title"><h1>Produit introuvable</h1><p>Ce téléphone n’existe pas ou a été retiré.</p><a class="btn" href="shop.php">Retour boutique</a></section>';
    include __DIR__ . '/includes/footer.php';
    exit;
}
$pageTitle = $product['name'] . ' - Mobile Hub';
$pageDescription = 'Achetez ' . $product['name'] . ' au Bénin avec paiement à la livraison chez Mobile Hub.';
include __DIR__ . '/includes/header.php';
?>

<section class="detail-layout">
  <div class="detail-image">
    <img src="<?= e($product['image']) ?>" alt="<?= e($product['name']) ?>">
  </div>
  <div class="detail-info">
    <p class="eyebrow"><?= e($product['brand']) ?></p>
    <h1><?= e($product['name']) ?></h1>
    <div class="badges">
      <?php if (!empty($product['trending'])): ?><span class="badge hot">Tendance</span><?php endif; ?>
      <?php if (!empty($product['promo'])): ?><span class="badge promo">Promo</span><?php endif; ?>
    </div>
    <p class="detail-price"><?= format_price($product['price']) ?></p>
    <p><?= e($product['description']) ?></p>
    <div class="spec-grid">
      <?php foreach (product_specs($product) as $label => $value): ?>
        <div class="spec-item">
          <span><?= e($label) ?></span>
          <strong><?= e($value) ?></strong>
        </div>
      <?php endforeach; ?>
    </div>
    <p class="stock-line"><strong><?= (int) $product['stock'] ?></strong> unité(s) en stock</p>
    <div class="trust-row">
      <span>Paiement à la livraison</span>
      <span>Vérification avant paiement</span>
      <span>Confirmation par appel</span>
    </div>
    <div class="hero-actions">
      <a class="btn large" href="checkout.php?product=<?= (int) $product['id'] ?>">Commander maintenant</a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
