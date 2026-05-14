<?php
require_once __DIR__ . '/functions.php';
$pageTitle = $pageTitle ?? 'Mobile Hub';
$pageDescription = $pageDescription ?? 'Mobile Hub vend des téléphones Android au Bénin avec paiement à la livraison.';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?= e($pageDescription) ?>">
  <title><?= e($pageTitle) ?></title>
  <link rel="icon" href="assets/img/favicon.svg" type="image/svg+xml">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <header class="site-header">
    <a class="logo" href="index.php" aria-label="Accueil Mobile Hub">
      <img src="assets/img/mobile-hub-logo.svg" alt="Mobile Hub">
    </a>
    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="main-nav">Menu</button>
    <nav class="main-nav" id="main-nav">
      <a href="index.php">Accueil</a>
      <a href="shop.php">Produits</a>
      <a href="marques.php">Marques</a>
      <a href="contact.php">Contact</a>
      <a class="admin-link" href="admin.php">Admin</a>
    </nav>
  </header>
  <main>
