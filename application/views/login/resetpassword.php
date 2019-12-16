<body class="login-page">

    <div class="page-header header-filter">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 ml-auto mr-auto">
                    <div class="card card-login">

                        <div class="card-header card-header-danger text-center">
                            <h4 class="card-title">RESET PASSWORD SILOCKI</h4>
                            <h5><?= $this->session->userdata['nama']; ?></h5>

                        </div>


                        <?= $this->session->flashdata('reset'); ?>

                        <div class="card-body card-form">
                            <!-- Form -->
                            <form method="post" action="<?= base_url('auth/resetpassword'); ?>">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="material-icons">fingerprint</i>
                                        </span>
                                    </div>
                                    <input type="text" name="tokennumber" class="form-control" placeholder="Token Telegram" value="<?= set_value('nip'); ?>" autocomplete off>
                                </div>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="material-icons">lock_outline</i>
                                        </span>
                                    </div>
                                    <input type="password" name="resetpass" class="form-control" placeholder="Password Baru" autocomplete off>
                                    <?= form_error('resetpass', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="material-icons">lock_outline</i>
                                        </span>
                                    </div>
                                    <input type="password" name="konfirmresetpass" class="form-control" placeholder="Konfirmasi Password Baru" autocomplete off>
                                    <?= form_error('konfirmresetpass', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>

                                <div class="row btn-row-reset">
                                    <button type="submit" class="btn btn-danger btn-reset"><b>RESET PASSWORD</b></button>
                                </div>

                                <div class="row btn-row-request">
                                    <button type="button" class="btn btn-danger btn-request" id="requestbtn" href="<?= base_url('auth/requestToken'); ?>">
                                        <b>REQUEST TOKEN</b>
                                    </button>
                                </div>

                            </form>
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
    <div class="modal fade" id="requestmodal" tabindex="-1">
        <div class="modal-dialog" role="document">

            <!-- Modal Content -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><b>Request Token Telegram</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Token reset sudah dikirimkan ke akun Telegram anda!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>