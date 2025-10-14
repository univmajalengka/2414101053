<?php
require_once __DIR__ . '/config/db.php';

$produkList = [];
$errorProduk = '';

if (isset($conn) && $conn instanceof mysqli) {
    $query = "SELECT id_produk, nama_produk, deskripsi_produk, nama_berkas_gambar FROM produk ORDER BY tanggal_dibuat DESC";
    $result = $conn->query($query);
    if ($result === false) {
        $errorProduk = $conn->error;
    } else {
        while ($row = $result->fetch_assoc()) {
            $namaProduk = $row['nama_produk'] ?? '';
            $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $namaProduk), '-'));

            $imageSrc = 'https://via.placeholder.com/300x300?text=No+Image';
            $imageFile = $row['nama_berkas_gambar'] ?? '';
            if ($imageFile !== '') {
                $candidates = [
                    'gambar/' . $imageFile,
                    'admin/gambar/' . $imageFile,
                ];
                foreach ($candidates as $candidate) {
                    if (file_exists(__DIR__ . '/' . $candidate)) {
                        $imageSrc = $candidate;
                        break;
                    }
                }
            }

            $produkList[] = [
                'id' => $row['id_produk'],
                'nama' => $namaProduk,
                'deskripsi' => $row['deskripsi_produk'] ?? '',
                'image' => $imageSrc,
                'slug' => $slug,
            ];
        }
        $result->free();
    }
} else {
    $errorProduk = 'Koneksi database tidak tersedia.';
}

