<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Produits - Mobile Hub';
$pageDescription = 'Achetez des téléphones Samsung, Tecno, Infinix, Xiaomi et Oppo au Bénin avec paiement à la livraison.';
$brandFilter = $_GET['brand'] ?? '';
$search = trim($_GET['q'] ?? '');
$promoOnly = isset($_GET['promo']);
$inStockOnly = isset($_GET['stock']);
$minPrice = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (int) $_GET['min_price'] : 0;
$maxPrice = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (int) $_GET['max_price'] : 0;
$sort = $_GET['sort'] ?? 'recent';

$products = get_products();
if ($brandFilter !== '') {
    $products = array_values(array_filter($products, fn ($product) => strtolower($product['brand']) === strtolower($brandFilter)));
}
if ($search !== '') {
    $products = array_values(array_filter($products, fn ($product) => strpos(strtolower($product['name']), strtolower($search)) !== false));
}
if ($promoOnly) {
    $products = array_values(array_filter($products, fn ($product) => !empty($product['promo'])));
}
if ($inStockOnly) {
    $products = array_values(array_filter($products, fn ($product) => (int) $product['stock'] > 0));
}
if ($minPrice > 0) {
    $products = array_values(array_filter($products, fn ($product) => (int) $product['price'] >= $minPrice));
}
if ($maxPrice > 0) {
    $products = array_values(array_filter($products, fn ($product) => (int) $product['price'] <= $maxPrice));
}

usort($products, function ($a, $b) use ($sort) {
    return match ($sort) {
        'price_asc' => (int) $a['price'] <=> (int) $b['price'],
        'price_desc' => (int) $b['price'] <=> (int) $a['price'],
        'stock_desc' => (int) $b['stock'] <=> (int) $a['stock'],
        default => (int) $b['id'] <=> (int) $a['id'],
    };
});

include __DIR__ . '/includes/header.php';
?>

<section class="page-title">
  <p class="eyebrow">Boutique</p>
  <h1>Choisissez votre téléphone</h1>
  <p>Des modèles Android prêts à livrer, avec un tunnel de commande simple et sans paiement en ligne.</p>
</section>

<section class="shop-layout">
  <aside class="filters">
    <form method="get" action="shop.php" class="filter-form">
      <label for="q">Recherche</label>
      <input id="q" name="q" type="search" value="<?= e($search) ?>" placeholder="Ex: Samsung A15">
      <label for="brand">Marque</label>
      <select id="brand" name="brand">
        <option value="">Toutes les marques</option>
        <?php foreach (brands() as $brand): ?>
          <option value="<?= e($brand) ?>" <?= strtolower($brandFilter) === strtolower($brand) ? 'selected' : '' ?>><?= e($brand) ?></option>
        <?php endforeach; ?>
      </select>
      <label class="checkbox-row">
        <input type="checkbox" name="promo" value="1" <?= $promoOnly ? 'checked' : '' ?>>
        Promos seulement
      </label>
      <label class="checkbox-row">
        <input type="checkbox" name="stock" value="1" <?= $inStockOnly ? 'checked' : '' ?>>
        En stock seulement
      </label>
      <div class="filter-row">
        <div>
          <label for="min_price">Prix min.</label>
          <input id="min_price" name="min_price" type="number" min="0" value="<?= $minPrice ?: '' ?>" placeholder="0">
        </div>
        <div>
          <label for="max_price">Prix max.</label>
          <input id="max_price" name="max_price" type="number" min="0" value="<?= $maxPrice ?: '' ?>" placeholder="150000">
        </div>
      </div>
      <label for="sort">Trier par</label>
      <select id="sort" name="sort">
        <option value="recent" <?= $sort === 'recent' ? 'selected' : '' ?>>Plus récents</option>
        <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Moins chers</option>
        <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Plus chers</option>
        <option value="stock_desc" <?= $sort === 'stock_desc' ? 'selected' : '' ?>>Meilleur stock</option>
      </select>
      <button class="btn" type="submit">Filtrer</button>
      <a class="btn ghost" href="shop.php">Réinitialiser</a>
    </form>
  </aside>

  <div>
    <div class="result-count"><?= count($products) ?> produit(s) trouvé(s)</div>
    <div class="product-grid">
      <?php foreach ($products as $product): ?>
        <?php include __DIR__ . '/includes/product-card.php'; ?>
      <?php endforeach; ?>
    </div>
    <?php if (!$products): ?>
      <p class="empty-state">Aucun produit ne correspond à votre recherche.</p>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
