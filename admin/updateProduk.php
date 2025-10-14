<?php
include '../config/db.php';

function parsePackageInput(string $input): array
{
    $result = [];
    $lines = preg_split("/\r\n|\n|\r/", $input);
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

        $result[] = [
            'nama' => $label,
            'harga' => $price,
        ];
    }

    return $result;
}

function packagesToTextarea(array $packages): string
{
    $lines = [];
    foreach ($packages as $item) {
        if (!is_array($item)) {
            continue;
        }

        $label = trim((string) ($item['nama'] ?? $item['label'] ?? ''));
        $price = trim((string) ($item['harga'] ?? $item['price'] ?? ''));

        if ($label !== '' && $price !== '') {
            $lines[] = $label . ' | ' . $price;
        } elseif ($label !== '') {
            $lines[] = $label;
        } elseif ($price !== '') {
            $lines[] = '| ' . $price;
        }
    }

    return implode(PHP_EOL, $lines);
}

function formatPackagesForTextarea(?string $raw): string
{
    if ($raw === null) {
        return '';
    }

    $raw = trim($raw);
    if ($raw === '') {
        return '';
    }

    $decoded = json_decode($raw, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        return packagesToTextarea($decoded);
    }

    return $raw;
}

$redirectUrl = 'products.php';
$idProduk = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$produk = null;
$paketTextareaValue = '';

if ($idProduk <= 0) {
    echo "<script>alert('ID produk tidak valid.'); window.location.href='$redirectUrl';</script>";
    $conn->close();
    exit;
}

$sql = "SELECT id_produk, nama_produk, deskripsi_produk, nama_berkas_gambar, daftar_paket FROM produk WHERE id_produk = $idProduk LIMIT 1";
$result = $conn->query($sql);
if ($result && $result->num_rows === 1) {
    $produk = $result->fetch_assoc();
    $paketTextareaValue = formatPackagesForTextarea($produk['daftar_paket'] ?? '');
} else {
    echo "<script>alert('Produk tidak ditemukan.'); window.location.href='$redirectUrl';</script>";
    $conn->close();
    exit;
}

