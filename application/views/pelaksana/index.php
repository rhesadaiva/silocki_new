<div class="main">
    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="panel panel-profile">
                <div class="clearfix">
                    <!-- LEFT COLUMN -->
                    <div class="profile-left">
                        <!-- PROFILE HEADER -->
                        <div class="profile-header">
                            <div class="overlay"></div>
                            <div class="profile-main">
                                <a href="#" data-toggle="modal" data-target="#modalChangePhoto"><img src="assets/img/profile/<?= $user['img'] ?>" class="img-circle" alt="Avatar"></a>
                                <h3 class="name"><?= strtoupper($user['nama']); ?></h3>
                                <span><?= $user['nip']; ?></span>
                            </div>
                        </div>
                        <!-- END PROFILE HEADER -->
                        <!-- PROFILE DETAIL -->
                        <div class="profile-detail">
                            <div class="panel panel-headline">
                                <div class="panel-heading">
                                    <h2 class="panel-title"><b>PROFIL PEGAWAI</b></h2>
                                    <hr style="margin-bottom: -10px;">
                                </div>
                                <div class="panel-body">
                                    <!-- Pangkat / Golongan -->
                                    <div>
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                <h3 style="margin-top: -10px; margin-bottom: -10px;"><b>PANGKAT/GOLONGAN</b></h3>
                                            </div>
                                            <div class="panel-body">
                                                <h4><?= $user['pangkat']; ?></h4>
                                            </div>
                                        </div>
                                        <!-- Unit Organisasi -->
                                        <div class="panel panel-success" style="margin-top: -10px;">
                                            <div class="panel-heading">
                                                <h3 style="margin-top: -10px; margin-bottom: -10px;"><b>UNIT ORGANISASI</b></h3>
                                            </div>
                                            <div class="panel-body">
                                                <h4><?= $user['level']; ?> pada <?= $user['seksi_subseksi'] ?></h4>
                                            </div>
                                        </div>
                                        <!-- Atasan -->
                                        <div class="panel panel-warning" style="margin-top: -10px;">
                                            <div class="panel-heading">
                                                <h3 style="margin-top: -10px; margin-bottom: -10px;"><b>ATASAN</b></h3>
                                            </div>
                                            <div class="panel-body">
                                                <h4><?= $user['nama_pejabat']; ?> </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END PROFILE DETAIL -->
                    </div>
                    <!-- END LEFT COLUMN -->
                    <!-- RIGHT COLUMN -->
                    <div class="profile-right">
                        <h4 class="heading">STATISTIK DATA</h4>
                        <!-- AWARDS -->
                        <div class="awards">
                            <div class="row">
                                <div class="col-md-4 col-sm-6">
                                    <div class="award-item">
                                        <div class="hexagon" style="margin-left: 15px;">
                                            <span class="fas fa-fw fa-file-signature fa-4x" style="padding-left: 14px;"></span>
                                        </div>
                                        <h3 style="margin-bottom: -10px"><b><?= $kkdisetujui; ?></b></h3>
                                        <br>
                                        <span class="text-center">Total Kontrak Kinerja</span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="award-item">
                                        <div class="hexagon">
                                            <span class="fas fa-clipboard-list fa-4x"></span>
                                        </div>
                                        <h3 style="margin-bottom: -10px"><b><?= $ikudisetujui; ?></b></h3>
                                        <br>
                                        <span>Total IKU</span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="award-item">
                                        <div class="hexagon">
                                            <span class="fas fa-fw fa-clipboard-check fa-4x"></span>
                                        </div>
                                        <h3><b><?= $logbookdikirim; ?></b></h3>
                                        <span>Total Logbook</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END AWARDS -->
                        <!-- TABBED CONTENT -->
                        <div class="custom-tabs-line tabs-line-bottom left-aligned">
                            <ul class="nav" role="tablist">
                                <li class="active"><a href="#tab-bottom-left1" role="tab" data-toggle="tab">PENGUMUMAN</a></li>
                                <li><a href="#tab-bottom-left2" role="tab" data-toggle="tab">AKTIVITAS TERAKHIR</a></li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade in active" id="tab-bottom-left1">
                                <div class="pengumumanDashboard">

                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab-bottom-left2">
                                <div class="recentActivityData">
                                    <ul class="list-unstyled activity-timeline">
                                        <?php foreach ($recentActivity as $recent) : ?>
                                            <li>
                                                <i class="fa fa-cloud-upload activity-icon"></i>
                                                <p><?= $recent['log_desc']; ?><span class="timestamp"><?= indonesian_date($recent['log_time']); ?></span></p>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- END TABBED CONTENT -->
                    </div>
                    <!-- END RIGHT COLUMN -->
                </div>
            </div>
        </div>
    </div>
    <!-- END MAIN CONTENT -->
</div>