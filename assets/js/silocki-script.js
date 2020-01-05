// ====== SILOCKI MAIN-SCRIPT ===== //
// ====== Required to make SILOCKI work properly ===== //


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
		}
	})
})

// DataTable Kontrak Kinerja
$(document).ready(function () {
	$('#kontrakKinerjaTable').DataTable({
		"ordering": false,
		"lengthChange": false,
	});
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
		}
	})
})

// AJAX Delete Kontrak Kinerja Pegawai
$('button[name="btnDeleteKontrak"]').click(function () {
	let idKontrak = $(this).attr('kontrak-id');

	$('.btnConfirmDeleteKontrak').click(function () {

		$.ajax({
			type: "POST",
			url: 'delete-kontrak/' + idKontrak,
			dataType: "JSON",
			beforeSend: function () {
				$('.btnConfirmDeleteKontrak').html('<i class="fa fa-cog fa-spin"></i> Proses hapus..').attr("disabled", "disabled");
			},
			success: function () {
				$('#deleteKontrakModal').modal('hide');
				toastr["success"]("Kontrak Kinerja berhasil dihapus!", "Sukses", {
					positionClass: "toast-top-right",
					showDuration: "200",
					hideDuration: "500",
					timeOut: "3000",
				});
			}
		})
	})
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
			$('#editUserModal').modal('hide');
			toastr["success"]("Kontrak Kinerja berhasil diubah!", "Sukses", {
				positionClass: "toast-top-right",
				showDuration: "200",
				hideDuration: "500",
				timeOut: "3000",
			});
		}
	})
})

// DataTable IKU
$(document).ready(function () {
	$('#ikuTable').DataTable({
		"ordering": false,
		"lengthChange": false,
	});
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

	// console.log(penanggungJawabIKU);

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
			$('.btnConfirmEditIKU').html('<i class="fa fa-cog fa-spin"></i> Proses Update..').attr("disabled", "disabled");
		},
		success: function () {

			$('#editIKUModal').modal('hide');
			toastr["success"]("IKU berhasil diubah!", "Sukses", {
				positionClass: "toast-top-right",
				showDuration: "200",
				hideDuration: "500",
				timeOut: "3000",
			});
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
		}
	})
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
				</tr>`
			resolve($('.masterIKU').html(masterIKU))
		})
	})
}

// Buat objek promise load logbook
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
								<td class="text-center">` + data.periode + `</td>
								<td class="text-justify">` + data.perhitungan + `</td>
								<td class="text-justify">` + data.realisasibulan + `</td>
								<td class="text-justify">` + data.realisasiterakhir + `</td>
								<td class="text-justify">` + data.ket + `</td>
								<td class="text-center">` + moment(data.wakturekam).format('Do MMMM YYYY, HH:mm:ss') + `</td>
								<td class="aksiLogbook">
									<button class="btn btn-primary"> Kirim Logbook</button> 
									<button class="btn btn-danger"> Hapus Logbook</button>
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
									<button class="btn btn-success" onclick="alert('clicked')"> Cetak Logbook</button>
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

	// Aksi tutup modal
	$('#closeIKUModal').click(function () {
		$('#createLogbookModal').modal('hide')
	})
	// Apabila modal tertutup, sembunyikan content, hapus isi content, dan munculkan animasi loading
	$('#createLogbookModal').on('hidden.bs.modal', function () {
		$('.ikuLogbookContent').addClass('hidden');
		$('.loadingAnimation').removeClass('hidden');
		$('#logbookData').empty();
	})
})
