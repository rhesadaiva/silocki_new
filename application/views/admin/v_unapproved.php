<div class="main">
    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Tabel -->
            <div class="main-content unapproved">
                <div class="panel panel-headline panel-primary" id="unapproved-content">
                    <div class="panel-heading panel-title">
                        <div class="col-sm">
                            <h3 style="margin-top: -10px; margin-bottom: -10px;"><?= $title; ?></h3>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="filter" style="margin-bottom: 20px; margin-top: 10px;">
                            <select class="selectpicker" name="refBulan" data-width="fit" id="refBulan">
                                <?php foreach ($refBulan as $Bulan) : ?>
                                    <option value="<?= $Bulan['Bulan_ket'] ?>"><?= $Bulan['Bulan_ket'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn btn-primary btn-sm">Filter</button>
                            <button class="btn btn-default btn-sm">Reset</button>
                        </div>
                        <table class="table table-striped table-hover table-bordered" id="unapprovedTable">
                            <thead>
                                <tr class="success">
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center">Nama Pegawai</th>
                                    <th scope="col" class="text-center">Status Logbook</th>
                                    <th scope="col" class="text-center">Periode</th>
                                    <th scope="col" class="text-center">Detail</th>
                                </tr>
                            </thead>
                            <tbody id="unapproved-data">
                                <?php $i = 1; ?>
                                <?php foreach ($notValidated as $unapproved) : ?>
                                    <tr>
                                        <th scope="row" class="text-center"><?= $i ?></th>
                                        <td><?= $unapproved['nama'] ?></td>
                                        <td>Ada <b><?= $unapproved['total'] ?></b> Logbook yang belum disetujui</td>
                                        <td class="text-center"><?= $unapproved['periode'] ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-primary btn-sm" onclick="unapprovedLogbook('<?= $i; ?>')" id="<?= $i ?>" data-nama="<?= $unapproved['nama'] ?>" data-periode="<?= $unapproved['periode'] ?>"><i class="fas fa-search"></i> Detail</button>
                                        </td>
                                    </tr>
                                    <?php $i++ ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END MAIN CONTENT -->

    <!-- Detail Modal -->
    <div id="detailLogbookUnapproved" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" style="width: 1000px;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-primary"><b>Detail Logbook</b></h4>
                </div>
                <div class="modal-body">
                    <div class="loadingAnimation">
                        <h2><i class="fa fa-cog fa-spin"></i> Loading...</h2>
                    </div>
                    <!-- Detail Pemilik Logbook -->
                    <div class="contentUnapproved hidden">
                        <table class="table">
                            <tbody class="logbookOwner">
                                <tr>
                                    <th scope="row" style="width: 250px;">Pemilik Logbook</th>
                                    <td class="pemilik"></td>
                                <tr>
                                    <th scope="row">Periode Logbook</th>
                                    <td class="periode"></td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- Detail Logbook -->
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr class="warning">
                                    <th scope="col" class="text-center">Kode IKU</th>
                                    <th scope="col" class="text-center">Nama IKU</th>
                                    <th scope="col" class="text-center">Perhitungan</th>
                                    <th scope="col" class="text-center">Realisasi Bulan Pelaporan</th>
                                    <th scope="col" class="text-center">Realisasi s.d Bulan Pelaporan</th>
                                    <th scope="col" class="text-center">Keterangan</th>
                                    <th scope="col" class="text-center">Waktu Rekam</th>
                                </tr>
                            </thead>
                            <tbody class="logbookDetailUnapproved">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

</div>