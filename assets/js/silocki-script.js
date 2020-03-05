// ====== SILOCKI MAIN-SCRIPT ===== //
// ====== Required to make SILOCKI work properly ===== //


// FUNGSI AMBIL DATA PENGUMUMAN
function loadPengumuman() {
	$.ajax({
		url: 'welcome/ambilPengumuman',
		dataType: 'JSON',
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

// FUNGSI RELOAD PAGE (UNTUK UPDATE KK DAN IKU)
function doReloadPage() {
	setTimeout(function () {
		window.location.reload()
	}, 1000)
};

// MENJALANKAN FUNGSI LOADPENGUMUMAN
$(".pengumumanDashboard").ready(function () {
	loadPengumuman();
})

// Fungsi init Datatables
function initDataTable(idDocument) {
	$(idDocument).DataTable({
		"ordering": false,
		"lengthChange": false,
	})
}

// DataTables manajemen user
$(document).ready(function () {
	initDataTable('#manajemenUserTable');
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
function savePegawaiBaru() {
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
				timeOut: "3000",
			});
		}
	})
}

// AJAX Delete Pegawai
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
					timeOut: "3000",
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
		url: "admin/getUserByID?u=" + u,
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
			$('#editOrganisasiPegawai').selectpicker('val', data.id_seksi_subseksi);
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
			$('.btnConfirmEditPegawai').html('<i class="fa fa-cog fa-spin"></i> Proses Update..').attr("disabled", "disabled");
		},
		success: function (data) {
			$('#editUserModal').modal('hide');
			toastr["success"]("Data berhasil diubah!", "Sukses", {
				positionClass: "toast-top-right",
				showDuration: "200",
				hideDuration: "500",
				timeOut: "3000",
			});

			// console.log(seksiPegawai);
			// console.log(u)
		}
	})
})

// DataTable Kontrak Kinerja
$(document).ready(function () {
	initDataTable('#kontrakKinerjaTable');
})

// Sembunyikan tombol simpan Kontrak Kinerja apabila form belum diisi lengkap
$('#nomorKontrak,#tanggalAwalKontrak,#tanggalAkhirKontrak').change(function () {
	if ($('#nomorKontrak').val() !== "" && $('#tanggalAwalKontrak').val() !== "" && $('#tanggalAkhirKontrak').val() !== "") {
		$("#btnNewKontrak").removeClass("hidden")
	} else {
		$("#btnNewKontrak").addClass("hidden");
	}
})

// AJAX Menambah Kontrak Kinerja Baru
$("#btnNewKontrak").click(function () {
	let dataKontrak = $('#newKontrakKinerjaForm').serialize();

	$.ajax({
		type: "POST",
		url: "save-kontrak",
		dataType: "JSON",
		data: dataKontrak,
		beforeSend: function (data) {
			$('#btnNewKontrak').html('<i class="fa fa-cog fa-spin"></i> Proses Simpan..').attr("disabled", "disabled");
		},
		success: function (data) {
			$('#newKontrakModal').modal('hide');
			toastr["success"]("Kontrak Kinerja berhasil disimpan", "Sukses", {
				positionClass: "toast-top-right",
				showDuration: "200",
				hideDuration: "500",
				timeOut: "3000",
			});
			doReloadPage();
		}
	})
})

// AJAX Delete Kontrak Kinerja Pegawai
$('button[name="btnDeleteKontrak"]').click(function () {
	let idKontrak = $(this).attr('kontrak-id');
	let alertDeleteKontrak = confirm("Apakah anda ingin menghapus Kontrak Kinerja ini?")

	if (alertDeleteKontrak) {
		// Jalankan AJAX
		$.ajax({
			type: "POST",
			url: 'delete-kontrak/' + idKontrak,
			dataType: "JSON",
			beforeSend: function (data) {
				toastr["info"]("Sedang proses menghapus data", "Menghapus data", {
					"positionClass": "toast-top-right",
					"showDuration": "300",
					"hideDuration": "500",
					"timeOut": "3000",
				})
			},
			success: function () {
				toastr["success"]("Kontrak Kinerja berhasil dihapus!", "Sukses", {
					positionClass: "toast-top-right",
					showDuration: "200",
					hideDuration: "500",
					timeOut: "3000",
				});
				doReloadPage();
			}
		})
	}
})

// AJAX Get Kontrak By ID
$('button[name="btnEditKontrak"]').click(function () {
	let id = $(this).attr('kontrak-id');

	$.ajax({
		type: "GET",
		url: "kontrakkinerja/getKontrakByID?id=" + id,
		dataType: "JSON",
		beforeSend: function (data) {
			toastr["info"]("Sedang proses mengambil data", "Mengambil data", {
				"positionClass": "toast-top-right",
				"showDuration": "300",
				"hideDuration": "500",
				"timeOut": "3000",
			})
		},
		success: function (data) {
			toastr["success"]("Berhasil mengambil data kontrak kinerja", "Sukses", {
				positionClass: "toast-top-right",
				showDuration: "200",
				hideDuration: "500",
				timeOut: "3000",
			});
			$('#idKontrakKinerja').val(data.id_kontrak);
			$('#editSeriKontrakKinerja').selectpicker('val', data.kontrakkinerjake);
			$('#editNomorKontrakKinerja').val(data.nomorkk);
			$('#editTanggalAwalKontrak').val(data.tanggalmulai);
			$('#editTanggalAkhirKontrak').val(data.tanggalselesai);
		}
	})
})

// AJAX Update Kontrak Kinerja
$('.btnConfirmEditKontrak').click(function () {
	let idKontrak = $('#idKontrakKinerja').val();
	let SeriKontrakKinerja = $('#editSeriKontrakKinerja').val();
	let NomorKontrakKinerja = $('#editNomorKontrakKinerja').val();
	let TanggalAwalKontrak = $('#editTanggalAwalKontrak').val();
	let TanggalAkhirKontrak = $('#editTanggalAkhirKontrak').val();

	$.ajax({
		type: "POST",
		url: 'update-kontrak',
		dataType: "JSON",
		data: {
			idKontrak: idKontrak,
			SeriKontrakKinerja: SeriKontrakKinerja,
			NomorKontrakKinerja: NomorKontrakKinerja,
			TanggalAwalKontrak: TanggalAwalKontrak,
			TanggalAkhirKontrak: TanggalAkhirKontrak
		},
		beforeSend: function (data) {
			$('.btnConfirmEditKontrak').html('<i class="fa fa-cog fa-spin"></i> Proses Update..').attr("disabled", "disabled");
		},
		success: function (data) {
			$('#editKontrakModal').modal('hide');
			toastr["success"]("Kontrak Kinerja berhasil diubah!", "Sukses", {
				positionClass: "toast-top-right",
				showDuration: "200",
				hideDuration: "500",
				timeOut: "3000",
			});
			doReloadPage();
		}
	})
})

// Mengosongkan form edit Kontrak Kinerja pada saat Modal Edit Kontrak Kinerja ditutup
$('#editKontrakModal').on('hidden.bs.modal', function () {
	$('#idKontrakKinerja').val('');
	$('#editSeriKontrakKinerja').selectpicker('val', 'Pertama');
	$('#editNomorKontrakKinerja').val('');
	$('#editTanggalAwalKontrak').val('');
	$('#editTanggalAkhirKontrak').val('');
})

