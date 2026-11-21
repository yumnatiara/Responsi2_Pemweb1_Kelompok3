document.addEventListener("DOMContentLoaded", function () {
    // 1. Validasi Otomatis Tanggal Tenggat (pinjamkan.php & pinjam_masuk.php)
    // Memastikan tanggal tenggat tidak bisa dipilih sebelum tanggal pinjam
    const inputTglPinjam = document.querySelector('input[name="tgl_pinjam"], input[name="tanggal_pinjam"]');
    const inputTglTenggat = document.querySelector('input[name="tgl_tenggat"], input[name="tanggal_tenggat"]');

    if (inputTglPinjam && inputTglTenggat) {
        const sinkronTanggal = () => {
            if (inputTglPinjam.value) {
                inputTglTenggat.min = inputTglPinjam.value;
                if (inputTglTenggat.value && inputTglTenggat.value < inputTglPinjam.value) {
                    inputTglTenggat.value = inputTglPinjam.value;
                }
            }
        };
        sinkronTanggal();
        inputTglPinjam.addEventListener("change", sinkronTanggal);
    }

    // 2. Pencarian Cepat (Live Search) Otomatis pada Tabel Data (index.php, barang.php, riwayat.php, kategori.php)
    const tables = document.querySelectorAll("main table");
    tables.forEach((table) => {
        const rows = table.querySelectorAll("tr");
        // Pasang search bar jika tabel memiliki minimal 2 baris data
        if (rows.length > 2) {
            const searchWrapper = document.createElement("div");
            searchWrapper.style.marginBottom = "12px";

            const searchInput = document.createElement("input");
            searchInput.type = "text";
            searchInput.placeholder = "Cari nama barang, peminjam, kategori, atau status...";
            searchInput.style.maxWidth = "380px";
            searchInput.style.padding = "9px 14px";
            searchInput.style.fontSize = "0.9rem";

            searchWrapper.appendChild(searchInput);
            table.parentNode.insertBefore(searchWrapper, table);

            searchInput.addEventListener("keyup", function () {
                const keyword = this.value.toLowerCase();
                rows.forEach((row, index) => {
                    if (index === 0) return; // Lewati baris header <th>
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(keyword) ? "" : "none";
                });
            });
        }
    });

    // 3. Penghitung Karakter Real-Time pada Catatan Kerusakan / Deskripsi (pengembalian.php & barang_tambah.php)
    const textareas = document.querySelectorAll('textarea[name="catatan_kerusakan"], textarea[name="deskripsi"]');
    textareas.forEach((ta) => {
        const maxLen = 250;
        ta.maxLength = maxLen;

        const counter = document.createElement("small");
        counter.style.display = "block";
        counter.style.color = "#64748B";
        counter.style.marginTop = "4px";
        counter.style.fontSize = "0.82rem";
        counter.textContent = `${ta.value.length} / ${maxLen} karakter`;

        ta.parentNode.insertBefore(counter, ta.nextSibling);

        ta.addEventListener("input", function () {
            counter.textContent = `${this.value.length} / ${maxLen} karakter`;
        });
    });

    // 4. Penanda Menu Aktif Otomatis di Navbar (navbar.php)
    const currentPage = window.location.pathname.split("/").pop() || "index.php";
    const navLinks = document.querySelectorAll("header nav a");
    navLinks.forEach((link) => {
        const href = link.getAttribute("href");
        if (href === currentPage) {
            link.style.color = "#F59E0B";
            link.style.backgroundColor = "rgba(255, 255, 255, 0.08)";
        }
    });
});