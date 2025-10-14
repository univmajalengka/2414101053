<?php
    require_once __DIR__ . '/config/db.php';

    function slugify(string $text): string
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
        return trim($text, '-');
    }

    function resolveImagePath(?string $filename): string
    {
        if (!$filename) {
            return 'https://via.placeholder.com/400x400?text=No+Image';
        }

        $candidates = [
            'gambar/' . $filename,
            'admin/gambar/' . $filename,
        ];

        foreach ($candidates as $candidate) {
            if (file_exists(__DIR__ . '/' . $candidate)) {
                return $candidate;
            }
        }

        return 'https://via.placeholder.com/400x400?text=No+Image';
    }

    function parsePackages(?string $raw): array
    {
        if (!$raw) {
            return [];
        }

        $raw = trim($raw);
        if ($raw === '') {
            return [];
        }

        $packages = [];
        $decoded = json_decode($raw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            foreach ($decoded as $item) {
                if (is_array($item)) {
                    $label = trim((string) ($item['nama'] ?? $item['label'] ?? ''));
                    $price = trim((string) ($item['harga'] ?? $item['price'] ?? ''));
                    if ($label === '' && $price === '') {
                        continue;
                    }
                    if ($price !== '' && ctype_digit(str_replace(['.', ','], '', $price))) {
                        $price = 'Rp ' . number_format((float) str_replace(['.', ','], '', $price), 0, ',', '.');
                    }
                    $packages[] = [
                        'label' => $label !== '' ? $label : $price,
                        'price' => $price,
                    ];
                } elseif (is_string($item)) {
                    $label = trim($item);
                    if ($label !== '') {
                        $packages[] = [
                            'label' => $label,
                            'price' => '',
                        ];
                    }
                }
            }
            return $packages;
        }

        $lines = preg_split('/
?
|;;/', $raw);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $label = $line;
            $price = '';
            if (strpos($line, '||') !== false) {
                [$label, $price] = array_map('trim', explode('||', $line, 2));
            } elseif (strpos($line, '|') !== false) {
                [$label, $price] = array_map('trim', explode('|', $line, 2));
            } elseif (strpos($line, ' - ') !== false) {
                [$label, $price] = array_map('trim', explode(' - ', $line, 2));
            }

            if ($label === '' && $price === '') {
                continue;
            }

            if ($price !== '' && ctype_digit(str_replace(['.', ','], '', $price))) {
                $price = 'Rp ' . number_format((float) str_replace(['.', ','], '', $price), 0, ',', '.');
            }

            $packages[] = [
                'label' => $label !== '' ? $label : $price,
                'price' => $price,
            ];
        }

        return $packages;
    }

    $slug = isset($_GET['product']) ? trim((string) $_GET['product']) : '';
    $productData = null;
    $dbError = '';

    if ($slug !== '' && isset($conn) && $conn instanceof mysqli) {
        $query = "SELECT * FROM produk";
        $result = $conn->query($query);
        if ($result === false) {
            $dbError = $conn->error;
        } else {
            while ($row = $result->fetch_assoc()) {
                $candidateSlug = slugify($row['nama_produk'] ?? '');
                if ($candidateSlug === $slug) {
                    $productData = [
                        'id' => $row['id_produk'] ?? null,
                        'name' => $row['nama_produk'] ?? '',
                        'description' => $row['deskripsi_produk'] ?? '',
                        'instructions' => trim((string) ($row['instruksi_produk'] ?? '')),
                        'image' => resolveImagePath($row['nama_berkas_gambar'] ?? ''),
                        'packages' => parsePackages($row['daftar_paket'] ?? null),
                    ];
                    if ($productData['instructions'] === '') {
                        $productData['instructions'] = 'Masukkan User ID dan Server ID sesuai petunjuk di dalam game.';
                    }
                    break;
                }
            }
            $result->free();
        }
    } elseif ($slug === '') {
        $dbError = 'Parameter produk tidak ditemukan.';
    } else {
        $dbError = 'Koneksi database tidak tersedia.';
    }

    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }

    $paymentMethods = ['QRIS', 'Transfer Bank', 'GoPay', 'OVO', 'Dana'];
    ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Produk - Novastore</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-950 text-white">
    <nav class="bg-gray-900/90 backdrop-blur-md shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold text-blue-500">Novastore</a>
            <a href="index.php#produk" class="text-sm text-gray-300 hover:text-blue-400">Kembali ke Produk</a>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-6 py-12">
        <?php if ($productData === null): ?>
        <section class="bg-gray-900 rounded-2xl p-10 text-center shadow-lg">
            <h1 class="text-3xl font-bold text-blue-400 mb-4">Produk tidak ditemukan</h1>
            <?php if ($dbError !== ''): ?>
            <p class="text-red-400 mb-4 text-sm"><?php echo htmlspecialchars($dbError); ?></p>
            <?php else: ?>
            <p class="text-gray-400 mb-6">Kami tidak dapat menemukan produk yang kamu cari. Silakan kembali ke daftar
                produk untuk memilih game lainnya.</p>
            <?php endif; ?>
            <a href="index.php#produk"
                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg text-white font-semibold inline-block">Lihat
                Produk</a>
        </section>
        <?php else: ?>
        <section class="grid lg:grid-cols-[2fr,3fr] gap-10 items-start">
            <div class="bg-gray-900 rounded-2xl p-8 shadow-lg">
                <div class="aspect-square bg-gray-800 flex items-center justify-center rounded-xl mb-6 overflow-hidden">
                    <img src="<?php echo htmlspecialchars($productData['image']); ?>"
                        alt="<?php echo htmlspecialchars($productData['name']); ?>"
                        class="w-4/5 h-4/5 object-contain" />
                </div>
                <h1 class="text-3xl font-bold text-blue-400 mb-2"><?php echo htmlspecialchars($productData['name']); ?>
                </h1>
                <p class="text-gray-300 leading-relaxed"><?php echo htmlspecialchars($productData['description']); ?>
                </p>
                <div class="mt-8 bg-gray-800 p-4 rounded-xl">
                    <h2 class="text-lg font-semibold text-blue-300 mb-2">Instruksi Pengisian ID</h2>
                    <p class="text-gray-300 text-sm leading-relaxed">
                        <?php echo htmlspecialchars($productData['instructions']); ?></p>
                </div>
            </div>

            <div class="bg-gray-900 rounded-2xl p-8 shadow-lg">
                <h2 class="text-2xl font-semibold text-blue-400 mb-6">Formulir Top Up</h2>
                <form action="success.php" method="post" class="space-y-6">
                    <input type="hidden" name="product_slug" value="<?php echo htmlspecialchars($slug); ?>" />
                    <input type="hidden" name="product_id"
                        value="<?php echo htmlspecialchars((string) ($productData['id'] ?? '')); ?>" />
                    <div>
                        <label for="user_id" class="block text-sm font-semibold text-gray-200 mb-2">User ID</label>
                        <input id="user_id" name="user_id" type="text" required placeholder="Masukkan User ID"
                            class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div>
                        <label for="server_id" class="block text-sm font-semibold text-gray-200 mb-2">Server / Zone
                            ID</label>
                        <input id="server_id" name="server_id" type="text" required placeholder="Masukkan Server ID"
                            class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div>
                        <label for="nickname" class="block text-sm font-semibold text-gray-200 mb-2">Nickname
                            (opsional)</label>
                        <input id="nickname" name="nickname" type="text" placeholder="Masukkan nama karakter"
                            class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div>
                        <label for="nominal" class="block text-sm font-semibold text-gray-200 mb-2">Pilih
                            Nominal</label>
                        <select id="nominal" name="nominal" required
                            class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <?php if (!empty($productData['packages'])): ?>
                            <option value="" disabled selected>Pilih paket top up</option>
                            <?php foreach ($productData['packages'] as $package): ?>
                            <?php $display = $package['label'] . ($package['price'] !== '' ? ' - ' . $package['price'] : ''); ?>
                            <option value="<?php echo htmlspecialchars($package['label']); ?>">
                                <?php echo htmlspecialchars($display); ?></option>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <option value="" disabled selected>Belum ada paket tersedia</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <label for="payment_method" class="block text-sm font-semibold text-gray-200 mb-2">Metode
                            Pembayaran</label>
                        <select id="payment_method" name="payment_method" required
                            class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="" disabled selected>Pilih metode pembayaran</option>
                            <?php foreach ($paymentMethods as $method): ?>
                            <option value="<?php echo htmlspecialchars($method); ?>">
                                <?php echo htmlspecialchars($method); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-semibold text-gray-200 mb-2">Catatan
                            (opsional)</label>
                        <textarea id="notes" name="notes" rows="3" placeholder="Catatan tambahan untuk admin"
                            class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <button type="submit"
                        class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold">Konfirmasi Top
                        Up</button>
                </form>
                <p class="text-xs text-gray-500 mt-4">Setelah submit, admin akan memproses pesananmu dalam 1-5 menit.
                </p>
            </div>
        </section>
        <?php endif; ?>
    </main>
</body>

</html>