// DataTable IKU
$(document).ready(function () {
	initDataTable('#ikuTable');
})

// Sembunyikan Tombol Simpan apabila Form IKU belum lengkap
$('#kodeIKU,#namaIKU,#formulaIKU,#targetIKU,#nilaiTertinggiIKU,#satuanPengukuranIKU,#penanggungJawabIKU,#penyediaDataIKU,#sumberDataIKU').change(function () {
	if ($('#kodeIKU').val() !== "" && $('#namaIKU').val() !== "" && $('#formulaIKU').val() !== "" && $('#targetIKU').val() !== "" && $('#nilaiTertinggiIKU').val() !== "" && $('#satuanPengukuranIKU').val() !== "" && $('#penanggungJawabIKU').val() !== "" && $('#penyediaDataIKU').val() !== "" && $('#sumberDataIKU').val() !== "") {
		$("#btnNewIKU").removeClass("hidden")
	} else {
		$("#btnNewIKU").addClass("hidden");
	}
})

// AJAX Simpan IKU Baru
$('#btnNewIKU').click(function () {
	let dataIKU = $('#newIKUForm').serialize();

	$.ajax({
		type: "POST",
		url: "save-iku",
		dataType: "JSON",
		data: dataIKU,
		beforeSend: function () {
			$('#btnNewIKU').html('<i class="fa fa-cog fa-spin"></i> Proses Simpan..').attr("disabled", "disabled");
		},
		success: function () {
			$('#newIKUModal').modal('hide');
			toastr["success"]("IKU berhasil disimpan!", "Sukses", {
				positionClass: "toast-top-right",
				showDuration: "200",
				hideDuration: "500",
				timeOut: "3000",
			});
			doReloadPage();
		}
	})
})

// AJAX Hapus IKU
$('button[name="btnDeleteIKU"]').click(function () {
	let idIKU = $(this).attr('iku-id');

	// Jalankan Ajax Hapus
	$('.btnConfirmDeleteIKU').click(function () {
		$.ajax({
			type: "POST",
			url: "delete-iku/" + idIKU,
			dataType: "JSON",
			beforeSend: function () {
				$('.btnConfirmDeleteIKU').html('<i class="fa fa-cog fa-spin"></i> Proses hapus..').attr("disabled", "disabled");
			},
			success: function () {
				$('#deleteIKUModal').modal('hide');
				toastr["success"]("IKU berhasil dihapus!", "Sukses", {
					positionClass: "toast-top-right",
					showDuration: "200",
					hideDuration: "500",
					timeOut: "3000",
				});
				doReloadPage();
			}
		})
	})
})

// AJAX Ambil Data IKU
$('button[name="btnEditIKU"]').click(function () {
	let idIKU = $(this).attr('iku-id');

	// Jalankan AJAX
	$.ajax({
		type: "GET",
		url: 'iku/getIKUByID?id=' + idIKU,
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
			toastr["success"]("Berhasil mengambil data IKU", "Sukses", {
				positionClass: "toast-top-right",
				showDuration: "200",
				hideDuration: "500",
				timeOut: "3000",
			});
			$('#idIKU').val(idIKU);
			$('#editKodeIKU').val(data.kodeiku);
			$('#editNamaIKU').val(data.namaiku);
			$('#editFormulaIKU').val(data.formulaiku);
			$('#editTargetIKU').val(data.targetiku);
			$('#editNilaiTertinggiIKU').val(data.nilaitertinggi);
			$('#editSatuanPengukuranIKU').val(data.satuanpengukuran);
			$('#editAspekTargetIKU').selectpicker('val', data.aspektarget);
			$('#editPenanggungJawabIKU').val(data.penanggungjawab);
			$('#editPenyediaDataIKU').val(data.penyediadata);
			$('#editSumberDataIKU').val(data.sumberdata);
			$('#editKonsolidasiPeriodeIKU').selectpicker('val', data.konsolidasiperiodeiku);
			$('#editPeriodePelaporanIKU').selectpicker('val', data.periodepelaporan);
			$('#editKonversi120IKU').selectpicker('val', data.konversi120);
		}
	})
})

// Ajax Update Data IKU
$('.btnConfirmEditIKU').click(function () {
	let idIKU = $('#idIKU').val();
	let kodeIKU = $('#editKodeIKU').val();
	let namaIKU = $('#editNamaIKU').val();
	let formulaIKU = $('#editFormulaIKU').val();
	let targetIKU = $('#editTargetIKU').val();
	let nilaiTertinggiIKU = $('#editNilaiTertinggiIKU').val();
	let satuanPengukuranIKU = $('#editSatuanPengukuranIKU').val();
	let aspekTargetIKU = $('#editAspekTargetIKU').val();
	let penanggungJawabIKU = $('#editPenanggungJawabIKU').val();
	let penyediaDataIKU = $('#editPenyediaDataIKU').val();
	let sumberDataIKU = $('#editSumberDataIKU').val();
	let konsolidasiPeriodeIKU = $('#editKonsolidasiPeriodeIKU').val();
	let periodePelaporanIKU = $('#editPeriodePelaporanIKU').val();
	let konversi120IKU = $('#editKonversi120IKU').val();

	// Jalankan Ajax
	$.ajax({
		type: "POST",
		url: "update-iku",
		dataType: 'JSON',
		data: {
			idIKU: idIKU,
			kodeIKU: kodeIKU,
			namaIKU: namaIKU,
			formulaIKU: formulaIKU,
			targetIKU: targetIKU,
			nilaiTertinggiIKU: nilaiTertinggiIKU,
			satuanPengukuranIKU: satuanPengukuranIKU,
			aspekTargetIKU: aspekTargetIKU,
			penanggungJawabIKU: penanggungJawabIKU,
			penyediaDataIKU: penyediaDataIKU,
			sumberDataIKU: sumberDataIKU,
			konsolidasiPeriodeIKU: konsolidasiPeriodeIKU,
			periodePelaporanIKU: periodePelaporanIKU,
			konversi120IKU: konversi120IKU,
		},
		beforeSend: function () {
			// Disable tombol edit IKU
			$('.btnConfirmEditIKU').html('<i class="fa fa-cog fa-spin"></i> Proses Update..').attr("disabled", "disabled");
		},
		success: function () {
			// Sembunyikan Modal dan berikan notifikasi sukses
			$('#editIKUModal').modal('hide');
			toastr["success"]("IKU berhasil diubah!", "Sukses", {
				positionClass: "toast-top-right",
				showDuration: "200",
				hideDuration: "500",
				timeOut: "3000",
			});
			doReloadPage();
		}
	})
})

