<!DOCTYPE html>
<html lang="en">

<head>
    <title>SiLoCKi KPPBC Tanjungpinang</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
    <link rel="stylesheet" href="assets/vendor/linearicons/style.css">
    <link rel="stylesheet" href="assets/vendor/chartist/css/chartist-custom.css">
    <!-- MAIN CSS -->
    <link rel="stylesheet" href="assets/css/main.css">
    <!-- FOR DEMO PURPOSES ONLY. You should remove this in your project -->
    <link rel="stylesheet" href="assets/css/demo.css">
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
    <!-- ICONS -->
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.ico">
    <!-- DATATABLES CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.20/r-2.2.3/datatables.min.css" />
    <!-- BOOTSTRAP SELECTPICKER CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
    <!-- TOASTR CSS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
</head>

<body>
    <!-- WRAPPER -->
    <div id="wrapper">
        <!-- NAVBAR -->
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="brand">
                <img src="assets/img/logo1.png" style="width: 140px;" alt="" srcset="">
            </div>
            <div class="container-fluid">
                <div class="navbar-form navbar-left" style="padding-bottom: 3px; padding-top: 3px; padding-left: 40px;">
                    <h4><b>Sistem Pelaporan Capaian Kinerja KPPBC Tanjungpinang</b></h4>
                    <h5 style="margin-bottom: 5px;">Versi 2.0</h5>
                </div>
                <div id="navbar-menu">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="assets/img/profile/<?= $user['img'] ?>" class="img-circle" alt="Avatar"> <span><?= $user['nama']; ?></span> <i class="icon-submenu lnr lnr-chevron-down"></i></a>
                            <ul class="dropdown-menu">
                                <li><a href="#" data-toggle="modal" data-target="#modalChangePassword"><i class="lnr lnr-lock"></i> <span>Change Password</span></a></li>
                                <li><a href="#" data-toggle="modal" data-target="#modalLogout"><i class="lnr lnr-exit"></i> <span>Logout</span></a></li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>

        <!-- LOGOUT MODAL -->
        <div class="modal fade" id="modalLogout" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title text-primary" id="exampleModalLongTitle"><b>Logout Aplikasi</b></h3>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin keluar dari aplikasi ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" data-dismiss="modal">Batal</button>
                        <a class="btn btn-danger" href="<?= base_url('auth/logout'); ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- CHANGE PASS MODAL -->
        <div class="modal fade" id="modalChangePassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title text-primary" id="exampleModalLongTitle"><b>Ganti Password</b></h3>
                    </div>
                    <div class="modal-body">
                        <span id="validation-alert">

                        </span>
                        <form class="form-horizontal" action="" id="formChangePass">
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="passwordlama">Password Sekarang</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" id="passwordlama" placeholder="Masukkan password anda yang sekarang">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="pwd">Password baru</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" id="passwordbaru1" placeholder="Masukkan password baru anda minimal 5 karakter">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="pwd">Konfirmasi Password Baru</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" id="passwordbaru2" placeholder="Konfirmasi Password Baru">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                <button class="btn btn-success" id="updatePasswordBtn"><i class="fas fa-fingerprint"></i> Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL CHANGE PHOTO -->
        <div class="modal fade" id="modalChangePhoto" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title text-primary" id="exampleModalLongTitle"><b>Update Foto Profil</b></h3>
                    </div>
                    <div class="modal-body">
                        <span id="upload-alert">

                        </span>
                        <form action="" method="POST" class="form-horizontal" enctype="multipart/form-data" id="uploadPhoto">
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="profilePhoto">File input</label>
                                <div class="col-sm-5">
                                    <input class="form-control-file" type="file" name="filefoto">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" type="submit" id="btn_submit"><i class="fas fa-upload"></i> Upload</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- END NAVBAR -->
        <!-- TEMPLATE AS HEADER -->