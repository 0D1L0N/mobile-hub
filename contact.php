<?php
$pageTitle = 'Contact - Mobile Hub';
$pageDescription = 'Contactez Mobile Hub pour acheter un téléphone Android au Bénin avec paiement à la livraison.';
include __DIR__ . '/includes/header.php';
?>

<section class="page-title">
  <p class="eyebrow">Contact</p>
  <h1>Parlez à Mobile Hub</h1>
  <p>Une question sur un téléphone, un stock ou une livraison ? Contactez-nous directement.</p>
</section>

<section class="contact-grid">
  <div class="contact-card">
    <h2>Commande rapide</h2>
    <p><?= e(CONTACT_PHONE) ?></p>
    <p>Disponible du lundi au samedi.</p>
  </div>
  <div class="contact-card">
    <h2>Livraison</h2>
    <p>Cotonou, Porto-Novo, Abomey-Calavi et autres villes sur confirmation.</p>
  </div>
  <div class="contact-card">
    <h2>Discussion</h2>
    <p>Écrivez-nous pour une question, un conseil ou une disponibilité.</p>
  </div>
</section>

<section class="section">
  <div class="section-heading">
    <div>
      <p class="eyebrow">Réseaux</p>
      <h2>Discuter avec Mobile Hub</h2>
    </div>
  </div>
  <div class="social-grid">
    <a class="social-card facebook" href="<?= e(CONTACT_FACEBOOK) ?>" target="_blank" rel="noopener">
      <span>f</span>
      <strong>Facebook</strong>
      <small>Voir la page</small>
    </a>
    <a class="social-card instagram" href="<?= e(CONTACT_INSTAGRAM) ?>" target="_blank" rel="noopener">
      <span>IG</span>
      <strong>Instagram</strong>
      <small>Suivre les nouveautés</small>
    </a>
    <a class="social-card whatsapp-chat" href="<?= e(CONTACT_WHATSAPP) ?>" target="_blank" rel="noopener">
      <span>WA</span>
      <strong>WhatsApp</strong>
      <small>Discuter seulement</small>
    </a>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