// Ajax Ambil Data untuk Addendum IKU
$('button[name="btnAddendumIKU"]').click(function () {
	let idIKU = $(this).attr('iku-id');

	$('#btnConfirmEditIKU').addClass('hidden');
	$('#btnConfirmAddendumIKU').removeClass('hidden');

	// Jalankan AJAX
	$.ajax({
		type: "GET",
		url: 'iku/getIKUByID?id=' + idIKU,
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
			toastr["success"]("Berhasil mengambil data IKU", "Sukses", {
				positionClass: "toast-top-right",
				showDuration: "200",
				hideDuration: "500",
				timeOut: "3000",
			});
			$('#idIKU').val(idIKU);
			$('#editKodeIKU').val(data.kodeiku);
			$('#editNamaIKU').val(data.namaiku);
			$('#editFormulaIKU').val(data.formulaiku);
			$('#editTargetIKU').val(data.targetiku);
			$('#editNilaiTertinggiIKU').val(data.nilaitertinggi);
			$('#editSatuanPengukuranIKU').val(data.satuanpengukuran);
			$('#editAspekTargetIKU').selectpicker('val', data.aspektarget);
			$('#editPenanggungJawabIKU').val(data.penanggungjawab);
			$('#editPenyediaDataIKU').val(data.penyediadata);
			$('#editSumberDataIKU').val(data.sumberdata);
			$('#editKonsolidasiPeriodeIKU').selectpicker('val', data.konsolidasiperiodeiku);
			$('#editPeriodePelaporanIKU').selectpicker('val', data.periodepelaporan);
			$('#editKonversi120IKU').selectpicker('val', data.konversi120);
		}
	})
})

// AJAX Addendum IKU
$('.btnConfirmAddendumIKU').click(function () {
	let idIKU = $('#idIKU').val();
	let kodeIKU = $('#editKodeIKU').val();
	let namaIKU = $('#editNamaIKU').val();
	let formulaIKU = $('#editFormulaIKU').val();
	let targetIKU = $('#editTargetIKU').val();
	let nilaiTertinggiIKU = $('#editNilaiTertinggiIKU').val();
	let satuanPengukuranIKU = $('#editSatuanPengukuranIKU').val();
	let aspekTargetIKU = $('#editAspekTargetIKU').val();
	let penanggungJawabIKU = $('#editPenanggungJawabIKU').val();
	let penyediaDataIKU = $('#editPenyediaDataIKU').val();
	let sumberDataIKU = $('#editSumberDataIKU').val();
	let konsolidasiPeriodeIKU = $('#editKonsolidasiPeriodeIKU').val();
	let periodePelaporanIKU = $('#editPeriodePelaporanIKU').val();
	let konversi120IKU = $('#editKonversi120IKU').val();

	// Jalankan Ajax
	$.ajax({
		type: "POST",
		url: "addendum-iku",
		dataType: 'JSON',
		data: {
			idIKU: idIKU,
			kodeIKU: kodeIKU,
			namaIKU: namaIKU,
			formulaIKU: formulaIKU,
			targetIKU: targetIKU,
			nilaiTertinggiIKU: nilaiTertinggiIKU,
			satuanPengukuranIKU: satuanPengukuranIKU,
			aspekTargetIKU: aspekTargetIKU,
			penanggungJawabIKU: penanggungJawabIKU,
			penyediaDataIKU: penyediaDataIKU,
			sumberDataIKU: sumberDataIKU,
			konsolidasiPeriodeIKU: konsolidasiPeriodeIKU,
			periodePelaporanIKU: periodePelaporanIKU,
			konversi120IKU: konversi120IKU,
		},
		beforeSend: function () {
			$('.btnConfirmAddendumIKU').html('<i class="fa fa-cog fa-spin"></i> Proses Update..').attr("disabled", "disabled");
		},
		success: function () {
			$('#editIKUModal').modal('hide');
			toastr["success"]("IKU berhasil diubah!", "Sukses", {
				positionClass: "toast-top-right",
				showDuration: "200",
				hideDuration: "500",
				timeOut: "3000",
			});
			// Kembalikan ke tombol default
			$('#btnConfirmEditIKU').removeClass('hidden');
			$('#btnConfirmAddendumIKU').addClass('hidden');
			doReloadPage();
		}
	})
})

$('#editIKUModal').on('hidden.bs.modal', function () {
	$('#idIKU').val('');
	$('#editKodeIKU').val('');
	$('#editNamaIKU').val('');
	$('#editFormulaIKU').val('');
	$('#editTargetIKU').val('');
	$('#editNilaiTertinggiIKU').val('');
	$('#editSatuanPengukuranIKU').val('');
	$('#editAspekTargetIKU').selectpicker('val', 'Kuantitas');
	$('#editPenanggungJawabIKU').val('');
	$('#editPenyediaDataIKU').val('');
	$('#editSumberDataIKU').val('');
	$('#editKonsolidasiPeriodeIKU').selectpicker('val', 'Sum');
	$('#editPeriodePelaporanIKU').selectpicker('val', 'Bulanan');
	$('#editKonversi120IKU').selectpicker('val', 'Tidak');
})

// Buat Objek Promise load IKU
function loadIKU(idIKU) {
	return new Promise(function (resolve, reject) {
		let urlIKU = 'iku/getIKUByID?id=' + idIKU
		$.getJSON(urlIKU, function (data) {
			let masterIKU =
				`<tr>
					<th scope="row">Kode IKU</th>
					<td>` + data.kodeiku + `</td>
				<tr>
					<th scope="row">Nama IKU</th>
					<td>` + data.namaiku + `</td>
				</tr>
				<tr>	
					<th scope="row">Formula IKU</th>
					<td>` + data.formulaiku + `</td>
				</tr>
				<tr class="hidden">  
					<th scope="row" style:"padding-right: -20px;">ID IKU</th>
					<td>
					<input value="` + data.id_iku + `" class="form-control" readonly id="idIKULoadLogbook">
					</td>
				<tr>`

			resolve($('.masterIKU').html(masterIKU))
		})
	})
}

// Buat Objek Promise load logbook
function loadLogbook(idIKU) {
	return new Promise(function (resolve, reject) {
		let urlLogbook = "logbook/getLogbook?id-iku=" + idIKU
		$.getJSON(urlLogbook, function (data) {
			if (data) {
				$.each(data, function (i, data) {
					// Jika logbook belum dikirim
					if (data.is_sent == 0) {
						let masterLogbook =
							`<tr>
							<td class ="text-center">` + data.periode + `</td>
							<td class ="text-justify">` + data.perhitungan + `</td> 
							<td class ="text-justify">` + data.realisasibulan + `</td> 
							<td class ="text-justify">` + data.realisasiterakhir + `</td> 
							<td class ="text-justify">` + data.ket + `</td> 
							<td class ="text-center">` + moment(data.wakturekam).format('Do MMMM YYYY, HH:mm:ss') + `</td> 
							<td class ="aksiLogbook">
								<button class = "btn btn-primary btn-sm" onclick ="sendLogbook('` + data.id_logbook + `');"><i class ="fas fa-paper-plane"> </i> Kirim Logbook</button>
								<button class = "btn btn-info btn-sm" onclick="getLogbook('` + data.id_logbook + `');"><i class ="fas fa-edit"></i> Edit Logbook</button>
								<button class = "btn btn-danger btn-sm" onclick = "deleteLogbook('` + data.id_logbook + `');" > <i class ="fas fa-trash"> </i> Hapus Logbook</button>
							</td>
						</tr>`
						resolve($('#logbookData').append(masterLogbook))
					} else {
						// Jika logbook sudah dikirim
						let masterLogbook =
							`<tr>
								<td class="text-center">` + data.periode + `</td>
								<td class="text-justify">` + data.perhitungan + `</td>
								<td class="text-justify">` + data.realisasibulan + `</td>
								<td class="text-justify">` + data.realisasiterakhir + `</td>
								<td class="text-justify">` + data.ket + `</td>
								<td class="text-center">` + moment(data.wakturekam).format('Do MMMM YYYY, HH:mm:ss') + `</td>
								<td class="aksiLogbook">
									<button class="btn btn-success btn-sm" onclick="printLogbook('` + data.id_logbook + `');"><i class="fas fa-print"></i> Cetak Logbook</button>
								</td>
							</tr>`
						resolve($('#logbookData').append(masterLogbook))
					}
				})
			} else {
				// Jika tidak ada logbook
				resolve($('#logbookData').html(''))
			}
		})
	})
}

