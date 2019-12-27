<div class="main">
    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="panel panel-headline panel-primary">
                <div class="panel-heading panel-title">
                    <div class="col-sm">
                        <h3 style="margin-top: -10px; margin-bottom: -10px;"><?= $title; ?></h3>
                        <a class="btn btn-success btn-sm pull-right" style="margin-top: -20px;" data-toggle="modal" data-target="#newKontrakModal"><i class="fas fa-fw fa-folder-plus"></i> Tambah Kontrak Kinerja</a>
                    </div>
                    <div class="col-sm">
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover table-bordered" id="kontrakKinerjaTable">
                        <thead>
                            <tr class="success">
                                <th scope="col">#</th>
                                <th scope="col" class="text-center">Jenis Kontrak Kinerja</th>
                                <?php if ($user['role_id'] == 1) : ?>
                                    <th scope="col" class="text-center">Pemilik Kontrak Kinerja</th>
                                <?php endif; ?>
                                <th scope="col" class="text-center">Nomor Kontrak Kinerja</th>
                                <th scope="col" class="text-center">Periode Pelaksanaan</th>
                                <th scope="col" class="text-center">Status Validasi</th>
                                <th scope="col" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="usersData">
                            <?php $i = 1; ?>
                            <?php foreach ($kontrakKinerja as $kontrak) : ?>
                                <tr>
                                    <th scope="row"><?= $i; ?></th>
                                    <td><?= $kontrak['kontrakkinerjake'] ?></td>
                                    <?php if ($user['role_id'] == 1) : ?>
                                        <td><?= $kontrak['nama'] ?></td>
                                    <?php endif; ?>
                                    <td><?= $kontrak['nomorkk'] ?></td>
                                    <td><?= indonesian_date3($kontrak['tanggalmulai']); ?> s.d <?= indonesian_date3($kontrak['tanggalselesai']); ?></td>
                                    <td><?= $kontrak['validasi_ket'] ?></td>
                                    <td>
                                        <?php if ($kontrak['is_validated'] == 1) : ?>
                                            <button class="btn btn-primary btn-xs btnEditPegawai" name="btnEditKontrak" id="btnEditKontrak" data-toggle="modal" data-target="#editUserModal" kontrak-id="<?= $kontrak['id_kontrak'] ?>"><i class="fas fa-fw fa-edit"></i> Edit Data</button>
                                            <button class="btn btn-danger btn-xs btnDeleteKontrak" data-toggle="modal" data-target="#deleteKontrakModal" kontrak-id="<?= $kontrak['id_kontrak'] ?>" name="btnDeleteKontrak"><i class=" fas fa-fw fa-trash"></i> Hapus Data</button>
                                        <?php else : ?>
                                            <button type="button" class="btn btn-warning"><i class="fas fa-fw fa-lock"></i> Kontrak Kinerja Terkunci</button>
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
        <!-- MODAL ADD KONTRAK -->
        <div class="modal fade" id="newKontrakModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-primary" id="myModalLabel"><b>Rekam Kontrak Kinerja</b></h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" action="" id="newKontrakKinerjaForm" method="POST">
                            <div class="form-group">
                                <?php if ($user['role_id'] == 1) : ?>
                                    <label class="control-label col-sm-3" for="setPegawai">Set Pegawai</label>
                                    <div class="col-sm-8">
                                        <select class="selectpicker" name="setPegawai" data-live-search="true" id="setPegawai">
                                            <?php foreach ($userList as $userList) : ?>
                                                <option value="<?= $userList['nip'] ?>"><?= $userList['nama'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php else : ?>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="setPegawai" name="setPegawai" value="<? $user['nip']; ?>">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="seriKontrakKinerja">Seri Kontrak Kinerja</label>
                                <div class="col-sm-8">
                                    <select class="selectpicker" name="seriKontrakKinerja" data-live-search="true" id="seriKontrakKinerja">
                                        <?php foreach ($seriKontrak as $seri) : ?>
                                            <option value="<?= $seri ?>"><?= $seri ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="nomorKontrakKinerja">Nomor Kontrak Kinerja</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="nomorKontrakKinerja" name="nomorKontrakKinerja" placeholder="Masukkan Nomor Kontrak Kinerja anda">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="tanggalAwalKontrak">Tanggal Awal</label>
                                <div class="col-sm-6">
                                    <input type="date" class="form-control" id="tanggalAwalKontrak" name="tanggalAwalKontrak" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="tanggalAkhirKontrak">Tanggal Akhir</label>
                                <div class="col-sm-6">
                                    <input type="date" class="form-control" id="tanggalAkhirKontrak" name="tanggalAkhirKontrak" placeholder="">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                <button type="button" class="btn btn-success hidden" id="btnNewKontrak"><i class="fa fa-save"></i> Simpan Kontrak</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL CONFIRM DELETE KONTRAK -->
        <div class="modal fade" id="deleteKontrakModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title text-primary" id="exampleModalLongTitle"><b>Konfirmasi hapus kontrak kinerja</b></h3>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin menghapus kontrak kinerja ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                        <button class="btn btn-danger btnConfirmDeleteKontrak"><i class="fas fa-fw fa-trash"></i> Hapus Kontrak</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL EDIT KONTRAK -->
        <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-primary" id="myModalLabel"><b>Ubah Data Kontrak Kinerja</b></h4>
                        <input type="hidden" readonly id="idKontrakKinerja">
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" action="" id="editKontrakKinerjaForm" method="POST">
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editSeriKontrakKinerja">Seri Kontrak Kinerja</label>
                                <div class="col-sm-8">
                                    <select class="selectpicker" name="editSeriKontrakKinerja" data-live-search="true" id="editSeriKontrakKinerja">
                                        <?php foreach ($seriKontrak as $seri) : ?>
                                            <option value="<?= $seri ?>"><?= $seri ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editNomorKontrakKinerja">Nomor Kontrak Kinerja</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="editNomorKontrakKinerja" name="editNomorKontrakKinerja" placeholder="Masukkan Nomor Kontrak Kinerja anda">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editTanggalAwalKontrak">Tanggal Awal</label>
                                <div class="col-sm-6">
                                    <input type="date" class="form-control" id="editTanggalAwalKontrak" name="editTanggalAwalKontrak">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editTanggalAkhirKontrak">Tanggal Akhir</label>
                                <div class="col-sm-6">
                                    <input type="date" class="form-control" id="editTanggalAkhirKontrak" name="editTanggalAkhirKontrak">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                <button type="button" class="btn btn-success btnConfirmEditKontrak"><i class="fa fa-save"></i> Update Kontrak Kinerja</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END MAIN CONTENT -->
</div>