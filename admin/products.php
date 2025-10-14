<?php
include '../config/db.php';

$sql = "SELECT id_produk AS id, nama_produk AS nama, deskripsi_produk AS deskripsi, daftar_paket AS paket, nama_berkas_gambar AS gambar, tanggal_dibuat AS created_at FROM produk ORDER BY tanggal_dibuat DESC";
$result = $conn->query($sql);
$totalProduk = $result instanceof mysqli_result ? $result->num_rows : 0;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Produk - Novastore Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-950 text-white">
    <nav class="bg-gray-900 border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-6 py-4 flex flex-wrap items-center justify-between gap-4">
            <a href="dashboard.php" class="text-xl font-semibold text-blue-400">Novastore Admin</a>
            <div class="flex flex-wrap gap-3 text-sm">
                <a href="dashboard.php" class="px-3 py-2 rounded-lg bg-gray-800 hover:bg-gray-700">Dashboard</a>
                <a href="products.php" class="px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700">Produk</a>
                <a href="orders.php" class="px-3 py-2 rounded-lg bg-gray-800 hover:bg-gray-700">Pesanan</a>
                <a href="createProduk.php" class="px-3 py-2 rounded-lg bg-green-600 hover:bg-green-700">Tambah Produk</a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-10 space-y-8">
        <header class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-blue-400">Manajemen Produk</h1>
                <p class="text-gray-400 text-sm">Kelola katalog produk yang tampil di halaman utama.</p>
            </div>
            <span class="inline-flex items-center gap-2 text-sm text-gray-300 bg-gray-900 border border-gray-800 rounded-lg px-4 py-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                Total Produk: <strong class="text-white"><?php echo $totalProduk; ?></strong>
            </span>
        </header>

        <section class="bg-gray-900 border border-gray-800 rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <?php if ($result === false): ?>
                    <div class="p-8 text-center text-red-400">
                        Terjadi kesalahan saat memuat data produk: <?php echo htmlspecialchars($conn->error); ?>
                    </div>
                <?php elseif ($result->num_rows === 0): ?>
                    <div class="p-8 text-center text-gray-400">Belum ada data produk.</div>
                <?php else: ?>
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-900 text-gray-300 uppercase text-xs tracking-wide">
                            <tr>
                                <th class="px-4 py-3 text-left">ID</th>
                                <th class="px-4 py-3 text-left">Produk</th>
                                <th class="px-4 py-3 text-left">Deskripsi</th>
                                <th class="px-4 py-3 text-left">Paket Harga</th>
                                <th class="px-4 py-3 text-left">Gambar</th>
                                <th class="px-4 py-3 text-left">Tanggal Dibuat</th>
                                <th class="px-4 py-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <?php
                                    $imageFile = $row['gambar'];
                                    $imageSrc = 'https://via.placeholder.com/48x48?text=No+Image';
                                    if (!empty($imageFile)) {
                                        $adminImagePath = __DIR__ . '/gambar/' . $imageFile;
                                        $rootImagePath = dirname(__DIR__) . '/gambar/' . $imageFile;

                                        if (file_exists($adminImagePath)) {
                                            $imageSrc = 'gambar/' . rawurlencode($imageFile);
                                        } elseif (file_exists($rootImagePath)) {
                                            $imageSrc = '../gambar/' . rawurlencode($imageFile);
                                        }
                                    }

                                    $packageSummary = 'Belum diatur';
                                    $rawPackage = $row['paket'] ?? '';
                                    if (!empty($rawPackage)) {
                                        $decodedPackages = json_decode($rawPackage, true);
                                        if (json_last_error() === JSON_ERROR_NONE && is_array($decodedPackages) && !empty($decodedPackages)) {
                                            $items = [];
                                            foreach ($decodedPackages as $item) {
                                                if (!is_array($item)) {
                                                    continue;
                                                }

                                                $label = trim((string) ($item['nama'] ?? $item['label'] ?? ''));
                                                $price = trim((string) ($item['harga'] ?? $item['price'] ?? ''));

                                                if ($label !== '' && $price !== '') {
                                                    $items[] = $label . ' (' . $price . ')';
                                                } elseif ($label !== '') {
                                                    $items[] = $label;
                                                } elseif ($price !== '') {
                                                    $items[] = $price;
                                                }
                                            }

                                            if ($items !== []) {
                                                $visible = array_slice($items, 0, 3);
                                                $packageSummary = implode(', ', $visible);
                                                $remaining = count($items) - count($visible);
                                                if ($remaining > 0) {
                                                    $packageSummary .= ' +' . $remaining;
                                                }
                                            }
                                        } else {
                                            $trimmed = trim($rawPackage);
                                            if ($trimmed !== '') {
                                                $packageSummary = strlen($trimmed) > 60 ? substr($trimmed, 0, 57) . '...' : $trimmed;
                                            }
                                        }
                                    }
                                ?>
                                <tr class="hover:bg-gray-900">
                                    <td class="px-4 py-3 font-mono text-gray-400">#<?php echo htmlspecialchars($row['id']); ?></td>
                                    <td class="px-4 py-3 font-semibold text-white"><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td class="px-4 py-3 text-gray-300 max-w-md">
                                        <p class="truncate"><?php echo htmlspecialchars($row['deskripsi']); ?></p>
                                    </td>
                                    <td class="px-4 py-3 text-gray-300 max-w-sm">
                                        <p class="truncate"><?php echo htmlspecialchars($packageSummary); ?></p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <img src="<?php echo $imageSrc; ?>" alt="<?php echo htmlspecialchars($row['nama']); ?>"
                                            class="h-12 w-12 object-cover rounded-lg border border-gray-800" />
                                    </td>
                                    <td class="px-4 py-3 text-gray-300">
                                        <?php echo htmlspecialchars(date('d M Y H:i', strtotime($row['created_at']))); ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="updateProduk.php?id=<?php echo urlencode($row['id']); ?>"
                                                class="px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-black rounded-lg">Edit</a>
                                            <a href="delete.php?id=<?php echo urlencode($row['id']); ?>"
                                                class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg"
                                                onclick="return confirm('Yakin ingin menghapus produk ini?');">Hapus</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php $conn->close(); ?>
</body>

</html>