// Ketika tombol Create Logbook diklik
$('button[name="btnCreateLogbook"]').click(function () {

	let idIKU = $(this).attr('iku-id')
	$('#idIKULogbook').val(idIKU);

	// Munculkan animasi loading dan sembunyikan content
	$('.loadingAnimation').removeClass('hidden');
	$('.ikuLogbookContent').addClass('hidden');

	let a = loadIKU(idIKU);
	let b = loadLogbook(idIKU);

	// Jalankan Promise
	Promise.all([a, b]).then(hasil => {
		// Hasil Promise
		console.log("Fulfilled promise!")
		// Nonaktifkan animasi loading dan munculkan content
		$('.loadingAnimation').addClass('hidden');
		$('.ikuLogbookContent').removeClass('hidden');
	}).catch(e => {
		console.log(e);
	});

	// Aksi tutup modal Logbook
	$('#closeIKUModal').click(function () {
		$('#createLogbookModal').modal('hide')
	})
	// Apabila modal tertutup, sembunyikan content, hapus isi content, dan munculkan animasi loading
	$('#createLogbookModal').on('hidden.bs.modal', function () {
		$('.ikuLogbookContent').addClass('hidden');
		$('.loadingAnimation').removeClass('hidden');
		$('#logbookData').empty();
		$(".formLogbook").addClass("hidden");
	})

	// Tampilkan form Logbook
	$('#btnOpenFormLogbook').click(function () {
		$(".formLogbook").removeClass("hidden");
	})

	// Tutup Form Logbook
	$('.closeFormLogbook').click(function () {
		$(".formLogbook").addClass("hidden");
	})

	// Fungsi Save Logbook
	$('.saveNewLogbook').click(function () {
		let periodeLogbook = $('#periodePelaporanLogbook').val();
		let perhitunganLogbook = $('#perhitunganLogbook').val();
		let realisasiBulanPelaporanLogbook = $('#realisasiBulanPelaporanLogbook').val();
		let realisasiTerakhirLogbook = $('#realisasiTerakhirLogbook').val();
		let keteranganLogbook = $('#keteranganLogbook').val()

		// Jalankan AJAX Save Logbook
		$.ajax({
			type: "POST",
			url: 'save-logbook',
			dataType: "JSON",
			data: {
				idIKU: idIKU,
				periodeLogbook: periodeLogbook,
				perhitunganLogbook: perhitunganLogbook,
				realisasiBulanPelaporanLogbook: realisasiBulanPelaporanLogbook,
				realisasiTerakhirLogbook: realisasiTerakhirLogbook,
				keteranganLogbook: keteranganLogbook
			},
			// Ketika request dijalankan, disable tombol save
			beforeSend: function () {
				$('.saveNewLogbook').html('<i class="fa fa-cog fa-spin"></i> Proses Simpan..').attr("disabled", "disabled");
			},
			// Setelah request dijalankan
			success: function () {
				// Enable tombol save
				$('.saveNewLogbook').html('<i class="fas fa-fw fa-save"></i> Simpan Logbook').removeAttr("disabled");

				// Jalankan notifikasi
				toastr["success"]("Berhasil menambahkan Logbook, data Logbook akan muncul dibawah", "Sukses", {
					positionClass: "toast-top-right",
					showDuration: "200",
					hideDuration: "500",
					timeOut: "3000",
				});
				// Hide form new Logbook
				$(".formLogbook").addClass("hidden");

				// Kembalikan form ke posisi awal
				$('#periodePelaporanLogbook').selectpicker('val', 'Januari');
				$('#periodePelaporanLogbook').val('');
				$('#perhitunganLogbook').val('');
				$('#realisasiBulanPelaporanLogbook').val('');
				$('#realisasiTerakhirLogbook').val('');
				$('#keteranganLogbook').val('');

				// Kosongkan data Logbook
				$('#logbookData').html('')
				// Ambil data Logbook
				loadLogbook(idIKU)
			}
		})
	})
})

// Fungsi untuk menghapus Logbook
function deleteLogbook(idLogbook) {
	// Ambil ID IKU untuk refresh tabel logbook
	let idIKU = $('#idIKULogbook').val();

	// Konfirmasi hapus Logbook
	let confirmDelete = confirm("Hapus Logbook? Aksi ini tidak dapat diubah!")
	if (confirmDelete) {

		// Jika tombol OK diklik, jalankan AJAX Hapus Logbook
		$.ajax({
			type: "POST",
			url: "delete-logbook/" + idLogbook,
			dataType: "JSON",
			beforeSend: function () {
				toastr["info"]("Proses Hapus Data, mohon menunggu.", "Hapus Logbook", {
					"positionClass": "toast-top-right",
					"showDuration": "300",
					"hideDuration": "500",
					"timeOut": "3000",
				})
			},
			// Proses hapus berhasil dilaksanakan
			success: function () {
				toastr["success"]("Logbook berhasil dihapus!", "Sukses", {
					positionClass: "toast-top-right",
					showDuration: "200",
					hideDuration: "500",
					timeOut: "3000",
				});
				// Kosongkan data Logbook
				$('#logbookData').html('')
				// Ambil data Logbook
				loadLogbook(idIKU)
			}
		})
	}
}

// Fungsi untuk mengambil data Logbook untuk edit Logbook
function getLogbook(idLogbook) {

	// AJAX ambil data Logbook
	$.ajax({
		type: "GET",
		url: "logbook/getLogbookByID?id=" + idLogbook,
		dataType: "JSON",
		beforeSend: function () {
			toastr["info"]("Proses mengambil data, mohon menunggu.", "Ambil Data Logbook", {
				"positionClass": "toast-top-right",
				"showDuration": "300",
				"hideDuration": "500",
				"timeOut": "3000",
			})
		},
		success: function (data) {
			toastr["success"]("Data berhasil diambil!", "Sukses", {
				positionClass: "toast-top-right",
				showDuration: "200",
				hideDuration: "500",
				timeOut: "3000",
			});
			// Sembunyikan title new Logbook dan ganti dengan title edit Logbook
			$('.newLogbookTitle').addClass("hidden");
			$('.editLogbookTitle').removeClass("hidden");

			// Sembunyikan button save Logbook dan ganti dengan tombol edit
			$('.saveNewLogbook').addClass("hidden");
			$('.btnConfirmEditLogbook').removeClass("hidden").attr("id-logbook", data.id_logbook);

			// Isi Form Perubahan
			$('#periodePelaporanLogbook').selectpicker("val", data.periode);
			$('#perhitunganLogbook').val(data.perhitungan);
			$('#realisasiBulanPelaporanLogbook').val(data.realisasibulan);
			$('#realisasiTerakhirLogbook').val(data.realisasiterakhir);
			$('#keteranganLogbook').val(data.ket);

			// Tampilkan form Logbook
			$('.formLogbook').removeClass("hidden");
		}
	})
}

