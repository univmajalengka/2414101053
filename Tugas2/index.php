<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gunongan & Pinto Khop | Wisata Sejarah Aceh</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                boxShadow: {
                    soft: "0 10px 30px rgba(2,6,23,.10)"
                }
            }
        }
    }
    </script>
</head>

<body class="bg-slate-50 text-slate-900">
    <!-- Top Bar -->
    <div class="bg-slate-900 text-slate-100">
        <div class="mx-auto max-w-6xl px-4 py-2 flex items-center justify-between text-sm">
            <p class="opacity-90">Wisata Sejarah Aceh • Gunongan & Pinto Khop</p>
            <div class="flex items-center gap-3 opacity-90">
                <span class="hidden sm:inline">Jam buka: 08.00–17.00</span>
                <span class="hidden sm:inline">•</span>
                <span>Tiket: Mulai Rp10.000</span>
            </div>
        </div>
    </div>

    <!-- Navbar (Menu Aplikasi) -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b border-slate-200">
        <div class="mx-auto max-w-6xl px-4 py-4 flex items-center justify-between">
            <a href="#beranda" class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-2xl bg-slate-900 text-white grid place-items-center font-bold">GK</div>
                <div class="leading-tight">
                    <p class="font-semibold">Gunongan & Pinto Khop</p>
                    <p class="text-xs text-slate-500">Heritage Trip • Banda Aceh</p>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-6 text-sm">
                <a class="hover:text-slate-700" href="#galeri">Galeri</a>
                <a class="hover:text-slate-700" href="#tentang">Tentang</a>
                <a class="hover:text-slate-700" href="#paket">Paket Wisata</a>
                <a class="hover:text-slate-700" href="#video">Video</a>
                <a class="hover:text-slate-700" href="#kontak">Kontak</a>
            </nav>

            <div class="flex items-center gap-2">
                <a href="#paket"
                    class="hidden sm:inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-soft hover:bg-slate-800">
                    Lihat Paket
                </a>

                <!-- Mobile menu button -->
                <button id="btnMenu"
                    class="md:hidden inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm hover:bg-slate-50"
                    aria-label="Buka menu">
                    ☰
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobileMenu" class="md:hidden hidden border-t border-slate-200 bg-white">
            <div class="mx-auto max-w-6xl px-4 py-3 flex flex-col gap-3 text-sm">
                <a class="hover:text-slate-700" href="#galeri">Galeri</a>
                <a class="hover:text-slate-700" href="#tentang">Tentang</a>
                <a class="hover:text-slate-700" href="#paket">Paket Wisata</a>
                <a class="hover:text-slate-700" href="#video">Video</a>
                <a class="hover:text-slate-700" href="#kontak">Kontak</a>
            </div>
        </div>
    </header>

    <!-- Hero / Beranda -->
    <section id="beranda" class="mx-auto max-w-6xl px-4 pt-10 pb-6">
        <div class="grid lg:grid-cols-2 gap-8 items-center">
            <div>
                <p
                    class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm border border-slate-200 shadow-soft">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Rekomendasi wisata sejarah
                </p>

                <h1 class="mt-4 text-3xl sm:text-4xl font-extrabold tracking-tight">
                    Jelajahi Kisah Aceh di <span class="text-slate-900">Gunongan</span> & <span
                        class="text-slate-900">Pinto Khop</span>
                </h1>

                <p class="mt-4 text-slate-600 leading-relaxed">
                    Rasakan pengalaman wisata heritage yang modern: spot foto ikonik, tur singkat yang informatif, dan
                    paket wisata yang fleksibel
                    untuk keluarga, teman, atau rombongan kampus.
                </p>

                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <a href="#galeri"
                        class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-soft hover:bg-slate-800">
                        Lihat Galeri
                    </a>
                    <a href="#video"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold hover:bg-slate-50">
                        Tonton Video
                    </a>
                </div>

                <div class="mt-6 grid grid-cols-3 gap-3">
                    <div class="rounded-2xl bg-white border border-slate-200 p-4 shadow-soft">
                        <p class="text-xs text-slate-500">Durasi</p>
                        <p class="font-semibold">1–3 Jam</p>
                    </div>
                    <div class="rounded-2xl bg-white border border-slate-200 p-4 shadow-soft">
                        <p class="text-xs text-slate-500">Lokasi</p>
                        <p class="font-semibold">Banda Aceh</p>
                    </div>
                    <div class="rounded-2xl bg-white border border-slate-200 p-4 shadow-soft">
                        <p class="text-xs text-slate-500">Cocok untuk</p>
                        <p class="font-semibold">Keluarga</p>
                    </div>
                </div>
            </div>

            <!-- Gallery collage (Foto-foto kegiatan wisata / banner kolase) -->
            <div id="galeri" class="space-y-4">
                <div class="rounded-3xl bg-gradient-to-br from-slate-900 to-slate-700 p-[1px] shadow-soft">
                    <div class="rounded-3xl bg-white p-4">
                        <div class="flex items-center justify-between">
                            <h2 class="font-semibold">Galeri Kegiatan</h2>
                            <span class="text-xs text-slate-500">Kolase • Responsif</span>
                        </div>

                        <div class="mt-4 grid grid-cols-6 grid-rows-6 gap-3 h-[360px] sm:h-[420px]">
                            <!-- NOTE: Ganti src dengan foto kamu sendiri kalau sudah ada -->
                            <img class="col-span-4 row-span-4 h-full w-full rounded-2xl object-cover"
                                src="https://images.unsplash.com/photo-1526481280695-3c687fd5432c?auto=format&fit=crop&w=1200&q=70"
                                alt="Wisata sejarah - spot utama" />

                            <img class="col-span-2 row-span-2 h-full w-full rounded-2xl object-cover"
                                src="https://images.unsplash.com/photo-1526772662000-3f88f10405ff?auto=format&fit=crop&w=1200&q=70"
                                alt="Tur sejarah - berjalan" />

                            <img class="col-span-2 row-span-2 h-full w-full rounded-2xl object-cover"
                                src="https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=1200&q=70"
                                alt="Kegiatan wisata - foto bersama" />

                            <img class="col-span-3 row-span-2 h-full w-full rounded-2xl object-cover"
                                src="https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=1200&q=70"
                                alt="Arsitektur - detail" />

                            <img class="col-span-3 row-span-2 h-full w-full rounded-2xl object-cover"
                                src="https://images.unsplash.com/photo-1542640244-7e672d6cef4e?auto=format&fit=crop&w=1200&q=70"
                                alt="Ruang terbuka - suasana" />
                        </div>

                        <p class="mt-4 text-sm text-slate-600">
                            Kegiatan yang bisa kamu lakukan: <span class="font-semibold">tur sejarah singkat</span>,
                            <span class="font-semibold">hunting foto</span>,
                            <span class="font-semibold">konten reels</span>, dan <span class="font-semibold">tugas
                                observasi heritage</span>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang -->
    <section id="tentang" class="mx-auto max-w-6xl px-4 py-10">
        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1 rounded-3xl bg-white border border-slate-200 p-6 shadow-soft">
                <h3 class="text-lg font-bold">Tentang Destinasi</h3>
                <p class="mt-3 text-slate-600 text-sm leading-relaxed">
                    <span class="font-semibold">Gunongan</span> dikenal sebagai bangunan bersejarah yang ikonik,
                    sedangkan
                    <span class="font-semibold">Pinto Khop</span> menjadi spot heritage yang menarik untuk memahami
                    jejak budaya Aceh.
                    Halaman ini dibuat untuk promosi wisata sejarah dengan tampilan modern dan mudah dipahami.
                </p>
            </div>

            <div class="lg:col-span-2 grid sm:grid-cols-2 gap-6">
                <div class="rounded-3xl bg-white border border-slate-200 p-6 shadow-soft">
                    <p class="text-sm font-semibold">Fasilitas</p>
                    <ul class="mt-3 space-y-2 text-sm text-slate-600">
                        <li>• Area foto & spot landmark</li>
                        <li>• Jalur jalan kaki (heritage walk)</li>
                        <li>• Parkir & area istirahat</li>
                        <li>• Rekomendasi guide lokal (opsional)</li>
                    </ul>
                </div>
                <div class="rounded-3xl bg-white border border-slate-200 p-6 shadow-soft">
                    <p class="text-sm font-semibold">Tips Berkunjung</p>
                    <ul class="mt-3 space-y-2 text-sm text-slate-600">
                        <li>• Datang pagi/sore untuk cahaya foto bagus</li>
                        <li>• Pakai outfit nyaman untuk jalan</li>
                        <li>• Siapkan air minum</li>
                        <li>• Hormati area bersejarah (jaga kebersihan)</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Paket Wisata (daftar paket + image + deskripsi) -->
    <section id="paket" class="mx-auto max-w-6xl px-4 pb-12">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight">Daftar Paket Wisata</h2>
                <p class="mt-2 text-slate-600">Pilih paket sesuai kebutuhan (solo, keluarga, atau rombongan kampus).</p>
            </div>
            <a href="#kontak"
                class="hidden sm:inline-flex rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50">
                Tanya Admin
            </a>
        </div>

        <div class="mt-6 grid md:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <article class="rounded-3xl bg-white border border-slate-200 overflow-hidden shadow-soft">
                <img class="h-44 w-full object-cover"
                    src="https://images.unsplash.com/photo-1523413651479-597eb2da0ad6?auto=format&fit=crop&w=1200&q=70"
                    alt="Paket Gunongan" />
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold">Paket Gunongan Highlight</h3>
                        <span class="text-xs font-semibold rounded-full bg-slate-900 text-white px-3 py-1">1 Jam</span>
                    </div>
                    <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                        Fokus eksplor Gunongan: sejarah singkat, spot foto utama, dan rute santai cocok untuk keluarga.
                    </p>

                    <div class="mt-4 flex items-center justify-between">
                        <p class="font-extrabold">Rp 25.000<span
                                class="text-sm font-normal text-slate-500">/orang</span></p>
                        <button
                            class="btnPesan rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800"
                            data-paket="Paket Gunongan Highlight">
                            Pesan
                        </button>
                    </div>
                </div>
            </article>

            <!-- Card 2 -->
            <article class="rounded-3xl bg-white border border-slate-200 overflow-hidden shadow-soft">
                <img class="h-44 w-full object-cover"
                    src="https://images.unsplash.com/photo-1533106418989-88406c7cc8ca?auto=format&fit=crop&w=1200&q=70"
                    alt="Paket Pinto Khop" />
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold">Paket Pinto Khop Walk</h3>
                        <span class="text-xs font-semibold rounded-full bg-slate-900 text-white px-3 py-1">1.5
                            Jam</span>
                    </div>
                    <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                        Heritage walk area Pinto Khop: penjelasan budaya, sudut arsitektur, dan dokumentasi konten.
                    </p>

                    <div class="mt-4 flex items-center justify-between">
                        <p class="font-extrabold">Rp 35.000<span
                                class="text-sm font-normal text-slate-500">/orang</span></p>
                        <button
                            class="btnPesan rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800"
                            data-paket="Paket Pinto Khop Walk">
                            Pesan
                        </button>
                    </div>
                </div>
            </article>

            <!-- Card 3 -->
            <article class="rounded-3xl bg-white border border-slate-200 overflow-hidden shadow-soft">
                <img class="h-44 w-full object-cover"
                    src="https://images.unsplash.com/photo-1489515217757-5fd1be406fef?auto=format&fit=crop&w=1200&q=70"
                    alt="Paket Kombinasi" />
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold">Paket Kombo Heritage</h3>
                        <span class="text-xs font-semibold rounded-full bg-emerald-600 text-white px-3 py-1">Best</span>
                    </div>
                    <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                        Paket lengkap: Gunongan + Pinto Khop. Termasuk rute efisien, sesi foto, dan opsi guide lokal.
                    </p>

                    <div class="mt-4 flex items-center justify-between">
                        <p class="font-extrabold">Rp 55.000<span
                                class="text-sm font-normal text-slate-500">/orang</span></p>
                        <button
                            class="btnPesan rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700"
                            data-paket="Paket Kombo Heritage">
                            Pesan
                        </button>
                    </div>
                </div>
            </article>
        </div>
    </section>

    <!-- Link Video YouTube -->
    <section id="video" class="mx-auto max-w-6xl px-4 pb-12">
        <div class="rounded-3xl bg-white border border-slate-200 p-6 shadow-soft">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
                <div>
                    <h2 class="text-2xl font-extrabold tracking-tight">Video Promosi</h2>
                    <p class="mt-2 text-slate-600">
                        Klik untuk menonton referensi video dari YouTube (sesuai link yang kamu berikan).
                    </p>
                </div>
                <a target="_blank" rel="noopener" href="https://youtu.be/buO7B6kTSVE?si=_Qlht3Q-hVY23m43"
                    class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">
                    Buka di YouTube →
                </a>
            </div>

            <div class="mt-6 aspect-video overflow-hidden rounded-2xl bg-slate-100 border border-slate-200">
                <!-- Embed (ambil id video: buO7B6kTSVE) -->
                <iframe class="h-full w-full" src="https://www.youtube.com/embed/buO7B6kTSVE" title="Video Promosi"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen></iframe>
            </div>
        </div>
    </section>

    <!-- Kontak -->
    <section id="kontak" class="mx-auto max-w-6xl px-4 pb-14">
        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1 rounded-3xl bg-white border border-slate-200 p-6 shadow-soft">
                <h3 class="text-lg font-bold">Kontak & Lokasi</h3>
                <p class="mt-3 text-sm text-slate-600 leading-relaxed">
                    Isi data admin (WA/IG) dan alamat detail sesuai kebutuhan tugasmu.
                </p>
                <div class="mt-4 space-y-2 text-sm">
                    <p><span class="font-semibold">Admin:</span> (Isi Nama)</p>
                    <p><span class="font-semibold">WhatsApp:</span> (08xx-xxxx-xxxx)</p>
                    <p><span class="font-semibold">Instagram:</span> @gunonganheritage</p>
                    <p><span class="font-semibold">Alamat:</span> Banda Aceh, Aceh</p>
                </div>
            </div>

            <div class="lg:col-span-2 rounded-3xl bg-white border border-slate-200 p-6 shadow-soft">
                <h3 class="text-lg font-bold">Form Minat Pemesanan</h3>
                <p class="mt-2 text-sm text-slate-600">Form sederhana (opsional) untuk melengkapi UI promosi.</p>

                <form class="mt-5 grid sm:grid-cols-2 gap-4" onsubmit="return false;">
                    <div>
                        <label class="text-sm font-semibold">Nama</label>
                        <input
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/20"
                            placeholder="Masukkan nama" />
                    </div>
                    <div>
                        <label class="text-sm font-semibold">No. HP</label>
                        <input
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/20"
                            placeholder="08xx..." />
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-sm font-semibold">Paket Pilihan</label>
                        <input id="paketTerpilih"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/20"
                            placeholder="Klik tombol Pesan pada paket" />
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-sm font-semibold">Catatan</label>
                        <textarea rows="4"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-slate-900/20"
                            placeholder="Contoh: tanggal kunjungan, jumlah orang, dll."></textarea>
                    </div>

                    <div class="sm:col-span-2 flex flex-col sm:flex-row gap-3">
                        <button
                            class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">
                            Kirim (Dummy)
                        </button>
                        <a class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold hover:bg-slate-50"
                            href="#beranda">
                            Kembali ke Atas
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-white">
        <div
            class="mx-auto max-w-6xl px-4 py-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <p class="text-sm text-slate-500">
                © <span id="year"></span> Gunongan & Pinto Khop • UI Promosi Wisata Sejarah
            </p>
            <div class="flex gap-4 text-sm">
                <a class="text-slate-600 hover:text-slate-900" href="#galeri">Galeri</a>
                <a class="text-slate-600 hover:text-slate-900" href="#paket">Paket</a>
                <a class="text-slate-600 hover:text-slate-900" href="#video">Video</a>
            </div>
        </div>
    </footer>

    <script>
    // Tahun otomatis
    document.getElementById("year").textContent = new Date().getFullYear();

    // Mobile menu
    const btnMenu = document.getElementById("btnMenu");
    const mobileMenu = document.getElementById("mobileMenu");
    btnMenu?.addEventListener("click", () => mobileMenu.classList.toggle("hidden"));

    // Tombol "Pesan" -> isi paket ke form
    const paketTerpilih = document.getElementById("paketTerpilih");
    document.querySelectorAll(".btnPesan").forEach(btn => {
        btn.addEventListener("click", () => {
            paketTerpilih.value = btn.getAttribute("data-paket") || "";
            document.getElementById("kontak").scrollIntoView({
                behavior: "smooth"
            });
        });
    });
    </script>
</body>

</html>