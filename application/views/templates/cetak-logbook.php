<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Data Logbook Pegawai</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/cetak-style.css') ?>">
    <link href="<?= base_url('assets/') ?>/img/favicon.ico" rel="shortcut icon">
</head>

<body>


    <div class="row">
        <div class="text-center">
            <b class="title">
                BUKU CATATAN <em>(LOGBOOK)</em>
                <br>
                CAPAIAN KINERJA PEGAWAI TAHUN <?= $cetaklogbook['tahun_logbook']; ?>
            </b>
        </div>
    </div>

    <br>

    <!-- Tabel Identitas -->
    <table class="Identitas">
        <tr>
            <th class="identitas">Nama</th>
            <td>:</td>
            <td><?= $cetaklogbook['nama']; ?></td>
        </tr>
        <tr>
            <th class="identitas">NIP</th>
            <td>:</td>
            <td><?= $cetaklogbook['nip']; ?></td>
        </tr>
        <tr>
            <th class="identitas">Jabatan</th>
            <td>:</td>
            <td><?= $cetaklogbook['level']; ?> pada <?= $cetaklogbook['seksi']; ?></td>
        </tr>
        <tr>
            <th class="identitas">Nama IKU</th>
            <td>:</td>
            <td><?= $cetaklogbook['namaiku']; ?></td>
        </tr>
    </table>

    <br>

    <!-- Tabel Logbook -->
    <table border="1" cellspacing="0" rules="all" class="logbook-form">
        <thead class="tabel-head">
            <tr>
                <td class="nomor">No</td>
                <td class="periodepelaporan">Periode Pelaporan</td>
                <td class="formula">Formula</td>
                <!-- <td class="komponendata">Komponen Data</td> -->
                <td class="perhitungan">Perhitungan</td>
                <td class="realisasibulanpelaporan">Realisasi Pada Bulan Pelaporan</td>
                <td class="realisasisdbulanpelaporan">Realisasi s.d Bulan Pelaporan</td>
                <td class="target">Target</td>
                <td class="keterangan">Keterangan</td>
                <td class="parafatasan">Paraf Atasan Langsung</td>
            </tr>
        </thead>

        <tbody>
            <?php $i = 1; ?>

            <tr class="data-logbook">
                <td><?= $i; ?></td>
                <td><?= $cetaklogbook['periode']; ?></td>
                <td class="formula"><?= $cetaklogbook['formulaiku']; ?></td>
                <!-- <td></td> -->
                <td class="perhitungan"><?= $cetaklogbook['perhitungan']; ?></td>
                <td><?= $cetaklogbook['realisasibulan']; ?></td>
                <td><?= $cetaklogbook['realisasiterakhir']; ?></td>
                <td class="target"><?= $cetaklogbook['targetiku']; ?></td>
                <td class="keterangan"><?= $cetaklogbook['ket']; ?></td>
                <?php if ($cetaklogbook['tgl_approve'] === NULL) : ?>
                    <td class="paraf">Logbook belum disetujui oleh atasan.</td>
                <?php else : ?>
                    <td class="paraf"><?= indonesian_date2($cetaklogbook['tgl_approve']); ?></td>
                <?php endif; ?>
            </tr>

        </tbody>
    </table>
    <!-- End Table Logbook -->
    <p class="esigned"><em>Dokumen ini sudah di-generate secara otomatis oleh sistem, tidak membutuhkan tanda tangan basah dari atasan anda.</em></p>
</body>

</html>