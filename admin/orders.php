<?php
include "../config/db.php";

$sql = "SELECT id_pesanan, kode_produk, nama_produk, user_id_game, server_id_game, nickname_game, paket_nominal, metode_pembayaran, catatan_pembeli, tanggal_dibuat FROM pesanan ORDER BY tanggal_dibuat DESC";
$result = $conn->query($sql);

$orders = [];
$errorMessage = '';

if ($result === false) {
    $errorMessage = $conn->error;
} elseif ($result instanceof mysqli_result) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    $result->free();
}

$totalOrders = count($orders);

function formatDateTime(?string $dateTime): string
{
    if (!$dateTime) {
        return '-';
    }
    $timestamp = strtotime($dateTime);
    return $timestamp ? date('d M Y H:i', $timestamp) : $dateTime;
}

function formatPlayerIdentity(array $row): string
{
    $userId = trim((string) ($row['user_id_game'] ?? ''));
    $serverId = trim((string) ($row['server_id_game'] ?? ''));
    $nickname = trim((string) ($row['nickname_game'] ?? ''));

    $parts = [];
    if ($userId !== '') {
        $parts[] = 'User ID ' . htmlspecialchars($userId);
    }
    if ($serverId !== '') {
        $parts[] = 'Server ' . htmlspecialchars($serverId);
    }
    if ($nickname !== '') {
        $parts[] = 'Nickname ' . htmlspecialchars($nickname);
    }

    return $parts ? implode(' ? ', $parts) : '<span class="text-gray-500">-</span>';
}

function formatNominal($value): string
{
    $value = trim((string) $value);
    if ($value === '') {
        return '-';
    }

    if (preg_match('/[A-Za-z]/', $value)) {
        return htmlspecialchars($value);
    }

    $numeric = preg_replace('/[^0-9]/', '', $value);
    if ($numeric !== '' && ctype_digit($numeric)) {
        return 'Rp ' . number_format((float) $numeric, 0, ',', '.');
    }

    return htmlspecialchars($value);
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pesanan - Novastore Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-950 text-white min-h-screen">
    <nav class="bg-gray-900 border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-6 py-4 flex flex-wrap items-center justify-between gap-4">
            <a href="dashboard.php" class="text-xl font-semibold text-blue-400">Novastore Admin</a>
            <div class="flex flex-wrap gap-3 text-sm">
                <a href="dashboard.php" class="px-3 py-2 rounded-lg bg-gray-800 hover:bg-gray-700">Dashboard</a>
                <a href="products.php" class="px-3 py-2 rounded-lg bg-gray-800 hover:bg-gray-700">Produk</a>
                <a href="orders.php" class="px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700">Pesanan</a>
                <a href="create.php" class="px-3 py-2 rounded-lg bg-green-600 hover:bg-green-700">Tambah Produk</a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-10 space-y-8">
        <header class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white">Daftar Pesanan</h1>
                <p class="text-gray-400 text-sm">Pantau transaksi pelanggan yang tercatat di sistem.</p>
            </div>
            <span
                class="inline-flex items-center gap-2 text-sm text-gray-300 bg-gray-900 border border-gray-800 rounded-lg px-4 py-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                Total Pesanan: <strong class="text-white"><?php echo number_format($totalOrders); ?></strong>
            </span>
        </header>

        <section class="bg-gray-900 border border-gray-800 rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <?php if ($errorMessage !== ''): ?>
                <div class="p-8 text-center text-red-400">
                    Tidak dapat memuat data pesanan: <?php echo htmlspecialchars($errorMessage); ?>
                </div>
                <?php elseif (empty($orders)): ?>
                <div class="p-8 text-center text-gray-400">Belum ada data pesanan.</div>
                <?php else: ?>
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-900 text-gray-300 uppercase text-xs tracking-wide">
                        <tr>
                            <th class="px-4 py-3 text-left">ID Pesanan</th>
                            <th class="px-4 py-3 text-left">Produk</th>
                            <th class="px-4 py-3 text-left">User ID / Pelanggan</th>
                            <th class="px-4 py-3 text-left">Nominal</th>
                            <th class="px-4 py-3 text-left">Metode Pembayaran</th>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Catatan</th>
                            <th class="px-4 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        <?php foreach ($orders as $order): ?>
                        <tr class="hover:bg-gray-900">
                            <td class="px-4 py-3 font-mono text-gray-400">
                                #<?php echo htmlspecialchars($order['id_pesanan']); ?></td>
                            <td class="px-4 py-3 text-white">
                                <?php echo htmlspecialchars($order['nama_produk'] ?: ($order['kode_produk'] ?? '')); ?>
                            </td>
                            <td class="px-4 py-3 text-gray-300">
                                <?php echo formatPlayerIdentity($order); ?>
                            </td>
                            <td class="px-4 py-3 text-gray-200">
                                <?php echo formatNominal($order['paket_nominal'] ?? ''); ?>
                            </td>
                            <td class="px-4 py-3 text-gray-300">
                                <?php echo htmlspecialchars($order['metode_pembayaran'] ?? '-'); ?>
                            </td>
                            <td class="px-4 py-3 text-gray-400">
                                <?php echo htmlspecialchars(formatDateTime($order['tanggal_dibuat'] ?? '')); ?>
                            </td>
                            <td class="px-4 py-3 text-gray-300">
                                <?php
                                            $notes = trim((string) ($order['catatan_pembeli'] ?? ''));
                                            echo $notes !== '' ? nl2br(htmlspecialchars($notes)) : '<span class="text-gray-500">-</span>';
                                        ?>
                            </td>
                            <td class="px-4 py-3">
                                <a href="deletePesanan.php?id=<?php echo urlencode($order['id_pesanan']); ?>"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600 hover:bg-red-700 rounded-lg text-xs font-semibold text-white"
                                    onclick="return confirm('Hapus pesanan ini?');">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php $conn->close(); ?>
</body>

</html>