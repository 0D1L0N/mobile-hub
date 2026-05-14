<?php
require_once __DIR__ . '/includes/functions.php';
$sessionPath = __DIR__ . '/data/sessions';
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0775, true);
}
session_save_path($sessionPath);
session_start();

$loginError = '';
if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    redirect('admin.php');
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && ($_POST['action'] ?? '') === 'login') {
    if (hash_equals(ADMIN_PASSWORD, (string) ($_POST['password'] ?? ''))) {
        $_SESSION['admin_logged_in'] = true;
        redirect('admin.php');
    }
    $loginError = 'Mot de passe incorrect.';
}

$isLoggedIn = !empty($_SESSION['admin_logged_in']);
$pageTitle = 'Admin - Mobile Hub';
$pageDescription = 'Interface de gestion Mobile Hub.';

if (!$isLoggedIn) {
    include __DIR__ . '/includes/header.php';
    ?>
    <section class="login-screen">
      <form class="admin-form login-box" method="post" action="admin.php">
        <input type="hidden" name="action" value="login">
        <p class="eyebrow">Administration</p>
        <h1>Connexion</h1>
        <p>Entrez le mot de passe administrateur pour gérer la boutique.</p>
        <?php if ($loginError): ?><div class="notice error"><?= e($loginError) ?></div><?php endif; ?>
        <label for="password">Mot de passe</label>
        <input id="password" name="password" type="password" required autofocus>
        <button class="btn large" type="submit">Se connecter</button>
      </form>
    </section>
    <?php
    include __DIR__ . '/includes/footer.php';
    exit;
}

$products = get_products();
$editProduct = null;
$editId = (int) ($_GET['edit'] ?? 0);
if ($editId > 0) {
    $editProduct = find_product($editId);
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'save_product') {
        $id = (int) ($_POST['id'] ?? 0);
        $payload = [
            'id' => $id > 0 ? $id : next_product_id($products),
            'name' => trim($_POST['name'] ?? ''),
            'brand' => trim($_POST['brand'] ?? ''),
            'price' => (int) ($_POST['price'] ?? 0),
            'stock' => (int) ($_POST['stock'] ?? 0),
            'image' => trim($_POST['image'] ?? '') ?: 'product-image.php?id=' . ($id > 0 ? $id : next_product_id($products)),
            'description' => trim($_POST['description'] ?? ''),
            'memory' => trim($_POST['memory'] ?? ''),
            'ram' => trim($_POST['ram'] ?? ''),
            'battery' => trim($_POST['battery'] ?? ''),
            'camera' => trim($_POST['camera'] ?? ''),
            'warranty' => trim($_POST['warranty'] ?? '7 jours'),
            'trending' => isset($_POST['trending']),
            'promo' => isset($_POST['promo'])
        ];

        if ($id > 0) {
            foreach ($products as &$product) {
                if ((int) $product['id'] === $id) {
                    $product = $payload;
                    break;
                }
            }
            unset($product);
            save_products($products);
            redirect('admin.php?message=updated');
        }

        $products[] = $payload;
        save_products($products);
        redirect('admin.php?message=added');
    }

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $products = array_values(array_filter($products, fn ($product) => (int) $product['id'] !== $id));
        save_products($products);
        redirect('admin.php?message=deleted');
    }

    if ($action === 'order_status') {
        $orders = read_json_file(ORDERS_FILE);
        $orderId = (int) ($_POST['id'] ?? 0);
        $status = (string) ($_POST['status'] ?? 'Nouvelle');
        if (in_array($status, order_statuses(), true)) {
            foreach ($orders as &$order) {
                if ((int) ($order['id'] ?? 0) === $orderId) {
                    $order['status'] = $status;
                    break;
                }
            }
            unset($order);
            save_orders($orders);
        }
        redirect('admin.php?message=order');
    }
}

$messages = [
    'added' => 'Produit ajouté avec succès.',
    'updated' => 'Produit modifié avec succès.',
    'deleted' => 'Produit supprimé.',
    'order' => 'Statut de commande mis à jour.'
];
$message = $messages[$_GET['message'] ?? ''] ?? '';
$orders = get_orders();

$formProduct = $editProduct ?: [
    'id' => 0,
    'name' => '',
    'brand' => 'Samsung',
    'price' => '',
    'stock' => '',
    'image' => '',
    'description' => '',
    'memory' => '',
    'ram' => '',
    'battery' => '',
    'camera' => '',
    'warranty' => '7 jours',
    'trending' => false,
    'promo' => false
];

include __DIR__ . '/includes/header.php';
?>

<section class="page-title admin-title">
  <div>
    <p class="eyebrow">Administration</p>
    <h1>Gestion Mobile Hub</h1>
    <p>Gérez les produits, les stocks et les commandes avec paiement à la livraison.</p>
  </div>
  <a class="btn ghost" href="admin.php?logout=1">Déconnexion</a>
</section>

<?php if ($message): ?><div class="notice success admin-notice"><?= e($message) ?></div><?php endif; ?>

