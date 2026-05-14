<?php
declare(strict_types=1);

const PRODUCTS_FILE = __DIR__ . '/../data/products.json';
const ORDERS_FILE = __DIR__ . '/../data/orders.json';
const ADMIN_PASSWORD = 'admin123';
const CONTACT_PHONE = '+229 01 90 00 00 00';
const CONTACT_WHATSAPP = 'https://wa.me/2290190000000';
const CONTACT_FACEBOOK = 'https://facebook.com/mobilehub';
const CONTACT_INSTAGRAM = 'https://instagram.com/mobilehub';

function read_json_file(string $file): array
{
    if (!file_exists($file)) {
        return [];
    }

    $content = file_get_contents($file);
    $data = json_decode($content ?: '[]', true);

    return is_array($data) ? $data : [];
}

function write_json_file(string $file, array $data): void
{
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

function get_products(): array
{
    $products = read_json_file(PRODUCTS_FILE);
    usort($products, fn ($a, $b) => (int) $a['id'] <=> (int) $b['id']);
    return $products;
}

function save_products(array $products): void
{
    write_json_file(PRODUCTS_FILE, array_values($products));
}

function get_orders(): array
{
    $orders = read_json_file(ORDERS_FILE);
    return array_reverse($orders);
}

function save_orders(array $orders): void
{
    write_json_file(ORDERS_FILE, array_values($orders));
}

function save_order(array $order): void
{
    $orders = read_json_file(ORDERS_FILE);
    $order['id'] = next_order_id($orders);
    $orders[] = $order;
    write_json_file(ORDERS_FILE, $orders);
}

function next_order_id(array $orders): int
{
    $ids = array_map(fn ($order) => (int) ($order['id'] ?? 0), $orders);
    return $ids ? max($ids) + 1 : 1;
}

function find_product(int $id): ?array
{
    foreach (get_products() as $product) {
        if ((int) $product['id'] === $id) {
            return $product;
        }
    }

    return null;
}

function products_by_brand(string $brand): array
{
    return array_values(array_filter(get_products(), fn ($product) => strtolower((string) $product['brand']) === strtolower($brand)));
}

function brands(): array
{
    $brands = array_unique(array_merge(
        ['Samsung', 'Tecno', 'Infinix', 'Xiaomi', 'Oppo', 'Itel', 'Vivo', 'Realme', 'Nokia', 'Motorola', 'Honor', 'Huawei', 'OnePlus', 'Google Pixel', 'Nothing', 'Sony', 'Asus', 'TCL', 'ZTE', 'Blackview', 'Ulefone', 'Doogee', 'Oukitel', 'Umidigi', 'Cubot', 'Meizu', 'Alcatel'],
        array_map(fn ($product) => (string) $product['brand'], get_products())
    ));
    sort($brands);
    return $brands;
}

function brand_slug(string $brand): string
{
    $legacy = ['Samsung', 'Tecno', 'Infinix', 'Xiaomi', 'Oppo'];
    if (in_array($brand, $legacy, true)) {
        return strtolower($brand) . '.php';
    }

    return 'brand.php?brand=' . rawurlencode($brand);
}

function format_price(int|float|string $price): string
{
    return number_format((float) $price, 0, ',', ' ') . ' FCFA';
}

function product_specs(array $product): array
{
    return [
        'Mémoire' => $product['memory'] ?? 'À préciser',
        'RAM' => $product['ram'] ?? 'À préciser',
        'Batterie' => $product['battery'] ?? 'À préciser',
        'Caméra' => $product['camera'] ?? 'À préciser',
        'Garantie' => $product['warranty'] ?? '7 jours'
    ];
}

function normalize_phone(string $phone): string
{
    $digits = preg_replace('/\D+/', '', $phone);
    return $digits ?: $phone;
}

function customer_whatsapp_link(array $order): string
{
    $phone = normalize_phone((string) ($order['phone'] ?? ''));
    $message = 'Bonjour ' . ($order['name'] ?? '') . ', ici Mobile Hub. Nous vous contactons pour confirmer votre commande ' . ($order['product'] ?? '') . ' et organiser la livraison.';
    return 'https://wa.me/' . $phone . '?text=' . rawurlencode($message);
}

function order_statuses(): array
{
    return ['Nouvelle', 'Confirmée', 'Livrée', 'Annulée'];
}

function e(string|int|float|null $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function next_product_id(array $products): int
{
    $ids = array_map(fn ($product) => (int) $product['id'], $products);
    return $ids ? max($ids) + 1 : 1;
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}
?>