if (isset($_POST['update'])) {
    $idProduk = (int) ($_POST['id_produk'] ?? 0);
    $namaProduk = trim($_POST['nama_produk'] ?? '');
    $deskripsiProduk = trim($_POST['deskripsi_produk'] ?? '');
    $paketInput = trim($_POST['paket_produk'] ?? '');

    $daftarPaketData = parsePackageInput($paketInput);
    if ($daftarPaketData === []) {
        $daftarPaketJson = '[]';
    } else {
        $encodedPackages = json_encode($daftarPaketData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $daftarPaketJson = $encodedPackages !== false ? $encodedPackages : '[]';
    }

    $paketTextareaValue = packagesToTextarea($daftarPaketData);

    $namaProdukEsc = $conn->real_escape_string($namaProduk);
    $deskripsiProdukEsc = $conn->real_escape_string($deskripsiProduk);
    $daftarPaketEsc = $conn->real_escape_string($daftarPaketJson);

    $produk['nama_produk'] = $namaProduk;
    $produk['deskripsi_produk'] = $deskripsiProduk;
    $produk['daftar_paket'] = $daftarPaketJson;

    $namaBerkasBaru = $_FILES['nama_berkas_gambar']['name'] ?? '';
    $tempBerkasBaru = $_FILES['nama_berkas_gambar']['tmp_name'] ?? '';
    $errorUpload = $_FILES['nama_berkas_gambar']['error'] ?? UPLOAD_ERR_NO_FILE;

    $targetDir = realpath(__DIR__ . '/gambar');
    if ($targetDir === false) {
        $targetDir = __DIR__ . '/gambar';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }
    }

    if (!is_writable($targetDir)) {
        echo "Folder upload tidak dapat diakses: $targetDir";
        $conn->close();
        exit;
    }

    $updateBerhasil = false;

    if ($errorUpload === UPLOAD_ERR_OK && $namaBerkasBaru !== '') {
        $targetFile = rtrim($targetDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . basename($namaBerkasBaru);
        if (move_uploaded_file($tempBerkasBaru, $targetFile)) {
            $namaBerkasEsc = $conn->real_escape_string($namaBerkasBaru);
            $sqlUpdate = "UPDATE produk SET nama_produk='$namaProdukEsc', deskripsi_produk='$deskripsiProdukEsc', daftar_paket='$daftarPaketEsc', nama_berkas_gambar='$namaBerkasEsc' WHERE id_produk=$idProduk";
            $updateBerhasil = $conn->query($sqlUpdate) === true;

            if ($updateBerhasil && !empty($produk['nama_berkas_gambar'])) {
                $oldFileAdmin = __DIR__ . '/gambar/' . $produk['nama_berkas_gambar'];
                if (file_exists($oldFileAdmin)) {
                    @unlink($oldFileAdmin);
                }
            }

            if ($updateBerhasil) {
                $produk['nama_berkas_gambar'] = $namaBerkasBaru;
            }
        } else {
            echo "Gagal mengunggah gambar.";
        }
    } else {
        $sqlUpdate = "UPDATE produk SET nama_produk='$namaProdukEsc', deskripsi_produk='$deskripsiProdukEsc', daftar_paket='$daftarPaketEsc' WHERE id_produk=$idProduk";
        $updateBerhasil = $conn->query($sqlUpdate) === true;
    }

    if ($updateBerhasil) {
        echo "<script>alert('Produk berhasil diperbarui.'); window.location.href='$redirectUrl';</script>";
        $conn->close();
        exit;
    } else {
        echo "Terjadi kesalahan: " . $conn->error;
    }
}

$currentImage = $produk['nama_berkas_gambar'] ?? '';
$imageUrl = '';
if ($currentImage !== '') {
    $adminRelative = 'gambar/' . rawurlencode($currentImage);
    $rootRelative = '../gambar/' . rawurlencode($currentImage);
    if (file_exists(__DIR__ . '/gambar/' . $currentImage)) {
        $imageUrl = $adminRelative;
    } elseif (file_exists(dirname(__DIR__) . '/gambar/' . $currentImage)) {
        $imageUrl = $rootRelative;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Produk - Novastore Admin</title>
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
            <p class="text-sm uppercase tracking-wide text-blue-300">Edit Produk</p>
            <h1 class="text-3xl font-bold text-white">Perbarui Informasi Produk</h1>
            <p class="text-gray-400 text-sm">Ubah detail produk atau unggah gambar baru jika diperlukan.</p>
        </header>

        <section class="bg-gray-900 border border-gray-800 rounded-2xl shadow-lg p-8">
            <form action="updateProduk.php?id=<?php echo htmlspecialchars($produk['id_produk']); ?>" method="post"
                enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="id_produk" value="<?php echo htmlspecialchars($produk['id_produk']); ?>" />

                <div>
                    <label for="nama_produk" class="block text-sm font-semibold text-gray-200 mb-2">Nama Produk</label>
                    <input type="text" id="nama_produk" name="nama_produk" required
                        value="<?php echo htmlspecialchars($produk['nama_produk']); ?>"
                        class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <div>
                    <label for="deskripsi_produk" class="block text-sm font-semibold text-gray-200 mb-2">Deskripsi
                        Produk</label>
                    <textarea id="deskripsi_produk" name="deskripsi_produk" rows="4" required
                        class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($produk['deskripsi_produk']); ?></textarea>
                </div>

                <div>
                    <label for="paket_produk" class="block text-sm font-semibold text-gray-200 mb-2">Daftar Paket & Harga</label>
                    <textarea id="paket_produk" name="paket_produk" rows="5"
                        class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($paketTextareaValue); ?></textarea>
                    <p class="text-xs text-gray-500 mt-2">Masukkan satu paket per baris. Gunakan tanda | untuk memisahkan nama paket dan harga.</p>
                </div>

                <div>
                    <label for="nama_berkas_gambar" class="block text-sm font-semibold text-gray-200 mb-2">Gambar
                        Produk</label>
                    <input type="file" id="nama_berkas_gambar" name="nama_berkas_gambar" accept="image/*"
                        class="w-full px-4 py-3 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <p class="text-xs text-gray-500 mt-2">Biarkan kosong jika tidak ingin mengganti gambar.</p>

                    <?php if ($imageUrl !== ''): ?>
                    <div class="mt-4 flex items-start gap-4">
                        <img src="<?php echo $imageUrl; ?>" alt="Gambar Produk"
                            class="w-28 h-28 object-cover rounded-lg border border-gray-800" />
                        <div class="text-xs text-gray-400">
                            <p class="font-semibold text-gray-200">Gambar saat ini</p>
                            <p><?php echo htmlspecialchars($produk['nama_berkas_gambar']); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="flex flex-wrap gap-3 justify-end">
                    <a href="<?php echo $redirectUrl; ?>"
                        class="px-4 py-2 rounded-lg bg-gray-800 hover:bg-gray-700 text-sm font-semibold">Batal</a>
                    <button type="submit" name="update"
                        class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-sm font-semibold">Simpan
                        Perubahan</button>
                </div>
            </form>
        </section>
    </main>
    <?php $conn->close(); ?>
</body>

</html>
