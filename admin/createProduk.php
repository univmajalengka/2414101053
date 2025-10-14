<?php
if (isset($_POST['create'])) {
    require_once __DIR__ . '/db.php';

    $namaProduk = trim($_POST['nama_produk'] ?? '');
    $deskripsiProduk = trim($_POST['deskripsi_produk'] ?? '');
    $paketInput = trim($_POST['paket_produk'] ?? '');

    $daftarPaketData = [];

    if ($paketInput !== '') {
        $lines = preg_split("/\r\n|\n|\r/", $paketInput);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $parts = array_map('trim', explode('|', $line, 2));
            $label = $parts[0] ?? '';
            $price = $parts[1] ?? '';

            if ($label === '' && $price === '') {
                continue;
            }

            $daftarPaketData[] = [
                'nama' => $label,
                'harga' => $price,
            ];
        }
    }

    if ($daftarPaketData === []) {
        $daftarPaketJson = '[]';
    } else {
        $encoded = json_encode($daftarPaketData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $daftarPaketJson = $encoded !== false ? $encoded : '[]';
    }

    $namaProdukEsc = $conn->real_escape_string($namaProduk);
    $deskripsiProdukEsc = $conn->real_escape_string($deskripsiProduk);
    $daftarPaketEsc = $conn->real_escape_string($daftarPaketJson);

    $namaBerkas = $_FILES['nama_berkas_gambar']['name'] ?? '';
    $tempBerkas = $_FILES['nama_berkas_gambar']['tmp_name'] ?? '';
    $errorUpload = $_FILES['nama_berkas_gambar']['error'] ?? UPLOAD_ERR_NO_FILE;

    $targetDir = realpath(__DIR__ . '/gambar');
    if ($targetDir === false) {
        $targetDir = __DIR__ . '/gambar';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }
    }

    $targetFile = rtrim($targetDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . basename($namaBerkas);
    if (!is_writable($targetDir)) {
        echo "Folder upload tidak dapat diakses: $targetDir";
        exit;
    }

    if ($errorUpload === UPLOAD_ERR_OK && move_uploaded_file($tempBerkas, $targetFile)) {
        $namaBerkasEsc = $conn->real_escape_string($namaBerkas);
        $sql = "INSERT INTO produk (nama_produk, deskripsi_produk, daftar_paket, nama_berkas_gambar) VALUES ('$namaProdukEsc', '$deskripsiProdukEsc', '$daftarPaketEsc', '$namaBerkasEsc')";

        if ($conn->query($sql) === true) {
            echo "<script>alert('Produk berhasil ditambahkan.'); window.location.href='products.php';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Gagal mengunggah gambar: " . $errorUpload;
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tambah Produk - Novastore Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-950 text-white min-h-screen">
    <nav class="bg-gray-900 border-b border-gray-800">
        <div class="max-w-6xl mx-auto px-6 py-4 flex flex-wrap items-center justify-between gap-4">
            <a href="dashboard.php" class="text-xl font-semibold text-blue-400">Novastore Admin</a>
            <div class="flex flex-wrap gap-3 text-sm">
                <a href="dashboard.php" class="px-3 py-2 rounded-lg bg-gray-800 hover:bg-gray-700">Dashboard</a>
                <a href="products.php" class="px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700">Produk</a>
                <a href="orders.php" class="px-3 py-2 rounded-lg bg-gray-800 hover:bg-gray-700">Pesanan</a>
            </div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-6 py-12 space-y-8">
        <header class="space-y-2">
            <p class="text-sm uppercase tracking-wide text-blue-300">Tambah Produk</p>
            <h1 class="text-3xl font-bold text-white">Formulir Produk Baru</h1>
            <p class="text-gray-400 text-sm">Lengkapi data di bawah ini untuk menambahkan produk baru ke katalog.</p>
        </header>

        <section class="bg-gray-900 border border-gray-800 rounded-2xl shadow-lg p-8">
            <form action="" method="post" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label for="nama_produk" class="block text-sm font-semibold text-gray-200 mb-2">Nama Produk</label>
                    <input
                        type="text"
                        id="nama_produk"
                        name="nama_produk"
                        required
                        placeholder="Contoh: Mobile Legends"
                        class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <div>
                    <label for="deskripsi_produk" class="block text-sm font-semibold text-gray-200 mb-2">Deskripsi Produk</label>
                    <textarea
                        id="deskripsi_produk"
                        name="deskripsi_produk"
                        rows="4"
                        required
                        placeholder="Tuliskan penjelasan singkat tentang produk"
                        class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div>
                    <label for="paket_produk" class="block text-sm font-semibold text-gray-200 mb-2">Daftar Paket & Harga</label>
                    <textarea
                        id="paket_produk"
                        name="paket_produk"
                        rows="5"
                        placeholder="Contoh: 86 Diamonds | 27000"
                        class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    <p class="text-xs text-gray-500 mt-2">Masukkan satu paket per baris. Gunakan tanda | untuk memisahkan nama paket dan harga.</p>
                </div>

                <div>
                    <label for="nama_berkas_gambar" class="block text-sm font-semibold text-gray-200 mb-2">Gambar Produk</label>
                    <input
                        type="file"
                        id="nama_berkas_gambar"
                        name="nama_berkas_gambar"
                        accept="image/*"
                        required
                        class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <p class="text-xs text-gray-500 mt-2">Unggah gambar dengan format JPG/PNG/WebP.</p>
                </div>

                <div class="flex flex-wrap gap-3 justify-end">
                    <a href="products.php" class="px-4 py-2 rounded-lg bg-gray-800 hover:bg-gray-700 text-sm font-semibold">Batal</a>
                    <button type="submit" name="create" class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-sm font-semibold">Simpan Produk</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
