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
                            <!-- <?php $i = 1; ?>
                            <?php foreach ($user_data as $user) : ?>
                                <tr>
                                    <th scope="row"><?= $i; ?></th>
                                    <td><?= $user['nama'] ?></td>
                                    <td><?= $user['nip'] ?></td>
                                    <td><?= $user['pangkat'] ?></td>
                                    <td><?= $user['level'] ?></td>
                                    <td><?= $user['seksi'] ?></td>
                                    <td><?= $user['atasan'] ?></td>
                                    <td>
                                        <button class="btn btn-primary btn-xs"> Edit Data</button>
                                        <button class="btn btn-danger btn-xs"> Hapus Data</button>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?> -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- MODAL ADD USER -->
        <div class="modal fade" id="newUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
                        <h4 class="modal-title text-primary" id="myModalLabel"><b>Tambah Data Pegawai</b></h4>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END MAIN CONTENT -->
</div>