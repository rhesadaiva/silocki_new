<body class="login-page">

    <div class="page-header header-filter">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 ml-auto mr-auto">
                    <div class="card card-login">

                        <div class="card-header card-header-success text-center">
                            <!-- <h4 class="card-title">SISTEM PELAPORAN CAPAIAN KINERJA KPPBC TANJUNGPINANG</h4> -->
                            <img src="<?= base_url('assets/img/logo1.png') ?>" alt="" style="width:90%; margin:auto">
                        </div>

                        <?= $this->session->flashdata('message'); ?>

                        <div class="card-body card-form form-login">
                            <!-- Form -->
                            <form method="post" action="<?= base_url('auth'); ?>">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="material-icons">account_circle</i>
                                        </span>
                                    </div>
                                    <input type="text" name="nip" class="form-control" placeholder="Nomor Induk Pegawai" value="<?= set_value('nip'); ?>" autocomplete off>
                                    <?= form_error('nip', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="material-icons">lock_outline</i>
                                        </span>
                                    </div>
                                    <input type="password" name="password" class="form-control" placeholder="Password" autocomplete off>
                                    <?= form_error('password', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>

                                <div class="row btn-row-submit">
                                    <button type="submit" class="btn btn-success btn-submit"><b>LOGIN</b></button>
                                </div>

                            </form>
                            <div class="row btn-row-forget">
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#forgotpassword">
                                    <b>LUPA PASSWORD</b>
                                </button>
                            </div>
                            <!-- End Form -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                <div class="copyright">
                    &copy;
                    <script>
                        document.write(new Date().getFullYear())
                    </script>, developed with <i class="material-icons">favorite</i> by
                    Daiva
                </div>
            </div>
        </footer>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="forgotpassword" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="padding-left:20px" id="forgotpasswordlabel"><b>Authentikasi Ganti Password</b></h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <?= $this->session->flashdata('forgot'); ?>

                    <form action="<?= base_url('auth/lupapassword'); ?>" method="post">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="material-icons">account_circle</i>
                                </span>
                            </div>
                            <input type="text" name="nipforgot" class="form-control" placeholder="Nomor Induk Pegawai" autocomplete off>
                            <?= form_error('nipforgot', '<small class="text-danger pl-3">', '</small>'); ?>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><b>Batal</b></button>
                            <button type="submit" class="btn btn-primary"><b>Proses</b></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>