<?php
$productUrl = 'details.php?id=' . (int) $product['id'];
$checkoutUrl = 'checkout.php?product=' . (int) $product['id'];
?>
<article class="product-card">
  <a class="product-image" href="<?= e($productUrl) ?>">
    <img src="<?= e($product['image']) ?>" alt="<?= e($product['name']) ?>">
    <span class="stock-pill"><?= (int) $product['stock'] ?> en stock</span>
  </a>
  <div class="product-body">
    <div class="badges">
      <?php if (!empty($product['trending'])): ?><span class="badge hot">Tendance</span><?php endif; ?>
      <?php if (!empty($product['promo'])): ?><span class="badge promo">Promo</span><?php endif; ?>
    </div>
    <p class="brand"><?= e($product['brand']) ?></p>
    <h3><a href="<?= e($productUrl) ?>"><?= e($product['name']) ?></a></h3>
    <p class="price"><?= format_price($product['price']) ?></p>
    <a class="btn order-btn" href="<?= e($checkoutUrl) ?>">Commander</a>
    <div class="card-actions">
      <a class="btn ghost" href="<?= e($productUrl) ?>">Voir les détails</a>
    </div>
  </div>
</article>
