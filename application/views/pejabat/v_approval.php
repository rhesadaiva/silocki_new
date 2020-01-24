<!-- MAIN -->
<div class="main">
    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Tabel Kontrak Bawahan -->
            <div class="kontrakBawahanList">
                <div class="panel panel-headline panel-primary">
                    <div class="panel-heading panel-title">
                        <div class="col-sm">
                            <h3 style="margin-top: -10px; margin-bottom: -10px;"><?= $title; ?></h3>
                        </div>
                        <div class="col-sm">
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-hover table-bordered" id="kinerjaBawahanTable">
                            <thead>
                                <tr class="success">
                                    <th scope="col">#</th>
                                    <th scope="col" class="text-center">Nomor Kontrak Kinerja</th>
                                    <th scope="col" class="text-center">Pemilik Kontrak Kinerja</th>
                                    <th scope="col" class="text-center">Periode Kontrak Kinerja</th>
                                    <th scope="col" class="text-center">Status Validasi</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                <?php foreach ($kontrakKinerjaBawahan as $kontrak) : ?>
                                    <tr>
                                        <th scope="row"><?= $i; ?></th>
                                        <td class="text-center"><?= $kontrak['nomorkk'] ?></td>
                                        <td class="text-center"><?= $kontrak['nama'] ?></td>
                                        <td class="text-center"><?= indonesian_date3($kontrak['tanggalmulai']) ?> s.d <?= indonesian_date3($kontrak['tanggalselesai']) ?> </td>
                                        <td class="text-center"><?= $kontrak['validasi_ket'] ?></td>
                                        <td>
                                            <?php if ($kontrak['is_validated'] == 1) : ?>
                                                <button class="btn btn-success btn-sm btnApproveKontrak" onclick="doApproveKontrak('<?= $kontrak['id_kontrak'] ?>');"><i class="fas fa-fw fa-thumbs-up"></i> Setujui Kontrak</button>

                                            <?php else : ?>
                                                <button class="btn btn-primary btn-sm btnDetailKontrak" name="btnDetailKontrak" onclick="detailKontrakKinerja('<?= $kontrak['id_kontrak'] ?>')"><i class="fas fa-fw fa-search"></i> Detail Kontrak </button>

                                                <button class="btn btn-danger btn-sm btnRejectKontrak" onclick="doRejectKontrak('<?= $kontrak['id_kontrak'] ?>');"><i class="fas fa-fw fa-thumbs-down"></i> Batalkan Persetujuan Kontrak</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="loadingDetailKontrakAnimation hidden">
                <h2><i class="fa fa-cog fa-spin"></i> Loading...</h2>
            </div>

            <!-- Table Detail Kontrak -->
            <div class="detailKontrakContent hidden">
                <div class="panel panel-headline panel-primary">
                    <div class="panel-heading panel-title">
                        <div class="col-sm">
                            <h3 style="margin-top: -10px; margin-bottom: -10px;">Identitas Kontrak Kinerja</h3>
                            <a class="btn btn-warning btn-sm pull-right" style="margin-top: -20px;" onclick="returnKontrakBawahan();"><i class="fas fa-fw fa-undo"></i> Kembali</a>
                        </div>
                        <div class="col-sm">
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table" id="panelDetailKontrak">
                            <tbody class="masterKontrakBawahan">

                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tabel List IKU -->
                <div class="panel panel-headline panel-primary">
                    <div class="panel-heading panel-title">
                        <div class="col-sm">
                            <h3 style="margin-top: -10px; margin-bottom: -10px;">Daftar IKU Bawahan</h3>
                        </div>
                        <div class="col-sm">
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-hover table-bordered" id="detailKontrakTable">
                            <thead>
                                <tr class="success">
                                    <th scope="col" class="text-center">Nomor IKU</th>
                                    <th scope="col" class="text-center">Nama IKU</th>
                                    <th scope="col" class="text-center">Target IKU</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="listIKUData">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <!-- MODAL LOGBOOK -->
        <div class="modal fade" id="modalLogbookBawahan" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg" role="document" style="width: 1366px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-primary"><b>Logbook Bawahan</b></h4>
                    </div>
                    <div class="modal-body">
                        <div class="loadingAnimation hidden">
                            <h2><i class="fa fa-cog fa-spin"></i> Loading...</h2>
                        </div>
                        <div class="ikuLogbookContent">
                            <!-- PANEL MASTER IKU -->
                            <div class="panel panel-default panel-primary" id="panelIKU" style="margin-bottom: 20px;">
                                <div class="panel-heading">
                                    <h3 class="panel-title" style="margin-top: -10px; margin-bottom: -10px;"><b>Data Indikator Kinerja Utama</b></h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table">
                                        <tbody class="masterIKU">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- PANEL LOGBOOK -->
                            <div class="panel panel-default panel-success" id="panelLogbook">
                                <div class="panel-heading">
                                    <h3 class="panel-title" style="margin-top: -10px; margin-bottom: -10px;"><b>Data Logbook Pegawai</b></h3>
                                </div>
                                <div class="panel-body">
                                    <!-- Logbook -->
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr class="success">
                                                <th scope="col" class="text-center">Periode Pelaporan</th>
                                                <th scope="col" class="text-center">Perhitungan</th>
                                                <th scope="col" class="text-center">Realisasi Periode Pelaporan</th>
                                                <th scope="col" class="text-center">Realisasi s.d Periode Pelaporan</th>
                                                <th scope="col" class="text-center">Keterangan</th>
                                                <th scope="col" class="text-center" style="width:10%;">Waktu Rekam</th>
                                                <th scope="col" class="text-center" style="width:10%;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="logbookBawahanData">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" onclick="closeModalLogbookBawahan()">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END MAIN CONTENT -->
</div>
<!-- END MAIN -->