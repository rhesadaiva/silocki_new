<!-- LEFT SIDEBAR -->
<div id="sidebar-nav" class="sidebar">
	<div class="sidebar-scroll">
		<nav>
			<ul class="nav">
				<?php if ($this->session->userdata('role_id') == 1) : ?>
					<li><a href="<?= base_url('index-admin'); ?>"><i class="lnr lnr-home"></i> <span>Dashboard</span></a></li>
				<?php elseif ($this->session->userdata('role_id') == 5) : ?>
					<li><a href="<?= base_url('index-pelaksana'); ?>"><i class="lnr lnr-home"></i> <span>Dashboard</span></a></li>
				<?php else : ?>
					<li><a href="<?= base_url('index-pejabat'); ?>"><i class="lnr lnr-home"></i> <span>Dashboard</span></a></li>
				<?php endif; ?>
				<li>
					<a href="#subPages" data-toggle="collapse" class="collapsed"><i class="lnr lnr-file-empty"></i> <span>Pengelolaan Kinerja</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
					<div id="subPages" class="collapse ">
						<ul class="nav">
							<li><a href="kontrak-kinerja" class="">Pengelolaan Kontrak Kinerja</a></li>
							<li><a href="indikator-kinerja-utama" class="">Pengelolaan IKU dan Logbook</a></li>
						</ul>
					</div>
				</li>
				<?php if ($user['role_id'] != 5) : ?>
					<li><a href="approval-atasan" class=""><i class="lnr lnr-code"></i> <span>Persetujuan Atasan</span></a></li>
				<?php endif; ?>
				<?php if ($user['role_id'] == 1) : ?>
					<li>
						<a href="#monitoring" data-toggle="collapse" class="collapsed"><i class="lnr lnr-checkmark-circle"></i> <span>Monitoring</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
						<div id="monitoring" class="collapse">
							<ul class="nav">
								<li><a href="unapproved" class="">Logbook yang Belum Disetujui</a></li>
								<li><a href="approved" class="">Logbook yang Telah Disetujui</a></li>
							</ul>
						</div>
					</li>
					<li>
						<a href="#config" data-toggle="collapse" class="collapsed"><i class="lnr lnr-cog"></i> <span>Konfigurasi</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
						<div id="config" class="collapse">
							<ul class="nav">
								<li><a href="manajemen-user" class="">Manajemen User</a></li>
								<li><a href="#" class="">Konfigurasi Aplikasi</a></li>
								<li><a href="#" class="">Log Activity</a></li>
							</ul>
						</div>
					</li>
				<?php endif; ?>
			</ul>
		</nav>
	</div>
</div>
<!-- END LEFT SIDEBAR -->