// AJAX Update Logbook
$('.btnConfirmEditLogbook').click(function () {

	// Ambil ID IKU
	let idIKU = $('#idIKULogbook').val();

	// Tetapkan nilai form
	let idLogbook = $(this).attr("id-logbook");
	let periodeLogbook = $('#periodePelaporanLogbook').val();
	let perhitunganLogbook = $('#perhitunganLogbook').val();
	let realisasiBulanPelaporanLogbook = $('#realisasiBulanPelaporanLogbook').val();
	let realisasiTerakhirLogbook = $('#realisasiTerakhirLogbook').val();
	let keteranganLogbook = $('#keteranganLogbook').val()

	// Jalankan AJAX update Logbook
	$.ajax({
		type: "POST",
		url: "update-logbook",
		dataType: "JSON",
		data: {
			idLogbook: idLogbook,
			periodeLogbook: periodeLogbook,
			perhitunganLogbook: perhitunganLogbook,
			realisasiBulanPelaporanLogbook: realisasiBulanPelaporanLogbook,
			realisasiTerakhirLogbook: realisasiTerakhirLogbook,
			keteranganLogbook: keteranganLogbook
		},
		beforeSend: function () {
			$('.btnConfirmEditLogbook').html('<i class="fa fa-cog fa-spin"></i> Proses Update..').attr("disabled", "disabled");
		},
		success: function () {
			$('.btnConfirmEditLogbook').html('<i class="fas fa-fw fa-save"></i> Simpan Perubahan Logbook').removeAttr("disabled");
			toastr["success"]("Logbook berhasil diubah!", "Sukses", {
				positionClass: "toast-top-right",
				showDuration: "200",
				hideDuration: "500",
				timeOut: "3000",
			});
			// Hide form new Logbook
			$(".formLogbook").addClass("hidden");

			// Sembunyikan tombol perubahan
			$('.saveNewLogbook').removeClass("hidden");
			$('.btnConfirmEditLogbook').addClass("hidden");

			// Kembalikan form ke posisi awal
			$('#periodePelaporanLogbook').selectpicker('val', 'Januari');
			$('#periodePelaporanLogbook').val('');
			$('#perhitunganLogbook').val('');
			$('#realisasiBulanPelaporanLogbook').val('');
			$('#realisasiTerakhirLogbook').val('');
			$('#keteranganLogbook').val('');

			// Kosongkan data Logbook
			$('#logbookData').html('')

			// Ambil data Logbook
			loadLogbook(idIKU)
		}
	})
})

// AJAX Kirim Logbook ke Atasan
function sendLogbook(idLogbook) {
	// Ambil ID IKU untuk refresh table logbook
	let idIKU = $('#idIKULogbook').val();

	// Konfirmasi kirim Logbook ke atasan
	let confirmSend = confirm("Kirim Logbook ke atasan? Aksi ini tidak dapat diubah!")

	// Jika OK
	if (confirmSend) {
		// Jalankan AJAX
		$.ajax({
			type: "POST",
			url: "send-logbook",
			data: {
				idLogbook: idLogbook
			},
			dataType: "JSON",
			beforeSend: function () {
				toastr["info"]("Proses mengirim data.", "Kirim Logbook", {
					"positionClass": "toast-top-right",
					"showDuration": "300",
					"hideDuration": "500",
					"timeOut": "3000",
					"progressBar": true,
				})
			},
			success: function () {
				toastr["success"]("Logbook berhasil dikirim ke atasan", "Kirim Logbook", {
					"positionClass": "toast-top-right",
					"showDuration": "300",
					"hideDuration": "500",
					"timeOut": "3000",
				})

				// Kosongkan data Logbook
				$('#logbookData').html('');

				// Ambil data Logbook
				loadLogbook(idIKU);
			}
		})
	}
}

// Tampilkan PDF Logbook di Tab Baru
function printLogbook(idLogbook) {
	const newLocal = 'logbook/cetakLogbook/';
	window.open(newLocal + idLogbook, '_blank');
}

// DataTables untuk Table Kontrak Kinerja Bawahan
$(document).ready(function () {
	initDataTable('#kinerjaBawahanTable');
});

// Fungsi konfirmasi persetujuan Kontrak Kinerja oleh atasan
function doApproveKontrak(idKontrak) {
	let confirmApproveKontrak = confirm("Apakah anda ingin menyetujui Kontrak Kinerja ini?");

	// Jika OK
	if (confirmApproveKontrak) {
		$.ajax({
			type: "POST",
			url: "approve-kontrak-kinerja",
			dataType: "JSON",
			data: {
				idKontrak: idKontrak
			},
			beforeSend: function () {
				toastr["info"]("Mohon menunggu...", "Persetujuan Kontrak Kinerja", {
					"positionClass": "toast-top-right",
					"showDuration": "300",
					"hideDuration": "500",
					"timeOut": "3000",
					"progressBar": true,
				})
			},
			success: function () {
				toastr["success"]("Proses Berhasil", "Persetujuan Kontrak Kinerja", {
					"positionClass": "toast-top-right",
					"showDuration": "300",
					"hideDuration": "500",
					"timeOut": "3000",
				})
			}
		})
	}
}

// Fungsi konfirmasi penolakan Kontrak Kinerja oleh atasan
function doRejectKontrak(idKontrak) {
	let confirmRejectKontrak = confirm("Apakah anda ingin menolak Kontrak Kinerja ini?");

	// Jika OK
	if (confirmRejectKontrak) {
		$.ajax({
			type: "POST",
			url: "reject-kontrak-kinerja",
			dataType: "JSON",
			data: {
				idKontrak: idKontrak
			},
			beforeSend: function () {
				toastr["info"]("Mohon menunggu...", "Penolakan Kontrak Kinerja", {
					"positionClass": "toast-top-right",
					"showDuration": "300",
					"hideDuration": "500",
					"timeOut": "3000",
					"progressBar": true,
				})
			},
			success: function () {
				toastr["success"]("Proses Berhasil", "Penolakan Kontrak Kinerja", {
					"positionClass": "toast-top-right",
					"showDuration": "300",
					"hideDuration": "500",
					"timeOut": "3000",
				})
			}
		})
	}
}