if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TopUpGame - Landing Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-950 text-white font-sans">
    <!-- Navbar -->
    <nav class="fixed top-0 left-0 w-full bg-gray-900/90 backdrop-blur-md z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center h-16">
            <h1 class="text-2xl font-bold text-blue-500">Novastore</h1>
            <ul class="hidden md:flex space-x-8">
                <li><a href="#home" class="hover:text-blue-400">Beranda</a></li>
                <li><a href="#produk" class="hover:text-blue-400">Produk</a></li>
                <li>
                    <a href="#testimoni" class="hover:text-blue-400">Testimoni</a>
                </li>
                <li><a href="#faq" class="hover:text-blue-400">FAQ</a></li>
                <li><a href="#kontak" class="hover:text-blue-400">Kontak</a></li>
                <li><a href="login.php" class="px-2 py-1 bg-blue-500 rounded-lg">Login</a></li>
            </ul>
            <button class="md:hidden text-blue-500">???</button>
        </div>
    </nav>

    <!-- Jumbotron -->
    <section id="home"
        class="relative h-screen flex items-center justify-center bg-gradient-to-r from-gray-950 via-gray-900 to-gray-950">
        <div class="text-center px-6 mt-16">
            <h2 class="text-4xl md:text-6xl font-bold text-blue-400 mb-4">
                Top Up Game Favoritmu Sekarang
            </h2>
            <p class="text-gray-300 mb-6 text-lg">
                Cepat, aman, terpercaya, dan harga terbaik untuk semua game populer
            </p>
            <a href="#produk"
                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg shadow-lg text-white font-semibold">Mulai Top
                Up</a>
        </div>
    </section>

    <!-- Carousel -->
    <section class="py-12 bg-gray-900">
        <div class="max-w-6xl mx-auto px-6">
            <h3 class="text-3xl font-bold text-center mb-8 text-blue-400">
                Promo Spesialb
            </h3>
            <div class="relative overflow-hidden rounded-xl shadow-lg">
                <div class="flex animate-scroll space-x-4">
                    <img src="gambar/nova1.png" alt="Promo 1" class="rounded-lg" />
                    <img src="gambar/nova2.png" alt="Promo 2" class="rounded-lg" />

                </div>
            </div>
        </div>
    </section>

    <!-- Produk Cards -->
    <section id="produk" class="py-16 bg-gray-950">
        <div class="max-w-7xl mx-auto px-6">
            <h3 class="text-3xl font-bold text-center text-blue-400 mb-10">
                Produk Populer
            </h3>

            <div class="grid md:grid-cols-3 gap-8">
                <?php if ($errorProduk !== ''): ?>
                <div class="md:col-span-3 bg-gray-900 rounded-2xl p-6 text-center border border-red-500/40">
                    <p class="text-red-400 text-sm">Terjadi kesalahan saat memuat produk:
                        <?php echo htmlspecialchars($errorProduk); ?></p>
                </div>
                <?php elseif (empty($produkList)): ?>
                <div class="md:col-span-3 bg-gray-900 rounded-2xl p-6 text-center border border-gray-800">
                    <p class="text-gray-400">Belum ada produk tersedia.</p>
                </div>
                <?php else: ?>
                <?php foreach ($produkList as $produk): ?>
                <div class="bg-gray-900 rounded-2xl p-6 shadow-lg hover:scale-105 transition">
                    <div
                        class="aspect-square w-full mb-4 overflow-hidden rounded-lg flex items-center justify-center bg-gray-800">
                        <img src="<?php echo htmlspecialchars($produk['image']); ?>"
                            alt="<?php echo htmlspecialchars($produk['nama']); ?>" class="w-3/4 h-3/4 object-contain" />
                    </div>
                    <h4 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($produk['nama']); ?></h4>
                    <p class="text-gray-400 mb-4"><?php echo htmlspecialchars($produk['deskripsi']); ?></p>
                    <?php $detailLink = $produk['slug'] !== '' ? 'detail.php?product=' . urlencode($produk['slug']) : '#'; ?>
                    <a href="<?php echo $detailLink; ?>"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg w-full inline-block text-center">
                        Top Up Sekarang
                    </a>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Testimoni -->
    <!-- Testimoni -->
    <section id="testimoni" class="py-16 bg-gray-900">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h3 class="text-3xl font-bold text-blue-400 mb-10">Apa Kata Mereka</h3>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-gray-800 p-6 rounded-xl shadow-lg">
                    <p class="text-gray-300 mb-4">
                        "Top up cepat banget, cuma 1 menit langsung masuk!"
                    </p>
                    <h4 class="font-semibold text-blue-400">Budi, Jakarta</h4>
                </div>
                <div class="bg-gray-800 p-6 rounded-xl shadow-lg">
                    <p class="text-gray-300 mb-4">
                        "Harga lebih murah dibanding tempat lain, recommended!"
                    </p>
                    <h4 class="font-semibold text-blue-400">Sari, Bandung</h4>
                </div>
                <div class="bg-gray-800 p-6 rounded-xl shadow-lg">
                    <p class="text-gray-300 mb-4">
                        "Customer supportnya ramah banget, mantap."
                    </p>
                    <h4 class="font-semibold text-blue-400">Andi, Surabaya</h4>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section id="faq" class="py-16 bg-gray-950">
        <div class="max-w-4xl mx-auto px-6">
            <h3 class="text-3xl font-bold text-center text-blue-400 mb-10">FAQ</h3>
            <div class="space-y-6">
                <details class="bg-gray-900 p-4 rounded-lg">
                    <summary class="cursor-pointer font-semibold">
                        Berapa lama proses top up?
                    </summary>
                    <p class="text-gray-400 mt-2">
                        Biasanya hanya 1-5 menit, tergantung metode pembayaran.
                    </p>
                </details>
                <details class="bg-gray-900 p-4 rounded-lg">
                    <summary class="cursor-pointer font-semibold">
                        Metode pembayaran apa saja yang tersedia?
                    </summary>
                    <p class="text-gray-400 mt-2">
                        Kami menerima transfer bank, e-wallet, dan pulsa.
                    </p>
                </details>
                <details class="bg-gray-900 p-4 rounded-lg">
                    <summary class="cursor-pointer font-semibold">Apakah aman?</summary>
                    <p class="text-gray-400 mt-2">
                        Ya, transaksi dijamin aman dengan sistem otomatis.
                    </p>
                </details>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="kontak" class="bg-gray-900 py-10 mt-12">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-8">
            <div>
                <h4 class="text-xl font-bold text-blue-400 mb-4">TopUpGame</h4>
                <p class="text-gray-400">
                    Solusi top up game cepat, aman, dan terpercaya.
                </p>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Navigasi</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#produk" class="hover:text-blue-400">Produk</a></li>
                    <li>
                        <a href="#testimoni" class="hover:text-blue-400">Testimoni</a>
                    </li>
                    <li><a href="#faq" class="hover:text-blue-400">FAQ</a></li>
                    <li><a href="#kontak" class="hover:text-blue-400">Kontak</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Hubungi Kami</h4>
                <p class="text-gray-400">Email: support@topupgame.com</p>
                <p class="text-gray-400">WhatsApp: +62 812-3456-7890</p>
            </div>
        </div>
        <div class="text-center text-gray-500 mt-8 text-sm">
            ?? 2025 TopUpGame. Semua Hak Dilindungi.
        </div>
    </footer>

    <!-- Animasi Carousel -->
    <style>
    .animate-scroll {
        animation: scroll 20s linear infinite;
    }

    @keyframes scroll {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-100%);
        }
    }
    </style>
</body>

</html>