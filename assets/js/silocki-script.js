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
		"ordering": false,
		"lengthChange": false,
	});
})

// Sembunyikan button ketika form user belum diisi, dan munculkan button ketika telah diisi
$('#namaPegawai,#nipPegawai,#telegramPegawai').change(function () {
	if ($('#namaPegawai').val() !== "" && $('#nipPegawai').val() !== "" && $('#telegramPegawai').val() !== "") {
		$("#btnNewPegawai").removeClass("hidden")
	} else {
		$("#btnNewPegawai").addClass("hidden");
	}
})

// AJAX Menambah user baru
let savePegawaiBaru =
	function () {
		let dataPegawai = $('#newPegawaiForm').serialize();
		$.ajax({
			type: "POST",
			url: "admin/addPegawai",
			dataType: "JSON",
			data: dataPegawai,
			beforeSend: function () {
				$('#btnNewPegawai').html('<i class="fa fa-cog fa-spin"></i> Proses Simpan..').attr("disabled", "disabled");
			},
			success: function (data) {
				$('#newUserModal').modal('hide');
				toastr["success"]("Data berhasil disimpan dengan password default: 123456", "Sukses", {
					positionClass: "toast-top-right",
					showDuration: "200",
					hideDuration: "500",
					timeOut: "5000",
				});
			}
		})
	}

// AJAX Menghapus Data User
$('#btnDeletePegawai').click(function () {
	let url = $('#btnDeletePegawaiModal').data('url');
	$.ajax({
		type: "POST",
		url: url,
		dataType: "JSON",
		beforeSend: function () {
			$('#btnDeletePegawai').html('<i class="fa fa-cog fa-spin"></i> Proses hapus..').attr("disabled", "disabled");
		},
		success: function () {
			$('#deleteUserModal').modal('hide');
			toastr["success"]("Data berhasil dihapus!", "Sukses", {
				positionClass: "toast-top-right",
				showDuration: "200",
				hideDuration: "500",
				timeOut: "5000",
			});
		}
	})
})

// AJAX Ambil Data berdasarkan ID
{}