// Objek Promise ambil data Kontrak Kinerja
function getDetailKontrak(idKontrak) {
	return new Promise(function (resolve, reject) {
		let url = 'pejabat/getDetailKontrak?id=' + idKontrak;
		$.getJSON(url, function (data) {
			let detailKontrak =
				`<tr>
					<th scope="row" style:"padding-right: -20px;">Nomor Kontrak Kinerja</th>
					<td>` + data.nomorkk + `</td>
				<tr>
					<th scope="row" style:"padding-right: -20px;">Seri Kontrak Kinerja</th>
					<td>` + data.kontrakkinerjake + `</td>
				</tr>
				<tr>
					<th scope="row" style:"padding-right: -20px;">Tanggal Awal</th>
					<td>` + moment(data.tanggalmulai).format('DD MMMM YYYY') + `</td>
				</tr>
				<tr>	
					<th scope="row" style:"padding-right: -20px;">Tanggal Akhir</th>
					<td>` + moment(data.tanggalselesai).format('DD MMMM YYYY') + `</td>
				</tr>
				<tr class="hidden">
					<th scope="row" style:"padding-right: -20px;">ID Kontrak</th>
					<td>
					<input value="` + data.id_kontrak + `" class="form-control" readonly id="idKontrakKinerja">
					</td>
				<tr>`

			resolve($('.masterKontrakBawahan').html(detailKontrak));
		})
	})
}

// Objek Promise ambil data IKU
function getIKUFromKontrak(idKontrak) {
	return new Promise(function (resolve, reject) {
		let urlIKU = "pejabat/getIKUFromKontrakKinerja?kk=" + idKontrak;

		// Jalankan AJAX urlIKU 
		$.getJSON(urlIKU, function (data) {
			if (data) {
				$.each(data, function (i, data) {
					// Jika IKU belum divalidasi
					if (data.iku_validated == 0) {
						let listIKU =
							`<tr>
								<td class="text-center">` + data.kodeiku + `</td>
								<td class="text-justify">` + data.namaiku + `</td>
								<td class="text-justify">` + data.targetiku + ` dari ` + data.nilaitertinggi + `</td>
								<td class="aksiLogbook">
									<button class="btn btn-success btn-sm" onclick="doApproveIKU('` + data.id_iku + `');" ><i class="fas fa-fw fa-thumbs-up"></i> Persetujuan IKU</button>									
								</td>
							</tr>`
						resolve($('#listIKUData').append(listIKU))
					} else {
						// Jika IKU sudah divalidasi
						let listIKU =
							`<tr>
								<td class="text-center">` + data.kodeiku + `</td>
								<td class="text-justify">` + data.namaiku + `</td>
								<td class="text-justify">` + data.targetiku + ` dari ` + data.nilaitertinggi + `</td>
								<td class="aksiLogbook">
									<button class="btn btn-primary btn-sm" onclick="detailLogbook('` + data.id_iku + `');"><i class="fas fa-fw fa-chart-line"></i> Data Logbook</button>
									<button class="btn btn-danger btn-sm" onclick="doRejectIKU('` + data.id_iku + `');"><i class="fas fa-fw fa-thumbs-down"></i> Batalkan Persetujuan IKU</button>
								</td>
							</tr>`
						resolve($('#listIKUData').append(listIKU))
					}
				})
			} else {
				// Jika tidak ada logbook
				resolve($('#listIKUData').html(''))
			}
		})
	})
}

// Fungsi menampilkan detail Kontrak Kinerja dan IKU Bawahan
function detailKontrakKinerja(idKontrak) {
	// Tampilkan loading
	$('.kontrakBawahanList').addClass('hidden');
	$('.loadingDetailKontrakAnimation').removeClass('hidden');

	let c = getDetailKontrak(idKontrak);
	let d = getIKUFromKontrak(idKontrak);

	// Jalankan promise
	Promise.all([c, d]).then(hasil => {
		// Console Log promise berhasil dijalankan
		console.log("promise bawahan fulfilled");
		// Nonaktifkan loading dan munculkan content
		$('.loadingDetailKontrakAnimation').addClass('hidden');
		$('.detailKontrakContent').removeClass('hidden');
	})
}

// Kembali ke List Kontrak Bawahan
function returnKontrakBawahan() {
	$('.masterKontrakBawahan').empty();
	$('#listIKUData').html('');
	$('.detailKontrakContent').addClass('hidden');
	$('.kontrakBawahanList').removeClass('hidden');
}

// Fungsi konfirmasi persetujuan IKU
function doApproveIKU(idIKU) {
	let idKontrak = $('#idKontrakKinerja').val();

	// Tetapkan konfirmasi
	let confirmIKU = confirm("Apakah anda menyetujui IKU ini?");

	// Jika OK
	if (confirmIKU) {
		$.ajax({
			type: "POST",
			url: "approve-iku",
			dataType: "JSON",
			data: {
				iku: idIKU
			},
			beforeSend: function () {
				toastr["info"]("Mohon menunggu...", "Persetujuan IKU", {
					"positionClass": "toast-top-right",
					"showDuration": "300",
					"hideDuration": "500",
					"timeOut": "3000",
					"progressBar": true,
				})
			},
			success: function () {
				toastr["success"]("Proses Berhasil", "Persetujuan IKU", {
					"positionClass": "toast-top-right",
					"showDuration": "300",
					"hideDuration": "500",
					"timeOut": "3000",
				})
				// Kosongkan data list IKU
				$('#listIKUData').html('');

				// Ambil data
				getIKUFromKontrak(idKontrak);
			}
		})
	}
}

// Fungsi konfirmasi penolakan IKU
function doRejectIKU(idIKU) {
	let idKontrak = $('#idKontrakKinerja').val();

	// Tetapkan konfirmasi
	let confirmIKU = confirm("Apakah anda menyetujui IKU ini?");

	// Jika OK
	if (confirmIKU) {
		$.ajax({
			type: "POST",
			url: "reject-iku",
			dataType: "JSON",
			data: {
				iku: idIKU
			},
			beforeSend: function () {
				toastr["info"]("Mohon menunggu...", "Persetujuan IKU", {
					"positionClass": "toast-top-right",
					"showDuration": "300",
					"hideDuration": "500",
					"timeOut": "3000",
					"progressBar": true,
				})
			},
			success: function () {
				toastr["success"]("Proses Berhasil", "Persetujuan IKU", {
					"positionClass": "toast-top-right",
					"showDuration": "300",
					"hideDuration": "500",
					"timeOut": "3000",
				})
				// Kosongkan data list IKU
				$('#listIKUData').html('');

				// Ambil data
				getIKUFromKontrak(idKontrak);
			}
		})
	}
}

