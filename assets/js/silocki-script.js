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
			// beforeSend: function () {
			// 	$('.pengumumanDashboard').html(loadingAnimation);
			// },
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


// FUNGSI AMBIL DATA AKTIVITAS SEMUA
// let loadActivity =
// 	function () {
// 		$.ajax({
// 			url: 'welcome/getAllActivity',
// 			dataTyoe: 'JSON',
// 			success: function (data) {
// 				let activityContent = '';
// 				let i = 0;
// 				for (i = 0; i < data.length; i++) {
// 					activityContent += '<table>' +
// 						'<tr>' +
// 						'<th>#</th>' +
// 						'<th>Aktivitas</th>' +
// 						'<th>Waktu Aktivitas</th>' +
// 						'</tr>' +
// 						'<tr>' +
// 						'<td>' + '</td>' +
// 						'<td>' + data[i].log_desc + '</td>' +
// 						'<td>' + data[i].log_time + '</td>' +
// 						'</tr>' +
// 						'</table>'

// 					$('.allUserActivity').html(activityContent);
// 				}
// 			}
// 		});
// 	}
