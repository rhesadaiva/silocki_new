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
								<img src="assets/img/default.jpg" class="img-circle" alt="Avatar">
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
									<!-- <p class="panel-subtitle">Panel to display most important information</p> -->
								</div>
								<div class="panel-body">
									<!-- Pangkat / Golongan -->
									<div class="panel border-left-primary">
										<div class="panel-heading">
											<h3 class="panel-title"><b>PANGKAT/GOLONGAN</b></h3>
											<hr style="margin-bottom: -10px;">
										</div>
										<div class="panel-body">
											<p><?= $user['pangkat']; ?></p>
										</div>
									</div>

									<!-- Unit Organisasi -->
									<div class="panel" style="margin-top: -10px;">
										<div class="panel-heading">
											<h3 class="panel-title"><b>UNIT ORGANISASI</b></h3>
											<hr style="margin-bottom: -10px;">
										</div>
										<div class="panel-body">
											<p><?= $user['level']; ?> pada <?= $user['seksi'] ?></p>
										</div>
									</div>

									<!-- Atasan -->
									<div class="panel" style="margin-top: -10px;">
										<div class="panel-heading">
											<h3 class="panel-title"><b>ATASAN</b></h3>
											<hr style="margin-bottom: -10px;">
										</div>
										<div class="panel-body">
											<p><?= $user['atasan']; ?> </p>
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
								<div class="col-md-3 col-sm-6">
									<div class="award-item">
										<div class="hexagon">
											<span class="fas fa-fw fa-users fa-4x"></span>
										</div>
										<h3 style="margin-bottom: -10px"><b><?= $jumlahUser; ?></b></h3>
										<br>
										<span>Total User Aktif</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="award-item">
										<div class="hexagon" style="margin-left: 15px;">
											<span class="fas fa-fw fa-file-signature fa-4x" style="padding-left: 14px;"></span>
										</div>
										<h3 style="margin-bottom: -10px"><b><?= $jumlahKontrakKinerja; ?></b></h3>
										<br>
										<span class="text-center">Total Kontrak Kinerja</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="award-item">
										<div class="hexagon">
											<span class="fas fa-clipboard-list fa-4x"></span>
										</div>
										<h3 style="margin-bottom: -10px"><b><?= $jumlahIKU; ?></b></h3>
										<br>
										<span>Total IKU</span>
									</div>
								</div>
								<div class="col-md-3 col-sm-6">
									<div class="award-item">
										<div class="hexagon">
											<span class="fas fa-fw fa-clipboard-check fa-4x"></span>
										</div>
										<h3><b><?= $jumlahLogbook; ?></b></h3>
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
								<div class="margin-top-30 text-center"><a href="#" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Lihat semua aktivitas saya</a></div>
							</div>
						</div>
						<!-- END TABBED CONTENT -->
					</div>
					<!-- END RIGHT COLUMN -->
				</div>
			</div>
		</div>
		<!-- MODAL ALL ACTIVITY BERDASARKAN USER-->
		<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title">Modal title</h1>
					</div>
					<div class="modal-body allUserActivity">
						<p>Modal body text goes here.</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary">Save changes</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END MAIN CONTENT -->
</div>