// Objek Promise Logbook Bawahan yang telah dikirim
function loadLogbookBawahan(idIKU) {
	return new Promise(function (resolve, reject) {
		let urlLogbook = "pejabat/getSentLogbook?id-iku=" + idIKU
		// Run AJAX
		$.getJSON(urlLogbook, function (data) {
			if (data) {
				$.each(data, function (i, data) {
					// Jika logbook belum dikirim
					if (data.is_approved == 0) {
						let masterLogbook =
							`<tr>
								<td class="text-center">` + data.periode + `</td>
								<td class="text-justify">` + data.perhitungan + `</td>
								<td class="text-justify">` + data.realisasibulan + `</td>
								<td class="text-justify">` + data.realisasiterakhir + `</td>
								<td class="text-justify">` + data.ket + `</td>
								<td class="text-center">` + moment(data.wakturekam).format('Do MMMM YYYY, HH:mm:ss') + `</td>
								<td class="aksiLogbook">
									<button class="btn btn-primary btn-sm" onclick="doApproveLogbook('` + data.id_logbook + `');" ><i class="fas fa-thumbs-up"></i> Setujui Logbook</button>
									<button class="btn btn-danger btn-sm" onclick="doRejectLogbook('` + data.id_logbook + `');"><i class="fas fa-thumbs-down"></i> Tolak Logbook</button>
								</td>
							</tr>`
						resolve($('#logbookBawahanData').append(masterLogbook))
					} else {
						// Jika logbook sudah dikirim
						let masterLogbook =
							`<tr>
								<td class="text-center">` + data.periode + `</td>
								<td class="text-justify">` + data.perhitungan + `</td>
								<td class="text-justify">` + data.realisasibulan + `</td>
								<td class="text-justify">` + data.realisasiterakhir + `</td>
								<td class="text-justify">` + data.ket + `</td>
								<td class="text-center">` + moment(data.wakturekam).format('Do MMMM YYYY, HH:mm:ss') + `</td>
								<td class="aksiLogbook">
									<button class="btn btn-success btn-sm" onclick="printLogbook('` + data.id_logbook + `');"><i class="fas fa-print"></i> Cetak Logbook</button>
									<button class="btn btn-danger btn-sm" onclick="doRejectLogbook('` + data.id_logbook + `');"><i class="fas fa-thumbs-down"></i> Tolak Logbook</button>
								</td>
							</tr>`
						resolve($('#logbookBawahanData').append(masterLogbook))
					}
				})
			} else {
				// Jika tidak ada logbook
				resolve($('#logbookBawahanData').html(''))
			}
		})
	})
}

// Fungsi detailLogbook
function detailLogbook(idIKU) {
	$('#modalLogbookBawahan').modal('show')
	$('.loadingAnimation').removeClass('hidden');
	$('.ikuLogbookContent').addClass('hidden');

	let a = loadIKU(idIKU);
	let b = loadLogbookBawahan(idIKU);

	// Jalankan promise
	Promise.all([a, b]).then(hasil => {
		// Hasil Promise
		console.log("Fulfilled promise L Bawahan!")
		// Nonaktifkan animasi loading dan munculkan content
		$('.loadingAnimation').addClass('hidden');
		$('.ikuLogbookContent').removeClass('hidden');
	}).catch(e => {
		console.log(e);
	});
}

// Fungsi tutup modal Logbook Bawhaan
function closeModalLogbookBawahan() {
	// Tutup Modal
	$('#modalLogbookBawahan').modal('hide')
}

// Kosongkan konten Logbook Bawahan dan tampilkan animasi loading
$('#modalLogbookBawahan').on('hidden.bs.modal', function () {
	$('#logbookBawahanData').empty();
	$('.ikuLogbookContent').addClass('hidden');
	$('.loadingAnimation').removeClass('hidden');
})

// Fungsi konfirmasi persetujuan Logbook
function doApproveLogbook(idLogbook) {
	let idIKU = $('#idIKULoadLogbook').val();

	let confirmLogbook = confirm("Apakah anda yakin untuk menyetujui Logbook ini?");
	// Jika OK
	if (confirmLogbook) {
		$.ajax({
			type: "POST",
			url: "approve-logbook",
			dataType: "JSON",
			data: {
				idlb: idLogbook
			},
			beforeSend: function () {
				toastr["info"]("Mohon menunggu...", "Persetujuan Logbook", {
					"positionClass": "toast-top-right",
					"showDuration": "300",
					"hideDuration": "500",
					"timeOut": "3000",
					"progressBar": true,
				})
			},
			success: function () {
				toastr["success"]("Proses Berhasil", "Persetujuan Logbook", {
					"positionClass": "toast-top-right",
					"showDuration": "300",
					"hideDuration": "500",
					"timeOut": "3000",
				})
				// Kosongkan data Logbook
				$('#logbookBawahanData').html('');

				// Ambil data Logbook
				loadLogbookBawahan(idIKU);
			}
		})
	}
}

// Fungsi konfirmasi tolak Logbook
function doRejectLogbook(idLogbook) {
	let idIKU = $('#idIKULoadLogbook').val();

	let confirmLogbook = confirm("Apakah anda yakin untuk menyetujui Logbook ini?");
	// Jika OK
	if (confirmLogbook) {
		$.ajax({
			type: "POST",
			url: "reject-logbook",
			dataType: "JSON",
			data: {
				idlb: idLogbook
			},
			beforeSend: function () {
				toastr["info"]("Mohon menunggu...", "Penolakan Logbook", {
					"positionClass": "toast-top-right",
					"showDuration": "300",
					"hideDuration": "500",
					"timeOut": "3000",
					"progressBar": true,
				})
			},
			success: function () {
				toastr["success"]("Proses Berhasil", "Penolakan Logbook", {
					"positionClass": "toast-top-right",
					"showDuration": "300",
					"hideDuration": "500",
					"timeOut": "3000",
				})
				// Kosongkan data Logbook
				$('#logbookBawahanData').html('');

				// Ambil data Logbook
				loadLogbookBawahan(idIKU);
			}
		})
	}
}

// Init DataTable Unapproved Logbook
$(document).ready(function () {
	$('#unapprovedTable,#approvedTable').DataTable({
		"ordering": false,
		"lengthChange": false,
		"searching": false
	})
})

// Promise mengambil owner Logbook
function getOwner(nama, periode) {
	return new Promise(function (resolve, reject) {
		let a = $('.pemilik').html(nama);
		let b = $('.periode').html(periode);
		resolve(a, b);
	});
}

// Promise mengambil detail unapproved
function getLogbookUnapproved(nama, periode) {
	return new Promise(function (resolve, reject) {
		let url = 'admin/getDetailLogbookUnapproved?nama=' + nama + '&periode=' + periode;
		$.getJSON(url, function (data) {
			if (data) {
				$.each(data, function (i, data) {
					let detailUnapproved =
						`<tr>
							<td class="text-center">` + data.kodeiku + `</td>
							<td class="text-justify">` + data.namaiku + `</td>
							<td class="text-justify">` + data.perhitungan + `</td>
							<td class="text-justify">` + data.realisasibulan + `</td>
							<td class="text-justify">` + data.realisasiterakhir + `</td>
							<td class="text-justify">` + data.ket + `</td>
							<td class="text-center">` + moment(data.wakturekam).format('Do MMMM YYYY, HH:mm:ss') + `</td>
						</tr>`
					resolve($('.logbookDetailUnapproved').append(detailUnapproved))
				})
			}
		});
	})
}

// Tampilkan detail modal Logbook Belum disetujui
function unapprovedLogbook(id) {
	$('#detailLogbookUnapproved').modal('show')
	let nama = document.getElementById(id).getAttribute("data-nama");
	let periode = document.getElementById(id).getAttribute("data-periode");

	let a = getOwner(nama, periode);
	let b = getLogbookUnapproved(nama, periode);
	// Jalankan Promise
	Promise.all([a, b]).then(result => {
		console.log('Fulfilled Promise unapproved Logbook');
		$('.loadingAnimation').addClass('hidden');
		$('.contentUnapproved').removeClass('hidden');
	}).catch(e => {
		console.log(e);
	});
}

