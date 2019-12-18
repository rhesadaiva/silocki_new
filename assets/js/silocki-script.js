// ====== SILOCKI MAIN-SCRIPT ===== //
// ====== Required to make SILOCKI work properly ===== //


// ANIMASI LOADING
let loadingAnimation = '<i class="fas fa-cog fa-spin"> Loading</i>'

// FUNGSI AMBIL DATA PENGUMUMAN
let loadPengumuman =
	function () {
		$.ajax({
			url: 'welcome/ambilPengumuman',
			dataType: 'json',
			success: function (data) {
				let isipengumuman = '';
				let i = 0;
				for (i = 0; i < data.length; i++) {
					isipengumuman += '<div class="alert alert-success" role="alert">' + data[i].datapengumuman + '</div>'
					$('.pengumumanDashboard').html(isipengumuman);
				}
			}
		});
	}

// MENJALANKAN FUNGSI LOADPENGUMUMAN
$(".pengumumanDashboard").ready(function () {
	loadPengumuman();
})

// DataTables manajemen user
$(document).ready(function () {

	$('#manajemenUserTable').DataTable({
		"processing": true,
		"stateSave": true,
		"order": [],
		"info": false,
		"ordering": false,
		"lengthChange": false,
		// "fixedColumns": true,
	});

})
