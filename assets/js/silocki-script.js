// ====== SILOCKI MAIN-SCRIPT ===== //
// ====== Required to make SILOCKI work properly ===== //

// CHECK IF LOADED
$(document).ready(function () {
	console.log("ready");
})

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

// Delete Pegawai
$('button[name="btnDeletePegawai"]').click(function () {
	let u = $(this).attr('user-id');

	$('.btnConfirmDeletePegawai').click(function () {
		$.ajax({
			type: "POST",
			url: 'admin/deletePegawai/' + u,
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
})

// AJAX Get Data User By ID
$('button[name="btnEditPegawai"]').click(function () {
	let u = $(this).attr("user-id");
	$.ajax({
		type: "GET",
		url: "admin/getUserByID/?u=" + u,
		dataType: "JSON",
		beforeSend: function () {
			toastr["info"]("Sedang proses mengambil data", "Mengambil data", {
				"positionClass": "toast-top-right",
				"showDuration": "300",
				"hideDuration": "500",
				"timeOut": "3000",
			})
		},
		success: function (data) {
			toastr["success"]("Berhasil mengambil data user", "Sukses", {
				positionClass: "toast-top-right",
				showDuration: "200",
				hideDuration: "500",
				timeOut: "3000",
			});
			$('#idPegawai').val(data.id);
			$('#editNamaPegawai').val(data.nama);
			$('#editNIPPegawai').val(data.nip);
			$('#editPangkatPegawai').selectpicker('val', data.pangkat);
			$('#editLevelPegawai').selectpicker('val', data.role_id);
			$('#editOrganisasiPegawai').selectpicker('val', data.seksi);
			$('#editAtasanPegawai').selectpicker('val', data.pejabat_id);
			$('#editTelegramPegawai').val(data.telegram);
		}
	})
})

// AJAX Update Data User 
$('.btnConfirmEditPegawai').click(function () {
	let u = $('#idPegawai').val();
	let namaPegawai = $('#editNamaPegawai').val();
	let nipPegawai = $('#editNIPPegawai').val();
	let pangkatPegawai = $('#editPangkatPegawai').val();
	let roleIDPegawai = $('#editLevelPegawai').val();
	let seksiPegawai = $('#editOrganisasiPegawai').val();
	let atasanPegawai = $('#editAtasanPegawai').val();
	let telegramPegawai = $('#editTelegramPegawai').val();

	$.ajax({
		type: "POST",
		url: "admin/updatePegawai/",
		dataType: "JSON",
		data: {
			u: u,
			namaPegawai: namaPegawai,
			nipPegawai: nipPegawai,
			pangkatPegawai: pangkatPegawai,
			roleIDPegawai: roleIDPegawai,
			seksiPegawai: seksiPegawai,
			atasanPegawai: atasanPegawai,
			telegramPegawai: telegramPegawai
		},
		beforeSend: function (data) {
			$('.btnConfirmEditPegawai').html('<i class="fa fa-cog fa-spin"></i> Proses Simpan..').attr("disabled", "disabled");
		},
		success: function (data) {
			$('#editUserModal').modal('hide'),
				toastr["success"]("Data berhasil diubah!", "Sukses", {
					positionClass: "toast-top-right",
					showDuration: "200",
					hideDuration: "500",
					timeOut: "5000",
				});

		}
	})
})
