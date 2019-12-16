<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->

    <div class="col-lg-5 col-sm-5">
        <div class="card shadow border-left-primary">
            <div class="card-header">
                <h4 class="text-gray-800"><span style="color:royalblue;font-weight: bold;"><?= $title; ?></span></h4>
            </div>
            <div class="card-body">

                <?= $this->session->flashdata('message'); ?>

                <form action="<?= base_url('auth/gantipassword'); ?>" method="post">
                    <div class="form-group">
                        <label for="passwordlama"><b>Password Sekarang</b></label>
                        <input type="password" class="form-control" id="passwordlama" name="passwordlama">
                        <?= form_error('passwordlama', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                    <div class="form-group">
                        <label for="passwordbaru1"><b>Password Baru</b></label>
                        <input type="password" class="form-control" id="passwordbaru1" name="passwordbaru1">
                        <?= form_error('passwordbaru1', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                    <div class="form-group">
                        <label for="passwordbaru2"><b>Konfirmasi Password Baru</b></label>
                        <input type="password" class="form-control" id="passwordbaru2" name="passwordbaru2">
                        <?= form_error('passwordbaru2', '<small class="text-danger pl-3">', '</small>'); ?>
                    </div>
                    <button type="submit" class="mt-3 btn btn-primary"><i class="fas fa-fw fa-key"></i> Ganti Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->