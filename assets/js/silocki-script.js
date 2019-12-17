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

var loadAllUsers =
	function () {
		$.ajax({
			url: 'admin/getAllUsers',
			dataType: 'JSON',
			success: function (data) {
				let usersData = '';
				let i;
				for (i = 1; i < data.length; i++) {
					usersData += '<tr>' +
						'<th scope="row">' + i + '</th>' +
						'<td>' + data[i].nama + '</td>' +
						'<td>' + data[i].nip + '</td>' +
						'<td>' + data[i].pangkat + '</td>' +
						'<td>' + data[i].level + '</td>' +
						'<td>' + data[i].seksi + '</td>' +
						'<td>' + data[i].atasan + '</td>' +
						'<td>' +
						'<button class="btn btn-primary btn-xs"> Edit Data</button>' +
						'<button class="btn btn-danger btn-xs"> Hapus Data</button>' +
						'</td>';
				}
				$("#usersData").html(usersData);
			}
		})
	}

// DataTables manajemen user
$(document).ready(function () {
	loadAllUsers();

	$('#manajemenUserTable').DataTable({
		"info": false,
		"ordering": false,
		"lengthChange": false
	});
})