<section class="admin-grid">
  <form class="admin-form" method="post" action="admin.php">
    <input type="hidden" name="action" value="save_product">
    <input type="hidden" name="id" value="<?= (int) $formProduct['id'] ?>">
    <h2><?= $editProduct ? 'Modifier le produit' : 'Ajouter un produit' ?></h2>
    <?php if ($editProduct): ?><a class="btn ghost" href="admin.php">Nouveau produit</a><?php endif; ?>

    <label for="name">Nom</label>
    <input id="name" name="name" type="text" value="<?= e($formProduct['name']) ?>" required>

    <label for="brand">Marque</label>
    <select id="brand" name="brand" required>
      <?php foreach (brands() as $brand): ?>
        <option value="<?= e($brand) ?>" <?= $formProduct['brand'] === $brand ? 'selected' : '' ?>><?= e($brand) ?></option>
      <?php endforeach; ?>
    </select>

    <div class="filter-row">
      <div>
        <label for="price">Prix FCFA</label>
        <input id="price" name="price" type="number" min="0" value="<?= e($formProduct['price']) ?>" required>
      </div>
      <div>
        <label for="stock">Stock</label>
        <input id="stock" name="stock" type="number" min="0" value="<?= e($formProduct['stock']) ?>" required>
      </div>
    </div>

    <label for="image">Image</label>
    <input id="image" name="image" type="text" value="<?= e($formProduct['image']) ?>" placeholder="product-image.php?id=1 ou URL d’une photo">

    <label for="description">Description</label>
    <textarea id="description" name="description" rows="4" required><?= e($formProduct['description']) ?></textarea>

    <div class="filter-row">
      <div>
        <label for="memory">Mémoire</label>
        <input id="memory" name="memory" type="text" value="<?= e($formProduct['memory'] ?? '') ?>" placeholder="128 Go">
      </div>
      <div>
        <label for="ram">RAM</label>
        <input id="ram" name="ram" type="text" value="<?= e($formProduct['ram'] ?? '') ?>" placeholder="4 Go">
      </div>
    </div>

    <div class="filter-row">
      <div>
        <label for="battery">Batterie</label>
        <input id="battery" name="battery" type="text" value="<?= e($formProduct['battery'] ?? '') ?>" placeholder="5000 mAh">
      </div>
      <div>
        <label for="camera">Caméra</label>
        <input id="camera" name="camera" type="text" value="<?= e($formProduct['camera'] ?? '') ?>" placeholder="50 MP">
      </div>
    </div>

    <label for="warranty">Garantie</label>
    <input id="warranty" name="warranty" type="text" value="<?= e($formProduct['warranty'] ?? '7 jours') ?>">

    <label class="checkbox-row"><input type="checkbox" name="trending" <?= !empty($formProduct['trending']) ? 'checked' : '' ?>> Tendance</label>
    <label class="checkbox-row"><input type="checkbox" name="promo" <?= !empty($formProduct['promo']) ? 'checked' : '' ?>> Promo</label>
    <button class="btn" type="submit"><?= $editProduct ? 'Enregistrer les modifications' : 'Ajouter le produit' ?></button>
  </form>

  <div class="admin-panel">
    <h2>Produits</h2>
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>Produit</th><th>Marque</th><th>Prix</th><th>Stock</th><th>Badges</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php foreach ($products as $product): ?>
            <tr>
              <td><?= e($product['name']) ?></td>
              <td><?= e($product['brand']) ?></td>
              <td><?= format_price($product['price']) ?></td>
              <td><?= (int) $product['stock'] ?></td>
              <td>
                <?php if (!empty($product['trending'])): ?><span class="badge hot">Tendance</span><?php endif; ?>
                <?php if (!empty($product['promo'])): ?><span class="badge promo">Promo</span><?php endif; ?>
              </td>
              <td class="action-cell">
                <a class="btn small ghost" href="admin.php?edit=<?= (int) $product['id'] ?>">Modifier</a>
                <form method="post" action="admin.php" onsubmit="return confirm('Supprimer ce produit ?');">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
                  <button class="btn danger small" type="submit">Supprimer</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<section class="section">
  <div class="section-heading">
    <div>
      <p class="eyebrow">COD</p>
      <h2>Commandes à traiter</h2>
    </div>
  </div>
  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>Date</th><th>Client</th><th>Téléphone</th><th>Adresse</th><th>Produit</th><th>Total</th><th>Contact</th><th>Statut</th></tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $order): ?>
          <tr>
            <td><?= e($order['date']) ?></td>
            <td><?= e($order['name']) ?></td>
            <td><?= e($order['phone']) ?></td>
            <td><?= e(($order['city'] ?? '') . ' - ' . ($order['address'] ?? '')) ?></td>
            <td><?= e($order['product']) ?></td>
            <td><?= format_price($order['price'] ?? 0) ?></td>
            <td class="action-cell">
              <a class="btn small" href="tel:<?= e(normalize_phone((string) $order['phone'])) ?>">Appeler</a>
              <a class="btn small ghost" href="<?= e(customer_whatsapp_link($order)) ?>" target="_blank" rel="noopener">WhatsApp</a>
            </td>
            <td>
              <form class="inline-form" method="post" action="admin.php">
                <input type="hidden" name="action" value="order_status">
                <input type="hidden" name="id" value="<?= (int) ($order['id'] ?? 0) ?>">
                <select name="status">
                  <?php foreach (order_statuses() as $status): ?>
                    <option value="<?= e($status) ?>" <?= ($order['status'] ?? 'Nouvelle') === $status ? 'selected' : '' ?>><?= e($status) ?></option>
                  <?php endforeach; ?>
                </select>
                <button class="btn small" type="submit">OK</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php if (!$orders): ?><p class="empty-state">Aucune commande pour le moment.</p><?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
