<?php
require_once __DIR__ . '/includes/functions.php';

$product = find_product((int) ($_GET['id'] ?? 0));
if (!$product) {
    http_response_code(404);
    exit;
}

$palette = [
    'Samsung' => ['#1e88e5', '#0f5fab', '#eaf4ff'],
    'Tecno' => ['#14b8a6', '#0f766e', '#ecfdf5'],
    'Infinix' => ['#22c55e', '#15803d', '#f0fdf4'],
    'Xiaomi' => ['#f97316', '#c2410c', '#fff7ed'],
    'Oppo' => ['#16a34a', '#166534', '#f0fdf4'],
    'Itel' => ['#2563eb', '#1d4ed8', '#eff6ff'],
    'Vivo' => ['#3b82f6', '#1e40af', '#eff6ff'],
    'Realme' => ['#facc15', '#a16207', '#fefce8'],
    'Nokia' => ['#334155', '#0f172a', '#f8fafc'],
    'Motorola' => ['#06b6d4', '#0e7490', '#ecfeff'],
    'Honor' => ['#8b5cf6', '#6d28d9', '#f5f3ff'],
    'Huawei' => ['#ef4444', '#b91c1c', '#fef2f2'],
    'OnePlus' => ['#dc2626', '#991b1b', '#fef2f2'],
    'Google Pixel' => ['#4285f4', '#174ea6', '#eff6ff'],
    'Nothing' => ['#111827', '#000000', '#f9fafb'],
    'Sony' => ['#475569', '#1e293b', '#f8fafc'],
    'Asus' => ['#7c3aed', '#5b21b6', '#f5f3ff'],
    'TCL' => ['#0ea5e9', '#0369a1', '#f0f9ff'],
    'ZTE' => ['#2563eb', '#1e3a8a', '#eff6ff'],
    'Blackview' => ['#111827', '#030712', '#f9fafb'],
    'Ulefone' => ['#f59e0b', '#92400e', '#fffbeb'],
    'Doogee' => ['#64748b', '#334155', '#f8fafc'],
    'Oukitel' => ['#84cc16', '#3f6212', '#f7fee7'],
    'Umidigi' => ['#ec4899', '#be185d', '#fdf2f8'],
    'Cubot' => ['#0891b2', '#155e75', '#ecfeff'],
    'Meizu' => ['#6366f1', '#4338ca', '#eef2ff'],
    'Alcatel' => ['#0d9488', '#115e59', '#f0fdfa']
];

[$primary, $dark, $soft] = $palette[$product['brand']] ?? ['#1e88e5', '#0f5fab', '#f4f7fb'];
$memory = $product['memory'] ?? '128 Go';
$ram = $product['ram'] ?? '4 Go';

header('Content-Type: image/svg+xml; charset=UTF-8');
?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 900 675" role="img" aria-labelledby="title desc">
  <title id="title"><?= e($product['name']) ?></title>
  <desc id="desc">Visuel produit Mobile Hub pour <?= e($product['name']) ?></desc>
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="<?= e($soft) ?>"/>
      <stop offset="1" stop-color="#ffffff"/>
    </linearGradient>
    <linearGradient id="phone" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0" stop-color="#1f2937"/>
      <stop offset="0.55" stop-color="#0f172a"/>
      <stop offset="1" stop-color="<?= e($dark) ?>"/>
    </linearGradient>
    <filter id="shadow" x="-30%" y="-30%" width="160%" height="160%">
      <feDropShadow dx="0" dy="26" stdDeviation="24" flood-color="#17212b" flood-opacity="0.22"/>
    </filter>
  </defs>
  <rect width="900" height="675" rx="0" fill="url(#bg)"/>
  <circle cx="128" cy="102" r="86" fill="<?= e($primary) ?>" opacity="0.12"/>
  <circle cx="770" cy="568" r="126" fill="<?= e($primary) ?>" opacity="0.10"/>
  <g filter="url(#shadow)">
    <rect x="298" y="70" width="304" height="535" rx="42" fill="url(#phone)"/>
    <rect x="318" y="96" width="264" height="483" rx="32" fill="#f8fbfe"/>
    <rect x="345" y="134" width="210" height="152" rx="22" fill="<?= e($primary) ?>" opacity="0.92"/>
    <circle cx="383" cy="182" r="24" fill="#ffffff" opacity="0.94"/>
    <circle cx="449" cy="182" r="24" fill="#ffffff" opacity="0.80"/>
    <circle cx="515" cy="182" r="24" fill="#ffffff" opacity="0.66"/>
    <rect x="356" y="326" width="188" height="18" rx="9" fill="#dbe7f3"/>
    <rect x="356" y="366" width="142" height="18" rx="9" fill="#dbe7f3"/>
    <rect x="356" y="428" width="88" height="88" rx="22" fill="<?= e($primary) ?>" opacity="0.14"/>
    <rect x="466" y="428" width="88" height="88" rx="22" fill="<?= e($primary) ?>" opacity="0.14"/>
  </g>
  <g>
    <rect x="64" y="468" width="278" height="112" rx="20" fill="#ffffff" opacity="0.96"/>
    <text x="91" y="514" fill="#667085" font-family="Arial, Helvetica, sans-serif" font-size="24" font-weight="700"><?= e($product['brand']) ?></text>
    <text x="91" y="550" fill="#17212b" font-family="Arial, Helvetica, sans-serif" font-size="30" font-weight="800"><?= e($memory) ?> / <?= e($ram) ?></text>
  </g>
  <g>
    <rect x="594" y="96" width="242" height="82" rx="18" fill="#ffffff" opacity="0.96"/>
    <text x="620" y="130" fill="#667085" font-family="Arial, Helvetica, sans-serif" font-size="20" font-weight="700">Mobile Hub</text>
    <text x="620" y="160" fill="<?= e($dark) ?>" font-family="Arial, Helvetica, sans-serif" font-size="26" font-weight="900"><?= format_price($product['price']) ?></text>
  </g>
</svg>
