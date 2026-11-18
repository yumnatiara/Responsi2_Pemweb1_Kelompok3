<?php

function cekStatusTenggat($tgl_tenggat) {
    $hari_ini = new DateTime();
    $tenggat  = new DateTime($tgl_tenggat);
    $selisih  = $hari_ini->diff($tenggat);
    $jumlah_hari = (int)$selisih->format('%r%a'); // Negatif jika sudah lewat

    if ($jumlah_hari < 0) {
        return "<span class='badge-terlambat'>Terlambat " . abs($jumlah_hari) . " Hari!</span>";
    } elseif ($jumlah_hari <= 2) {
        return "<span class='badge-peringatan'>Segera Tenggat ($jumlah_hari Hari Lagi)</span>";
    } else {
        return "<span class='badge-aman'>Aman ($jumlah_hari Hari Lagi)</span>";
    }
}

function generateWAPesan($no_wa, $nama_peminjam, $nama_barang, $tgl_tenggat) {
    // Bersihkan nomor telepon (harus berawalan 62)
    $no_wa = preg_replace('/[^0-9]/', '', $no_wa);
    if (substr($no_wa, 0, 1) === '0') {
        $no_wa = '62' . substr($no_wa, 1);
    }
    
    $pesan = "Halo *{$nama_peminjam}*, pengingat ramah dari AssetTrack bahwa barang *{$nama_barang}* yang kamu pinjam memiliki tenggat pengembalian pada *{$tgl_tenggat}*. Terima kasih!";
    $url = "https://api.whatsapp.com/send?phone={$no_wa}&text=" . urlencode($pesan);
    return $url;
}
?>