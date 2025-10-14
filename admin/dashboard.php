<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Novastore Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-950 text-white min-h-screen">
    <nav class="bg-gray-900 border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-6 py-4 flex flex-wrap items-center justify-between gap-4">
            <a href="dashboard.php" class="text-xl font-semibold text-blue-400">Novastore Admin</a>
            <div class="flex flex-wrap gap-3 text-sm">
                <a href="dashboard.php" class="px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700">Dashboard</a>
                <a href="products.php" class="px-3 py-2 rounded-lg bg-gray-800 hover:bg-gray-700">Produk</a>
                <a href="orders.php" class="px-3 py-2 rounded-lg bg-gray-800 hover:bg-gray-700">Pesanan</a>
                <a href="createProduk.php" class="px-3 py-2 rounded-lg bg-green-600 hover:bg-green-700">Tambah
                    Produk</a>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-6 py-14 space-y-10">
        <header class="space-y-3">
            <p class="text-sm uppercase tracking-wide text-blue-300">Dashboard</p>
            <h1 class="text-3xl md:text-4xl font-bold text-white">Manajemen Data Novastore</h1>
            <p class="text-gray-400 text-sm md:text-base">Pilih area yang ingin dikelola. Tabel produk dan tabel pesanan
                tersedia di halaman terpisah.</p>
        </header>

        <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <article class="bg-gray-900 border border-gray-800 rounded-2xl p-6 shadow-lg flex flex-col gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-white">Tabel Produk</h2>
                    <p class="text-gray-400 text-sm mt-2">Kelola daftar produk yang tampil kepada pengguna: tambah,
                        ubah, atau hapus item.</p>
                </div>
                <a href="products.php"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-sm font-semibold">Buka
                    Halaman Produk</a>
            </article>

            <article class="bg-gray-900 border border-gray-800 rounded-2xl p-6 shadow-lg flex flex-col gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-white">Tabel Pesanan</h2>
                    <p class="text-gray-400 text-sm mt-2">Pantau transaksi yang masuk, cek status, dan tindak lanjuti
                        permintaan pelanggan.</p>
                </div>
                <a href="orders.php"
                    class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-sm font-semibold">Buka
                    Halaman Pesanan</a>
            </article>
        </section>
    </main>
</body>

</html>