<div class="main">
    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="panel panel-headline panel-primary">
                <div class="panel-heading panel-title">
                    <div class="col-sm">
                        <h3 style="margin-top: -10px; margin-bottom: -10px;"><?= $title; ?></h3>
                        <a class="btn btn-success btn-sm pull-right" style="margin-top: -20px;" data-toggle="modal" data-target="#newUserModal"><i class="fas fa-fw fa-user-plus"></i> Tambah Pegawai</a>
                    </div>
                    <div class="col-sm">
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover table-bordered" id="manajemenUserTable">
                        <thead>
                            <tr class="success">
                                <th scope="col">#</th>
                                <th scope="col" class="text-center">Nama</th>
                                <th scope="col" class="text-center">NIP</th>
                                <th scope="col" class="text-center">Pangkat/Golongan</th>
                                <th scope="col" class="text-center">Jabatan</th>
                                <th scope="col" class="text-center">Unit Organisasi</th>
                                <th scope="col" class="text-center">Atasan</th>
                                <th scope="col" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="usersData">
                            <?php $i = 1; ?>
                            <?php foreach ($user_data as $user) : ?>
                                <tr>
                                    <th scope="row"><?= $i; ?></th>
                                    <td><?= $user['nama'] ?></td>
                                    <td><?= $user['nip'] ?></td>
                                    <td><?= $user['pangkat'] ?></td>
                                    <td><?= $user['level'] ?></td>
                                    <td><?= $user['seksi_subseksi'] ?></td>
                                    <td><?= $user['nama_pejabat'] ?></td>
                                    <td>
                                        <button class="btn btn-primary btn-xs btnEditPegawai" name="btnEditPegawai" id="btnEditPegawaiModal" data-toggle="modal" data-target="#editUserModal" user-id="<?= $user['id'] ?>"><i class="fas fa-fw fa-edit"></i> Edit Data</button>
                                        <button class="btn btn-danger btn-xs btnDeletePegawai" data-toggle="modal" data-target="#deleteUserModal" user-id="<?= $user['id'] ?>" name="btnDeletePegawai"><i class=" fas fa-fw fa-trash"></i> Hapus Data</button>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- MODAL ADD USER -->
        <div class="modal fade" id="newUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-primary" id="myModalLabel"><b>Tambah Data Pegawai</b></h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" action="" id="newPegawaiForm" method="POST">
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="namaPegawai">Nama Pegawai</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="namaPegawai" name="namaPegawai" placeholder="Masukkan nama pegawai">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="nipPegawai">NIP</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="nipPegawai" name="nipPegawai" placeholder="Masukkan NIP Pegawai">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="pangkatPegawai">Pangkat/Golongan</label>
                                <div class="col-sm-8">
                                    <select class="selectpicker" name="pangkatPegawai" data-live-search="true" id="pangkatPegawai">
                                        <?php foreach ($pangkat as $pangkats) : ?>
                                            <option value="<?= $pangkats['pangkat/golongan'] ?>"><?= $pangkats['pangkat/golongan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="levelPegawai">Hak Akses</label>
                                <div class="col-sm-8">
                                    <select class="selectpicker" name="levelPegawai" data-live-search="true" id="levelPegawai">
                                        <?php foreach ($role as $roles) : ?>
                                            <option value="<?= $roles['id_role'] ?>"><?= $roles['level'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="organisasiPegawai">Unit Organisasi</label>
                                <div class="col-sm-8">
                                    <select class="selectpicker" name="organisasiPegawai" data-live-search="true" data-width="68%" id="organisasiPegawai">
                                        <?php foreach ($seksi as $unor) : ?>
                                            <option value="<?= $unor['id_seksi_subseksi'] ?>"><?= $unor['seksi_subseksi'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="atasanPegawai">Atasan</label>
                                <div class="col-sm-8">
                                    <select class="selectpicker" name="atasanPegawai" data-live-search="true" data-width="60%" id="atasanPegawai">
                                        <?php foreach ($pejabat as $atasan) : ?>
                                            <option value="<?= $atasan['pejabat_id'] ?>"><?= $atasan['nama_pejabat'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="telegramPegawai">Telegram</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="telegramPegawai" name="telegramPegawai" placeholder="Masukkan ID Telegram">
                                </div>
                                <div class="col-sm-2">
                                    <a class="btn btn-info" href="<?= $tlinks; ?>" target="_blank">Check Telegram ID</a>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                <button type="button" class="btn btn-success hidden" id="btnNewPegawai" onclick="savePegawaiBaru()"><i class="fa fa-save"></i> Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL CONFIRM DELETE USER -->
        <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title text-primary" id="exampleModalLongTitle"><b>Konfirmasi hapus data pegawai</b></h3>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin menghapus data user ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                        <button class="btn btn-danger btnConfirmDeletePegawai"><i class="fas fa-fw fa-trash"></i> Hapus data</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL EDIT USER -->
        <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-primary" id="myModalLabel"><b>Ubah Data Pegawai</b></h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" action="" id="editPegawaiForm" method="POST">
                            <input type="hidden" readonly name="idPegawai" id="idPegawai">
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editNamaPegawai">Nama Pegawai</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="editNamaPegawai" name="editNamaPegawai" placeholder="Masukkan nama pegawai">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editNIPPegawai">NIP</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="editNIPPegawai" name="editNIPPegawai" placeholder="Masukkan NIP Pegawai">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editPangkatPegawai">Pangkat/Golongan</label>
                                <div class="col-sm-8">
                                    <select class="selectpicker" name="editPangkatPegawai" data-live-search="true" id="editPangkatPegawai">
                                        <?php foreach ($pangkat as $pangkats) : ?>
                                            <option value="<?= $pangkats['pangkat/golongan'] ?>"><?= $pangkats['pangkat/golongan'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editLevelPegawai">Hak Akses</label>
                                <div class="col-sm-8">
                                    <select class="selectpicker" name="editLevelPegawai" data-live-search="true" id="editLevelPegawai">
                                        <?php foreach ($role as $roles) : ?>
                                            <option value="<?= $roles['id_role'] ?>"><?= $roles['level'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editOrganisasiPegawai">Unit Organisasi</label>
                                <div class="col-sm-8">
                                    <select class="selectpicker" name="editOrganisasiPegawai" data-live-search="true" data-width="68%" id="editOrganisasiPegawai">
                                        <?php foreach ($seksi as $unor) : ?>
                                            <option value="<?= $unor['id_seksi_subseksi'] ?>"><?= $unor['seksi_subseksi'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editAtasanPegawai">Atasan</label>
                                <div class="col-sm-8">
                                    <select class="selectpicker" name="editAtasanPegawai" data-live-search="true" data-width="60%" id="editAtasanPegawai">
                                        <?php foreach ($pejabat as $atasan) : ?>
                                            <option value="<?= $atasan['pejabat_id'] ?>"><?= $atasan['nama_pejabat'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editTelegramPegawai">Telegram</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="editTelegramPegawai" name="editTelegramPegawai" placeholder="Masukkan ID Telegram">
                                </div>
                                <div class="col-sm-2">
                                    <a class="btn btn-info" href="<?= $tlinks; ?>" target="_blank">Check Telegram ID</a>
                                    <!-- <span class="label label-info">Info</span> -->
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                <button type=" button" class="btn btn-success btnConfirmEditPegawai"><i class="fa fa-save"></i> Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END MAIN CONTENT -->
</div>