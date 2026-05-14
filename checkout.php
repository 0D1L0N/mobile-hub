<?php
require_once __DIR__ . '/includes/functions.php';
$products = get_products();
$selectedId = (int) ($_GET['product'] ?? $_POST['product_id'] ?? 0);
$selectedProduct = $selectedId ? find_product($selectedId) : null;
$success = false;
$error = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $productId = (int) ($_POST['product_id'] ?? 0);
    $selectedProduct = find_product($productId);

    if ($name === '' || $phone === '' || $city === '' || $address === '' || !$selectedProduct) {
        $error = 'Veuillez remplir tous les champs et choisir un produit.';
    } elseif ((int) $selectedProduct['stock'] <= 0) {
        $error = 'Ce produit est actuellement en rupture de stock.';
    } else {
        save_order([
            'name' => $name,
            'phone' => $phone,
            'city' => $city,
            'address' => $address,
            'product' => $selectedProduct['name'],
            'product_id' => $productId,
            'price' => $selectedProduct['price'],
            'date' => date('Y-m-d H:i:s'),
            'status' => 'Nouvelle'
        ]);

        foreach ($products as &$product) {
            if ((int) $product['id'] === $productId) {
                $product['stock'] = max(0, (int) $product['stock'] - 1);
                break;
            }
        }
        unset($product);
        save_products($products);
        $success = true;
    }
}

$pageTitle = 'Commande COD - Mobile Hub';
$pageDescription = 'Passez une commande Mobile Hub avec paiement à la livraison au Bénin.';
include __DIR__ . '/includes/header.php';
?>

<section class="page-title">
  <p class="eyebrow">Commande sécurisée</p>
  <h1>Finalisez votre commande</h1>
  <p>Vous ne payez rien maintenant. Laissez vos coordonnées, nous vous contactons pour confirmer le produit et organiser la livraison.</p>
</section>

<section class="checkout-layout">
  <form class="checkout-form" method="post" action="checkout.php">
    <?php if ($success): ?>
      <div class="notice success">Commande enregistrée. Nous vous contacterons rapidement pour confirmer la livraison.</div>
    <?php elseif ($error): ?>
      <div class="notice error"><?= e($error) ?></div>
    <?php endif; ?>

    <label for="name">Nom complet</label>
    <input id="name" name="name" type="text" required>

    <label for="phone">Numéro de téléphone</label>
    <input id="phone" name="phone" type="tel" required placeholder="+229...">

    <label for="city">Ville</label>
    <input id="city" name="city" type="text" required placeholder="Cotonou">

    <label for="address">Adresse complète</label>
    <textarea id="address" name="address" rows="4" required></textarea>

    <label for="product_id">Produit sélectionné</label>
    <select id="product_id" name="product_id" required>
      <option value="">Choisir un produit</option>
      <?php foreach ($products as $product): ?>
        <option value="<?= (int) $product['id'] ?>" <?= $selectedProduct && (int) $selectedProduct['id'] === (int) $product['id'] ? 'selected' : '' ?>>
          <?= e($product['name']) ?> - <?= format_price($product['price']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <button class="btn large" type="submit">Confirmer la commande</button>
  </form>

  <aside class="order-summary">
    <p class="eyebrow">Résumé</p>
    <h2>Votre commande</h2>
    <?php if ($selectedProduct): ?>
      <img src="<?= e($selectedProduct['image']) ?>" alt="<?= e($selectedProduct['name']) ?>">
      <h3><?= e($selectedProduct['name']) ?></h3>
      <p><?= format_price($selectedProduct['price']) ?></p>
      <span class="stock-pill"><?= (int) $selectedProduct['stock'] ?> en stock</span>
    <?php else: ?>
      <p>Choisissez un produit pour voir le résumé de commande.</p>
    <?php endif; ?>
    <div class="mini-trust">
      <p><strong>Aucun paiement en ligne.</strong></p>
      <p>Après confirmation, Mobile Hub vous appelle pour convenir du lieu et de l’heure de livraison.</p>
    </div>
    <div class="checkout-steps">
      <div><strong>1</strong><span>Vous confirmez</span></div>
      <div><strong>2</strong><span>Nous appelons</span></div>
      <div><strong>3</strong><span>Vous payez à la livraison</span></div>
    </div>
  </aside>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
