<!-- LEFT SIDEBAR -->
<div id="sidebar-nav" class="sidebar">
	<div class="sidebar-scroll">
		<nav>
			<ul class="nav">
				<?php if ($this->session->userdata('role_id') == 1) : ?>
					<li><a href="<?= base_url('index-admin'); ?>" class="active"><i class="lnr lnr-home"></i> <span>Dashboard</span></a></li>
				<?php elseif ($this->session->userdata('role_id') == 5) : ?>
					<li><a href="<?= base_url('index-pelaksana'); ?>" class="active"><i class="lnr lnr-home"></i> <span>Dashboard</span></a></li>
				<?php else : ?>
					<li><a href="<?= base_url('index-pejabat'); ?>" class="active"><i class="lnr lnr-home"></i> <span>Dashboard</span></a></li>
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
					<li><a href="manajemen-user" class=""><i class="lnr lnr-chart-bars"></i> <span>Manajemen User</span></a></li>
				<?php endif; ?>
				<li><a href="panels.html" class=""><i class="lnr lnr-cog"></i> <span>Panels</span></a></li>
				<li><a href="notifications.html" class=""><i class="lnr lnr-alarm"></i> <span>Notifications</span></a></li>

				<li><a href="tables.html" class=""><i class="lnr lnr-dice"></i> <span>Tables</span></a></li>
				<li><a href="typography.html" class=""><i class="lnr lnr-text-format"></i> <span>Typography</span></a></li>
				<li><a href="icons.html" class=""><i class="lnr lnr-linearicons"></i> <span>Icons</span></a></li>
			</ul>
		</nav>
	</div>
</div>
<!-- END LEFT SIDEBAR -->