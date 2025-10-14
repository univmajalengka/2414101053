<?php
require_once __DIR__ . '/config/db.php';

function slugify(string $text): string
{
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
    return trim($text, '-');
}

function fetchProduct(mysqli $conn, string $whereClause): ?array
{
    $query = "SELECT * FROM produk " . $whereClause;
    $result = $conn->query($query);
    if ($result instanceof mysqli_result) {
        $row = $result->fetch_assoc();
        $result->free();
        return $row ?: null;
    }
    return null;
}

function findProductById(mysqli $conn, int $id): ?array
{
    if ($id <= 0) {
        return null;
    }
    return fetchProduct($conn, "WHERE id_produk = $id LIMIT 1");
}

function findProductBySlug(mysqli $conn, string $slug): ?array
{
    $query = "SELECT * FROM produk";
    $result = $conn->query($query);
    if ($result instanceof mysqli_result) {
        while ($row = $result->fetch_assoc()) {
            $candidate = slugify($row['nama_produk'] ?? '');
            if ($candidate === $slug) {
                $result->free();
                return $row;
            }
        }
        $result->free();
    }
    return null;
}

$isPost = $_SERVER['REQUEST_METHOD'] === 'POST';
$productId = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
$slug = isset($_POST['product_slug']) ? trim((string) $_POST['product_slug']) : '';
$userId = isset($_POST['user_id']) ? trim((string) $_POST['user_id']) : '';
$serverId = isset($_POST['server_id']) ? trim((string) $_POST['server_id']) : '';
$nickname = isset($_POST['nickname']) ? trim((string) $_POST['nickname']) : '';
$nominal = isset($_POST['nominal']) ? trim((string) $_POST['nominal']) : '';
$paymentMethod = isset($_POST['payment_method']) ? trim((string) $_POST['payment_method']) : '';
$notes = isset($_POST['notes']) ? trim((string) $_POST['notes']) : '';

$productData = null;
$dbError = '';

if ($isPost && isset($conn) && $conn instanceof mysqli) {
    if ($productId > 0) {
        $productData = findProductById($conn, $productId);
    }
    if (!$productData && $slug !== '') {
        $productData = findProductBySlug($conn, $slug);
    }
    if (!$productData) {
        $dbError = 'Produk tidak ditemukan.';
    }
} elseif (!$isPost) {
    $dbError = 'Permintaan tidak valid.';
} else {
    $dbError = 'Koneksi database tidak tersedia.';
}

$hasValidData = $isPost && $productData && $userId !== '' && $serverId !== '' && $nominal !== '' && $paymentMethod !== '';
$orderSaved = false;
$orderId = null;

if ($hasValidData && isset($conn) && $conn instanceof mysqli) {
    $kodeProduk = $slug !== '' ? $slug : slugify($productData['nama_produk'] ?? '');
    $namaProduk = $productData['nama_produk'] ?? '';
    $stmt = $conn->prepare("INSERT INTO pesanan (kode_produk, nama_produk, user_id_game, server_id_game, nickname_game, paket_nominal, metode_pembayaran, catatan_pembeli) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $nicknameParam = $nickname !== '' ? $nickname : '';
        $notesParam = $notes !== '' ? $notes : '';
        $stmt->bind_param('ssssssss', $kodeProduk, $namaProduk, $userId, $serverId, $nicknameParam, $nominal, $paymentMethod, $notesParam);
        if ($stmt->execute()) {
            $orderSaved = true;
            $orderId = $conn->insert_id;
        } else {
            $dbError = 'Gagal menyimpan pesanan: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        $dbError = 'Gagal menyiapkan perintah database.';
    }
}

if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}

$canShowSuccess = $hasValidData && $orderSaved;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Transaksi Sukses - Novastore</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-white">
    <nav class="bg-gray-900/90 backdrop-blur-md shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold text-blue-500">Novastore</a>
            <a href="index.php#produk" class="text-sm text-gray-300 hover:text-blue-400">Kembali ke Produk</a>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-6 py-12">
        <?php if (!$canShowSuccess): ?>
            <section class="bg-gray-900 rounded-2xl p-10 text-center shadow-lg">
                <h1 class="text-3xl font-bold text-red-400 mb-4">Data Transaksi Tidak Lengkap</h1>
                <?php if ($dbError !== ''): ?>
                    <p class="text-red-400 mb-4 text-sm"><?php echo htmlspecialchars($dbError); ?></p>
                <?php else: ?>
                    <p class="text-gray-400 mb-6">Silakan ulangi proses top up dan pastikan semua data telah diisi dengan benar.</p>
                <?php endif; ?>
                <a href="index.php#produk" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg text-white font-semibold inline-block">Pilih Produk Lagi</a>
            </section>
        <?php else: ?>
            <section class="bg-gray-900 rounded-2xl p-10 shadow-lg">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-16 h-16 rounded-full bg-green-500/20 flex items-center justify-center text-green-400 text-3xl">&#10003;</div>
                    <div>
                        <p class="text-sm uppercase tracking-wide text-green-400">Transaksi Berhasil</p>
                        <h1 class="text-3xl font-bold text-blue-400">Pesanan Sedang Diproses</h1>
                        <?php if ($orderId): ?>
                            <p class="text-xs text-gray-500 mt-1">ID Pesanan: #<?php echo htmlspecialchars($orderId); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-xl p-6 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Produk</span>
                        <strong><?php echo htmlspecialchars($productData['nama_produk'] ?? ''); ?></strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">User ID</span>
                        <strong><?php echo htmlspecialchars($userId); ?></strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Server / Zone ID</span>
                        <strong><?php echo htmlspecialchars($serverId); ?></strong>
                    </div>
                    <?php if ($nickname !== ''): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Nickname</span>
                            <strong><?php echo htmlspecialchars($nickname); ?></strong>
                        </div>
                    <?php endif; ?>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Nominal</span>
                        <strong><?php echo htmlspecialchars($nominal); ?></strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Metode Pembayaran</span>
                        <strong><?php echo htmlspecialchars($paymentMethod); ?></strong>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-1">Catatan</span>
                        <p class="text-gray-200 bg-gray-900 rounded-lg px-4 py-3 min-h-[3rem]">
                            <?php echo $notes !== '' ? nl2br(htmlspecialchars($notes)) : 'Tidak ada catatan tambahan.'; ?>
                        </p>
                    </div>
                </div>

                <div class="mt-8 bg-gray-800/70 rounded-xl p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-blue-300">Langkah Selanjutnya</h2>
                    <ul class="list-disc list-inside text-gray-300 space-y-2 text-sm">
                        <li>Admin kami akan menghubungimu apabila membutuhkan konfirmasi tambahan.</li>
                        <li>Pembayaran melalui metode yang dipilih perlu diselesaikan dalam 30 menit.</li>
                        <li>Simak status pesanan lewat WhatsApp atau email dari Novastore.</li>
                    </ul>
                </div>

                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="detail.php?product=<?php echo urlencode($slug); ?>" class="px-6 py-3 bg-gray-800 hover:bg-gray-700 rounded-lg text-white font-semibold">Buat Pesanan Lagi</a>
                    <a href="index.php#produk" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg text-white font-semibold">Lihat Produk Lain</a>
                </div>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>