// Kosongkan data apabila modal logbook belum disetujui tertutup
$('#detailLogbookUnapproved').on('hidden.bs.modal', function () {
	$('.loadingAnimation').removeClass('hidden');
	$('.contentUnapproved').addClass('hidden');
	$('.logbookDetailUnapproved').empty();
})

// Promise mengambil detail approved
function getLogbookApproved(nama, periode) {
	return new Promise(function (resolve, reject) {
		let url = 'admin/getDetailLogbookApproved?nama=' + nama + '&periode=' + periode;
		$.getJSON(url, function (data) {
			if (data) {
				$.each(data, function (i, data) {
					let detailUnapproved =
						`<tr>
							<td class="text-center">` + data.kodeiku + `</td>
							<td class="text-justify">` + data.namaiku + `</td>
							<td class="text-justify">` + data.perhitungan + `</td>
							<td class="text-justify">` + data.realisasibulan + `</td>
							<td class="text-justify">` + data.realisasiterakhir + `</td>
							<td class="text-justify">` + data.ket + `</td>
							<td class="text-center">` + moment(data.wakturekam).format('Do MMMM YYYY, HH:mm:ss') + `</td>
						</tr>`
					resolve($('.logbookDetailApproved').append(detailUnapproved))
				})
			}
		});
	})
}

// Tampilkan detail modal Logbook telah disetujui
function approvedLogbook(id) {
	$('#detailLogbookApproved').modal('show')
	let nama = document.getElementById(id).getAttribute("data-nama");
	let periode = document.getElementById(id).getAttribute("data-periode");

	let a = getOwner(nama, periode);
	let b = getLogbookApproved(nama, periode);
	// Jalankan Promise
	Promise.all([a, b]).then(result => {
		console.log('Fulfilled Promise approved Logbook');
		$('.loadingAnimation').addClass('hidden');
		$('.contentApproved').removeClass('hidden');
	}).catch(e => {
		console.log(e);
	});
}

// Kosongkan data apabila modal logbook yang telah disetujui tertutup
$('#detailLogbookApproved').on('hidden.bs.modal', function () {
	$('.loadingAnimation').removeClass('hidden');
	$('.contentApproved').addClass('hidden');
	$('.logbookDetailApproved').empty();
})

// Validating Password
$('#updatePasswordBtn').click(function (e) {
	e.preventDefault();

	let passwordlama = $('#passwordlama').val();
	let passwordbaru1 = $('#passwordbaru1').val();
	let passwordbaru2 = $('#passwordbaru2').val();

	// Jalankan AJAX Validasi
	$.ajax({
		url: 'change-password',
		method: 'POST',
		dataType: 'JSON',
		data: {
			passwordlama: passwordlama,
			passwordbaru1: passwordbaru1,
			passwordbaru2: passwordbaru2,
		},
		beforeSend: function () {
			$('#updatePasswordBtn').html('<i class="fa fa-cog fa-spin"></i> Proses Simpan..').attr("disabled", "disabled")
		},
		success: function (response) {
			// Jika password error
			if (response.error) {
				$('#validation-alert').append(response.passwordlama_error);
				$('#validation-alert').append(response.passwordbaru1_error);
				$('#validation-alert').append(response.passwordbaru2_error);
				$('#updatePasswordBtn').html('<i class="fas fa-fingerprint"></i> Update Password').removeAttr("disabled")

				// Jika response password lama tidak sesuai dengan password diinput
			} else if (response.notmatch) {
				$('#validation-alert').append(response.notmatchalert);
				$('#updatePasswordBtn').html('<i class="fas fa-fingerprint"></i> Update Password').removeAttr("disabled")

				// Jika validasi sukses
			} else if (response.success) {
				$('#validation-alert').append(response.successalert);
				$('#updatePasswordBtn').html('<i class="fas fa-fingerprint"></i> Update Password').removeAttr("disabled")
			}
		}
	})
})

// Kosongkan form dan alert apabila modal ganti password ditutup
$('#modalChangePassword').on('hidden.bs.modal', function () {
	$('#passwordlama').val('');
	$('#passwordbaru1').val('');
	$('#passwordbaru2').val('');
	$('#validation-alert').empty();
})

// AJAX upload foto profil
$('#uploadPhoto').submit(function (e) {
	e.preventDefault();

	$.ajax({
		url: 'upload-photo',
		method: 'POST',
		data: new FormData(this),
		processData: false,
		contentType: false,
		cache: false,
		beforeSend: function () {
			$('#btn_submit').html('<i class="fa fa-cog fa-spin"></i> Proses Upload..').attr("disabled", "disabled");
		},
		success: function () {
			$('#btn_submit').html('<i class="fas fa-upload"></i> Upload').removeAttr('disabled');
			alert('Foto berhasil diupload!')
		}
	})
})

// DataTable Server Side untuk Log Aktifitas
$(document).ready(function () {
	$("#logActivityTable").DataTable({
		"processing": true,
		"serverSide": true,
		"ordering": false,
		"searching": false,
		// "lengthChange": false,
		"order": [],

		// Load data konten table menggunakan AJAX
		"ajax": {
			"url": "get-log-activity",
			"type": "POST"
		},

		// Definisikan Kolom
		"columns": [{
				"data": "log_user",
				width: 70,
			},
			{
				"data": "log_desc",
				width: 100
			},
			{
				"data": "log_time",
				width: 80
			}
		],
	});
})

// Ambil data konfigurasi
$('.btnConfigData').click(function () {
	let idConfig = $(this).attr('cnfg');
	let url = 'config-detail?id=' + idConfig;

	$.getJSON(url, function (data) {
		if (data) {
			$('.modal-title').append("Detail Konfigurasi: <b>" + data.config_params + "</b>")
			$('.configId').val(data.config_id);
			$('.configDesc').val(data.config_desc)
			$('.configParams').val(data.config_params)
			$('.configValue').val(data.config_value)
			$('.configActive').val(data.config_is_active)
		}
	})
})

//  Update Config
$('.saveConfig').click(function () {
	let url = 'update-config';

	let configId = $('.configId').val();
	let configValue = $('.configValue').val();
	let configActive = $('.configActive').val();

	$.ajax({
		url: url,
		method: "POST",
		dataType: "JSON",
		data: {
			configId: configId,
			configValue: configValue,
			configActive: configActive
		},
		beforeSend: function () {
			$(this).html('<i class="fa fa-cog fa-spin"></i> Proses Simpan..').attr("disabled", "disabled");
		},
		success: function () {
			$(this).html('Simpan Konfigurasi').removeAttr('disabled');

			toastr["success"]("Data Konfigurasi berhasil disimpan", "Konfigurasi Aplikasi", {
				"positionClass": "toast-top-right",
				"showDuration": "300",
				"hideDuration": "500",
				"timeOut": "3000",
			});

			$('.configModal').modal('hide');
			doReloadPage();
		}
	})
})
