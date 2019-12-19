pageSetUp();
$(".select2select").select2({
	width: "100%"
});
$(".select2selectbarang").select2({
	width: "100%"
}, {
	dropdownParent: "#modal-container"
});
$(".inputfloat2").inputNumberFormat({
	decimal: 2,
	decimalAuto: 2
});
$(".inputfloat0").inputNumberFormat({
	decimal: 0,
	decimalAuto: 0
});
$(".inputfloat4").inputNumberFormat({
	decimal: 4,
	decimalAuto: 4
});
$(".datepicker").datepicker();
$("#txtTglSiapPeriksa").datepicker("setDate", new Date());
$(".timepicker").timepicker({
	minuteStep: 1,
	showSeconds: true,
	showMeridian: false
});
var accordionIcons = {
	header: "fa fa-plus",
	activeHeader: "fa fa-minus"
};
$(".accordion").accordion({
	autoHeight: false,
	heightStyle: "content",
	collapsible: true,
	animate: 300,
	icons: accordionIcons,
	header: "h4"
});
var ppftz = "";
var idppftz = "161344";
var npwpppjk = "";
var valmasuk = "3";
var countbrgdok = 0;
var countknvdok = 0;
var countprevtrf = 0;
var closeModal = function () {
	$(".close").click()
};
var klickFieldUrl = function () {
	$("#txtUrlDokumen").click()
};
var klickButtonSubmit = function () {
	$("#buttonUpload").click()
};
$("#txtUrlDokumen").change(function () {
	$("#tombolPilihFile").html('<i class="fa fa-folder-open-o"></i> ' + $("#txtUrlDokumen").val().split("\\").pop());
	klickButtonSubmit();
	$("#fieldButtonCancel").removeClass("hidden")
});
var batalUpload = function () {
	$("#fieldButtonUpload").html('<div class="btn btn-default btn-block btn-sm pointer" id="tombolPilihFile" onclick="klickFieldUrl()"><i class="fa fa-folder-open-o"></i> Pilih File</div>');
	$("#fieldButtonCancel").html('<div class="btn btn-primary btn-block btn-sm pointer hidden" id="tombolUploadAtas" onclick="klickButtonSubmit()"><i class="fa fa-upload"></i> Upload</div>')
};
$("#txtKdJenisIdentitasPengirim").change(function () {
	if ($("#txtKdJenisIdentitasPengirim option:selected").val() === "5") {
		setsearchicon("iconNoIdentitasPengirim", "search")
	} else {
		setsearchicon("iconNoIdentitasPengirim", "tag")
	}
});
$("#txtKdJenisIdentitasPenerima").change(function () {
	if ($("#txtKdJenisIdentitasPenerima option:selected").val() === "5") {
		setsearchicon("iconNoIdentitasPenerima", "search")
	} else {
		setsearchicon("iconNoIdentitasPenerima", "tag")
	}
});
$("#txtKdJenisIdentitasPenjual").change(function () {
	if ($("#txtKdJenisIdentitasPenjual option:selected").val() === "5") {
		setsearchicon("iconNoIdentitasPenjual", "search")
	} else {
		setsearchicon("iconNoIdentitasPenjual", "tag")
	}
});
$("#txtKdJenisIdentitasPembeli").change(function () {
	if ($("#txtKdJenisIdentitasPembeli option:selected").val() === "5") {
		setsearchicon("iconNoIdentitasPembeli", "search")
	} else {
		setsearchicon("iconNoIdentitasPembeli", "tag")
	}
});
var setsearchicon = function (b, a) {
	if (a === "tag") {
		$("#" + b).removeClass("pointer fa-search").addClass("fa-tag").removeAttr("onclick")
	} else {
		if (a === "search") {
			$("#" + b).addClass("pointer fa-search").removeClass("fa-tag").attr("onclick", "searchnpwp('" + b + "')")
		}
	}
};
var searchnpwp = function (c) {
	var d = c.substr(15);
	var b = $("#txtNoIdentitas" + d).val();
	if (ppftz === "PPFTZ03_1") {
		var a = "cekNpwpFtz03"
	} else {
		var a = "cekNpwp"
	}
	if (b === "") {
		$.smallBox({
			title: "Harap isi NPWP " + d + " !",
			content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
			color: "#C46A69",
			iconSmall: "fa fa-times fa-2x bounce animated",
			timeout: 6000
		})
	} else {
		$.ajaxQueue({
			url: "ppftz.html",
			type: "POST",
			data: {
				content: a,
				npwp: b,
				sebagai: d
			}
		}).done(function (h, e) {
			var f = $.parseJSON(h);
			var g = f.npwp;
			if (g === null) {
				$.smallBox({
					title: "NPWP " + d + " Tidak Ditemukan !",
					content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
					color: "#C46A69",
					iconSmall: "fa fa-times fa-2x bounce animated",
					timeout: 6000
				})
			} else {
				if (g === "error500") {
					$.smallBox({
						title: "Koneksi Ke Service Pajak Terputus !",
						content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
						color: "#C46A69",
						iconSmall: "fa fa-times fa-2x bounce animated",
						timeout: 6000
					})
				} else {
					if (g === "noijin") {
						$.smallBox({
							title: "Pembeli belum memiliki Ijin BPK !",
							content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
							color: "#C46A69",
							iconSmall: "fa fa-times fa-2x bounce animated",
							timeout: 6000
						})
					} else {
						$("#txtNm" + d).val(f.namaPerusahaan).attr("readonly");
						$("#txtAlamat" + d).val(f.alamatPerusahaan).attr("readonly");
						$("#txtNoIjinBpk" + d).val(f.noIjinBpk).attr("readonly");
						$("#txtTglIjinBpk" + d).val(f.tglIjinBpk).attr("readonly")
					}
				}
			}
		})
	}
};
var copyDataPerusahaan = function (a) {
	if (a === "Pembeli") {
		$("#txtKdJenisIdentitasPembeli").val($("#txtKdJenisIdentitasPenerima option:selected").val());
		$("#txtNoIdentitasPembeli").val($("#txtNoIdentitasPenerima").val());
		$("#txtNmPembeli").val($("#txtNmPenerima").val());
		$("#txtAlamatPembeli").val($("#txtAlamatPenerima").val());
		$("#txtKdNegaraPembeli").val($("#txtKdNegaraPenerima option:selected").val());
		$("#txtKdNegaraPembeli", window.parent.document).select2({
			width: "100%"
		})
	} else {
		if (a === "Penjual") {
			$("#txtKdJenisIdentitasPenjual").val($("#txtKdJenisIdentitasPengirim option:selected").val());
			$("#txtNoIdentitasPenjual").val($("#txtNoIdentitasPengirim").val());
			$("#txtNmPenjual").val($("#txtNmPengirim").val());
			$("#txtAlamatPenjual").val($("#txtAlamatPengirim").val());
			$("#txtKdNegaraPenjual").val($("#txtKdNegaraPengirim option:selected").val());
			$("#txtKdNegaraPenjual", window.parent.document).select2({
				width: "100%"
			})
		}
	}
};
$("#txtKdCaraBayarTransaksi").change(function () {
	if ($("#txtKdCaraBayarTransaksi").val() === "13") {
		$("#fldtxtNmTransaksiLainnya").removeClass("hidden")
	} else {
		$("#fldtxtNmTransaksiLainnya").addClass("hidden")
	}
});
$("#txtKdValuta").change(function () {
	$("#txtNdpbmKonversi").attr("disabled");
	getkurs("txtNdpbm", $("#txtKdValuta option:selected").val())
});
$("#txtKdKantorAsal").change(function () {
	var a = $("#txtKdKantorAsal option:selected").val();
	if (valmasuk === "1") {
		loadlistTPS(a)
	} else {
		if (valmasuk === "2" || valmasuk === "3" || valmasuk === "4" || valmasuk === "5") {
			loadlistRevParPpftz("txtKdKantorTujuan", "listKantorFtz", "", "y", ppftz)
		}
	}
	if (valkeluar === "1") {
		loadlistTPS(a)
	} else {
		if (valkeluar === "2") {
			loadlistTPS(a)
		} else {
			if (valkeluar === "3") {
				loadlistRevParPpftz("txtKdKantorTujuan", "listKantorFtz", "", "y", ppftz);
				loadlistTPS(a)
			} else {
				if (valkeluar === "4" || valkeluar === "5" || valkeluar === "6") {
					loadlistRevParPpftz("txtKdKantorTujuan", "listKantor", "", "y", ppftz);
					loadlistTPS(a)
				}
			}
		}
	}
});
$("#txtKdKantorTujuan").change(function () {
	var a = $("#txtKdKantorTujuan option:selected").val();
	loadlistTPS(a)
});
$("#txtKdPelabuhanMuat").change(function () {
	loadUraianPelabuhan("txtKdPelabuhanMuat", "txtUraianPelabuhanMuat", $("#txtKdPelabuhanMuat").val())
});
$("#txtKdPelabuhanTujuan").change(function () {
	loadUraianPelabuhan("txtKdPelabuhanTujuan", "txtUraianPelabuhanTujuan", $("#txtKdPelabuhanTujuan").val())
});
$("#txtKdPelabuhanTransit").change(function () {
	loadUraianPelabuhan("txtKdPelabuhanTransit", "txtUraianPelabuhanTransit", $("#txtKdPelabuhanTransit").val())
});
$("#txtTarikNoDok,#txtTarikTglDok").change(function () {
	if ($("#txtTarikNoDok").val() !== "" && $("#txtTarikTglDok").val() !== "") {
		$("#btnGetDataBc11").removeClass("hidden")
	} else {
		$("#btnGetDataBc11").addClass("hidden")
	}
});
$("#txtNoBc11").change(function () {
	$("#txtNoBc11").val(padNum($("#txtNoBc11").val(), 6))
});
$("#txtPosBc11").change(function () {
	$("#txtPosBc11").val(padNum($("#txtPosBc11").val(), 4))
});
$("#txtSubposBc11").change(function () {
	$("#txtSubposBc11").val(padNum($("#txtSubposBc11").val(), 4))
});
$("#txtSubsubposBc11").change(function () {
	$("#txtSubsubposBc11").val(padNum($("#txtSubsubposBc11").val(), 4))
});
$("#txtKdDokumen,#txtNoDokumen,#txtTglDokumen,#txtKdSkemaTarif").change(function () {
	var a = $("#txtKdDokumen option:selected").val();
	if (a === "861") {
		$("#flddokskema").removeClass("hidden");
		if ($("#txtKdDokumen option:selected").val() !== "" && $("#txtNoDokumen").val() !== "" && $("#txtTglDokumen").val() !== "" && $("#txtKdSkemaTarif").val() !== "") {
			$("#btnSaveDokumen").removeClass("hidden")
		} else {
			$("#btnSaveDokumen").addClass("hidden")
		}
	} else {
		$("#flddokskema").addClass("hidden");
		$("#txtKdSkemaTarif option[value='']").attr("selected", "selected");
		if ($("#txtKdDokumen option:selected").val() !== "" && $("#txtNoDokumen").val() !== "" && $("#txtTglDokumen").val() !== "") {
			$("#btnSaveDokumen").removeClass("hidden")
		} else {
			$("#btnSaveDokumen").addClass("hidden")
		}
	}
});
$("#txtKdDokumen").change(function () {
	var a = $("#txtKdDokumen option:selected").val();
	if (a === "861") {
		$("#flddokskema").removeClass("hidden")
	} else {
		$("#flddokskema").addClass("hidden")
	}
	if ($("#txtKdDokumen option:selected").val() === "217" || $("#txtKdDokumen option:selected").val() === "380") {
		$("#InvPl").removeClass("hidden");
		$("#YInvPl").click()
	} else {
		$("#InvPl").addClass("hidden");
		$("#NInvPl").click()
	}
});
$("#btnSaveDokumen").click(function () {
	$(this).html('<i class="fa fa-cog fa-spin"></i> Loading....').attr("disabled", true)
});
$("#txtJumlahKemasanKms,#txtKdKemasanKms,#txtMerkKemasan").change(function () {
	if ($("#txtJumlahKemasanKms").val() !== "" && $("#txtKdKemasanKms option:selected").val() !== "") {
		$("#btnSaveKemasan").removeClass("hidden")
	} else {
		$("#btnSaveKemasan").addClass("hidden")
	}
});
$("#txtNoKontainer,#txtKdTipeKontainer,#txtKdUkuranKontainer").change(function () {
	noContainer = $("#txtNoKontainer").val();
	contValidator = new ContainerValidator();
	if (contValidator.isValid(noContainer)) {
		$("#labelnokontainer").removeClass("state-error").addClass("state-success");
		$("#labelnokontainer").addClass("valid");
		$("#validationTextContainer").addClass("hidden")
	} else {
		$("#labelnokontainer").removeClass("state-success").addClass("state-error");
		$("#labelnokontainer").removeClass("valid");
		$("#validationTextContainer").removeClass("hidden").text(contValidator.getErrorMessages())
	}
	if ($("#txtKdTipeKontainer option:selected").val() !== "" && $("#txtKdUkuranKontainer option:selected").val() !== "") {
		$("#btnSaveKontainer").removeClass("hidden")
	} else {
		$("#btnSaveKontainer").addClass("hidden")
	}
});
$("#txtBeaMasukPerhit").change(function () {
	if ($("#txtBeaMasukPerhit option:selected").val() === "1") {
		$("#fldbrgbeamasukjumlahsatuan").addClass("hidden")
	} else {
		if ($("#txtBeaMasukPerhit option:selected").val() === "2") {
			$("#fldbrgbeamasukjumlahsatuan").removeClass("hidden")
		} else {
			$("#fldbrgbeamasukjumlahsatuan").addClass("hidden")
		}
	}
});
$("#txtCukaiPerhit").change(function () {
	if ($("#txtCukaiPerhit option:selected").val() === "1") {
		$("#fldbrgcukaijumlahsatuan").addClass("hidden")
	} else {
		if ($("#txtCukaiPerhit option:selected").val() === "2") {
			$("#fldbrgcukaijumlahsatuan").removeClass("hidden")
		} else {
			$("#fldbrgcukaijumlahsatuan").addClass("hidden")
		}
	}
});
$("#txtCukaiKomoditi").change(function () {
	loadlistsubkomoditicukai("txtCukaiSubKomoditi", $("#txtCukaiKomoditi option:selected").val())
});
$("#txtCukaiSubKomoditi").change(function () {
	loadTarifCukai("", $("#txtCukaiSubKomoditi option:selected").val())
});
$("#txtBarangPosTarif,#txtBarangUrBarang,#txtBarangMerek,#txtBarangTipe,#txtBarangUkuran,#txtBarangSpesifikasiLain,#txtBarangNetto,#txtBarangBruto,#txtBarangVolume,#txtBarangJumlahSatuan,#txtBarangKdSatuan,#txtBarangKdKemasan,#txtBarangJumlahKemasan,#txtBarangNilaiPabean,#txtBarangKdNegaraAsalBarang").change(function () {
	if ($("#txtBarangPosTarif").val() !== "" && $("#txtBarangUrBarang").val() !== "" && $("#txtBarangNetto").val() !== "" && $("#txtBarangBruto").val() !== "" && $("#txtBarangVolume").val() !== "" && $("#txtBarangJumlahSatuan").val() !== "" && $("#txtBarangKdSatuan option:selected").val() !== "" && $("#txtBarangKdKemasan option:selected").val() !== "" && $("#txtBarangJumlahKemasan").val() !== "" && $("#txtBarangNilaiPabean").val() !== "" && $("#txtBarangKdNegaraAsalBarang option:selected").val() !== "") {
		$("#btnSaveBarang").removeClass("hidden")
	} else {
		$("#btnSaveBarang").addClass("hidden")
	}
});
$("#txtDokumenBrgDok,#txtKdJenisFasilitasBarangDokumen").change(function () {
	if ($("#txtDokumenBrgDok option:selected").val() !== "" && $("#txtKdJenisFasilitasBarangDokumen option:selected").val() !== "") {
		$("#btnTambahBarangDokumen").removeClass("hidden")
	} else {
		$("#btnTambahBarangDokumen").addClass("hidden")
	}
});
$("#txtDokumenPreferensiTarif").change(function () {
	if ($("#txtDokumenPreferensiTarif option:selected").val() !== "") {
		$("#btnTambahPreferensiTarif").removeClass("hidden")
	} else {
		$("#btnTambahPreferensiTarif").addClass("hidden")
	}
});
var geteditbarang = function (a) {
	$("#btneditbrg" + a).html('<i class="fa fa-cog fa-spin"></i>');
	$.ajaxQueue({
		url: "ppftz-barang.html",
		type: "POST",
		data: {
			content: "getedit",
			txtIdPpftzBarang: a
		}
	}).done(function (c, b) {
		var d = $.parseJSON(c);
		$("#txtIdPpftzBarang").val(a);
		$("#txtBarangPosTarif").val(d.PosTarif);
		$("#txtBarangUrBarang").val(d.UrBarang);
		$("#txtBarangMerek").val(d.Merek);
		$("#txtBarangTipe").val(d.Tipe);
		$("#txtBarangUkuran").val(d.Ukuran);
		$("#txtBarangSpesifikasiLain").val(d.SpesifikasiLain);
		$("#txtBarangFlLartas").val(d.FlagLartas);
		$("#txtBarangKodeBarang").val(d.KodeBarang);
		$("#txtBarangNetto").val(d.Netto);
		$("#txtBarangBruto").val(d.Bruto);
		$("#txtBarangVolume").val(d.Volume);
		$("#txtBarangJumlahSatuan").val(d.JumlahSatuan);
		$("#txtBarangKdSatuan").val(d.KdSatuan);
		$("#txtBarangKdSatuan", window.parent.document).select2({
			width: "100%"
		});
		$("#txtBarangKdKemasan").val(d.KdKemasan);
		$("#txtBarangKdKemasan", window.parent.document).select2({
			width: "100%"
		});
		$("#txtBarangJumlahKemasan").val(d.JumlahKemasan);
		$("#txtBarangNilaiPabean").val(d.NilaiPabean);
		$("#txtBarangKdNegaraAsalBarang").val(d.KdNegaraAsalBarang);
		$("#txtBarangKdNegaraAsalBarang", window.parent.document).select2({
			width: "100%"
		});
		for (countbrgdok = 0; countbrgdok < d.BarangDokumen.length; countbrgdok++) {
			$("#fielddinamicbrgdokumen").append('<div class="col col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12" id="sectionDynamicBrg' + countbrgdok + '"><div class="row"><section class="col col-5 col-lg-5 col-md-5 col-sm-5 col-xs-12"><label class="input"><input name="txtKdFasBarangDokValue" id="txtKdFasBarangDokValue' + countbrgdok + '" type="hidden" value="' + d.BarangDokumen[countbrgdok].KdFasilitas + '"><input type="text" value="' + d.BarangDokumen[countbrgdok].textFasilitas + '" disabled></label></section><section class="col col-5 col-lg-5 col-md-5 col-sm-5 col-xs-12"><label class="input"><input name="txtBarangDokValue" id="txtBarangDokValue' + countbrgdok + '" type="hidden" value="' + d.BarangDokumen[countbrgdok].IdPpftzDokumen + '"><input type="text" value="' + d.BarangDokumen[countbrgdok].textPpftzDokumen + '" disabled></label></section><section class="col col-2 col-lg-2 col-md-2 col-sm-2 col-xs-12"><label class="input"><div class="btn btn-sm btn-danger pointer" onClick="removeDynamicBrgDoc(\'sectionDynamicBrg' + countbrgdok + '\')" ><i class="fa fa-trash"></i></div></label></section><div class="input-group-btn"></div></div></div>')
		}
		if (d.tarifbm !== null) {
			$("#txtBeaMasukPerhit").val(d.tarifbm.PerhitunganTarif);
			$("#txtBeaMasukPerhit", window.parent.document);
			if (d.tarifbm.PerhitunganTarif === "2") {
				$("#fldbrgbeamasukjumlahsatuan").removeClass("hidden")
			} else {
				$("#fldbrgbeamasukjumlahsatuan").addClass("hidden")
			}
			$("#txtBeaMasukTarif").val(d.tarifbm.Tarif);
			$("#txtBeaMasukFasTrf").val(d.tarifbm.KdJenisFasilitasTarif);
			if (d.tarifbm.KdJenisFasilitasTarif === "DBY" || d.tarifbm.KdJenisFasilitasTarif === "") {
				$("#fldbrgbeamasuktariffasilitas").addClass("hidden")
			} else {
				$("#fldbrgbeamasuktariffasilitas").removeClass("hidden")
			}
			$("#txtBeaMasukTarifFasilitas").val(d.tarifbm.FasilitasTarif);
			$("#txtBeaMasukPerSatuan").val(d.tarifbm.Jumlah);
			$("#txtBeaMasukKdSatuan").val(d.tarifbm.KdSatuan);
			$("#txtBeaMasukKdSatuan", window.parent.document)
		}
		if (d.tarifck !== null) {
			$("#txtCukaiKomoditi").val(d.tarifck.KdKomoditiCukai);
			loadlistsubkomoditicukai("txtCukaiSubKomoditi", d.tarifck.KdKomoditiCukai, d.tarifck.KdSubKomoditiCukai);
			$("#txtCukaiPerhit").val(d.tarifck.PerhitunganTarif);
			if (d.tarifck.PerhitunganTarif === "2") {
				$("#fldbrgcukaijumlahsatuan").removeClass("hidden")
			} else {
				$("#fldbrgcukaijumlahsatuan").addClass("hidden")
			}
			$("#txtCukaiTarif").val(d.tarifck.Tarif);
			$("#txtCukaiFasTrf").val(d.tarifck.KdJenisFasilitasTarif);
			if (d.tarifck.KdJenisFasilitasTarif === "DBY" || d.tarifck.KdJenisFasilitasTarif === "") {
				$("#fldbrgbeakeluartariffasilitas").addClass("hidden")
			} else {
				$("#fldbrgbeakeluartariffasilitas").removeClass("hidden")
			}
			$("#txtCukaiTarifFasilitas").val(d.tarifck.FasilitasTarif);
			$("#txtCukaiJumlahSatuan").val(d.tarifck.Jumlah);
			$("#txtCukaiKdSatuan").val(d.tarifck.KdSatuan);
			$("#txtCukaiKdSatuan", window.parent.document).select2({
				width: "100%"
			});
			$("#txtCukaiKomoditi").val(d.tarifck.KdKomoditiCukai);
			$("#txtCukaiSubKomoditi").val(d.tarifck.getKdSubKomoditiBkc);
			$("#txtCukaiHje").val(d.tarifck.HJE);
			$("#txtCukaiIsiPerKms").val(d.tarifck.isiPerKemas)
		}
		if (d.tarifppn !== null) {
			$("#txtPPNTarif").val(d.tarifppn.Tarif);
			$("#txtPPNFasTrf").val(d.tarifppn.KdJenisFasilitasTarif);
			if (d.tarifppn.KdJenisFasilitasTarif === "DBY" || d.tarifppn.KdJenisFasilitasTarif === "") {
				$("#fldbrgppntariffasilitas").addClass("hidden")
			} else {
				$("#fldbrgppntariffasilitas").removeClass("hidden")
			}
			$("#txtPPNTarifFasilitas").val(d.tarifppn.FasilitasTarif)
		}
		if (d.tarifppnbm !== null) {
			$("#txtPPnBMTarif").val(d.tarifppnbm.Tarif);
			$("#txtPPnBMFasTrf").val(d.tarifppnbm.KdJenisFasilitasTarif);
			if (d.tarifppnbm.KdJenisFasilitasTarif === "DBY" || d.tarifppnbm.KdJenisFasilitasTarif === "") {
				$("#fldbrgppnbmtariffasilitas").addClass("hidden")
			} else {
				$("#fldbrgppnbmtariffasilitas").removeClass("hidden")
			}
			$("#txtPPnBMTarifFasilitas").val(d.tarifppnbm.FasilitasTarif)
		}
		if (d.tarifpph !== null) {
			$("#txtPPhTarif").val(d.tarifpph.Tarif);
			$("#txtPPhFasTrf").val(d.tarifpph.KdJenisFasilitasTarif);
			if (d.tarifpph.KdJenisFasilitasTarif === "DBY" || d.tarifpph.KdJenisFasilitasTarif === "") {
				$("#fldbrgpphtariffasilitas").addClass("hidden")
			} else {
				$("#fldbrgpphtariffasilitas").removeClass("hidden")
			}
			$("#txtPPhTarifFasilitas").val(d.tarifpph.FasilitasTarif)
		}
		$("#btnSaveBarang").removeClass("hidden");
		$('#ppftz-barang-form input[name="content"]').val("edit");
		openrowformaddbarang();
		$("#btneditbrg" + a).html('<i class="fa fa-edit"></i>')
	})
};
$("#btnFirstPpftzBarang").click(function (a) {
	a.preventDefault();
	$("#btnPage").html(1);
	loadPpftzBarang(1)
});
$("#btnPrevPpftzBarang").click(function (c) {
	c.preventDefault();
	var b = parseInt($("#btnPage").html());
	var a;
	if (b === 1) {
		a = 1
	} else {
		a = b - 1
	}
	$("#btnPage").html(a);
	loadPpftzBarang(a)
});
$("#btnNextPpftzBarang").click(function (c) {
	c.preventDefault();
	var b = parseInt($("#btnPage").html());
	var d = parseInt("");
	var a;
	if (b === d) {
		a = d
	} else {
		a = b + 1
	}
	$("#btnPage").html(a);
	loadPpftzBarang(a)
});
$("#btnLastPpftzBarang").click(function (b) {
	b.preventDefault();
	var a = "";
	$("#btnPage").html(a);
	loadPpftzBarang(a)
});
var openrowformaddbarang = function () {
	$("#rowformaddbarang").removeClass("hidden");
	$("html, body").animate({
		scrollTop: $("#rowformaddbarang").offset().top
	}, 1000)
};
var closerowformaddbarang = function () {
	clearFormBarang();
	$("#rowformaddbarang").addClass("hidden")
};
var addDynamicBrgDoc = function () {
	var c = $("#txtKdJenisFasilitasBarangDokumen option:selected").val();
	var a = $("#txtKdJenisFasilitasBarangDokumen option:selected").text();
	var b = $("#txtDokumenBrgDok option:selected").val();
	var d = $("#txtDokumenBrgDok option:selected").text();
	$("#fielddinamicbrgdokumen").append('<div class="col col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12" id="sectionDynamicBrg' + countbrgdok + '"><div class="row"><section class="col col-5 col-lg-5 col-md-5 col-sm-5 col-xs-12"><label class="input"><input name="txtKdFasBarangDokValue" id="txtKdFasBarangDokValue' + countbrgdok + '" type="hidden" value="' + c + '"><input type="text" value="' + a + '" disabled></label></section><section class="col col-5 col-lg-5 col-md-5 col-sm-5 col-xs-12"><label class="input"><input name="txtBarangDokValue" id="txtBarangDokValue' + countbrgdok + '" type="hidden" value="' + b + '"><input type="text" value="' + d + '" disabled></label></section><section class="col col-2 col-lg-2 col-md-2 col-sm-2 col-xs-12"><label class="input"><div class="btn btn-sm btn-danger pointer" onClick="removeDynamicBrgDoc(\'sectionDynamicBrg' + countbrgdok + '\')"><i class="fa fa-trash"></i></div></label></section><div class="input-group-btn"></div></div></div>');
	countbrgdok++;
	$("#txtKdJenisFasilitasBarangDokumen option:selected").removeAttr("selected");
	$("#txtKdJenisFasilitasBarangDokumen", window.parent.document).select2({
		width: "100%"
	});
	$("#txtDokumenBrgDok option:selected").removeAttr("selected");
	$("#txtDokumenBrgDok", window.parent.document).select2({
		width: "100%"
	});
	$("#btnTambahBarangDokumen").addClass("hidden");
	closeModal()
};
var addDynamicPrefTrf = function () {
	var a = $("#txtDokumenPreferensiTarif option:selected").val();
	var b = $("#txtDokumenPreferensiTarif option:selected").text();
	$("#fielddinamicbrgpreferensitarif").append('<div class="col col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12" id="sectionDynamicPref' + countprevtrf + '"><div class="row"><section class="col col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="input-group"><input name="txtPrevDokValue" id="txtPrevDokValue' + countprevtrf + '" type="hidden" value="' + a + '"><input class="form-control" type="text" value="' + b + '" disabled><div class="input-group-btn"><div class="btn btn-sm btn-danger pointer" onClick="removeDynamicPrevTrf(\'sectionDynamicPref' + countprevtrf + '\')"><i class="fa fa-trash"></i></div></div></div></section><div class="input-group-btn"></div></div></div>');
	countprevtrf++;
	$("#txtDokumenPreferensiTarif option:selected").removeAttr("selected");
	$("#txtDokumenPreferensiTarif", window.parent.document).select2({
		width: "100%"
	});
	$("#btnTambahPreferensiTarif").addClass("hidden");
	closeModal()
};
var removeDynamicPrevTrf = function (a) {
	$("#" + a).remove()
};
var removeDynamicBrgDoc = function (a) {
	$("#" + a).remove()
};
$("#txtBarangPosTarif").change(function () {
	var a = $("#txtBarangPosTarif").val();
	loaddatahscode(a, "labelpostarif", "validationTextPosTarif", "txtBeaMasukPerhit", "txtBeaMasukTarif", "fldbrgbeamasukjumlahsatuan", "txtBeaMasukKdSatuan", "txtPPNTarif", "txtPPnBMTarif")
});
var deleteBarang = function (a) {
	$.SmartMessageBox({
		title: "<i class='fa fa-trash txt-color-orangeDark'></i> Delete Barang !",
		content: "Anda yakin menghapus Barang ini??",
		buttons: "[No][Yes]"
	}, function (b) {
		if (b === "Yes") {
			$.ajaxQueue({
				url: "ppftz-barang.html",
				type: "POST",
				data: {
					content: "delete",
					txtIdPpftzBarang: a
				}
			}).done(function (c) {
				if (c === "timeout") {
					$.smallBox({
						title: "Session time out!<br/>Silahkan login lagi! ...",
						content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
						color: "#C46A69",
						iconSmall: "fa fa-times fa-2x bounce animated",
						timeout: 4000
					})
				} else {
					if (c === "success") {
						$.smallBox({
							title: "Success ...<br/>Data Barang berhasil dihapus ...",
							content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
							color: "#5F895F",
							iconSmall: "fa fa-times fa-2x bounce animated",
							timeout: 4000
						});
						loadPpftzBarang(1);
						loadvalidator();
						$("#btndeleteBarang" + a).html('<i class="fa fa-trash"></i>')
					} else {
						$.smallBox({
							title: "Error system ...<br/> " + c,
							content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
							color: "#C46A69",
							iconSmall: "fa fa-times fa-2x bounce animated",
							timeout: 6000
						})
					}
				}
			})
		}
	})
};
var openrowformaddkonversi = function () {
	$("#rowformaddkonversi").removeClass("hidden");
	$("html, body").animate({
		scrollTop: $("#rowformaddkonversi").offset().top
	}, 1000)
};
var closerowformaddkonversi = function () {
	$("#rowformaddkonversi").addClass("hidden");
	clearFormKonversi()
};
var tambahKonversi = function (a) {
	openrowformaddkonversi();
	$("#txtIdPpftzBarangKnv").val(a)
};
$("#txtKdAsalBarangKnv,#txtNoPpftzMasukKnv,#txtTglPpftzMasukKnv,#txtPosTarifKnv,#txtUrBarangKnv,#txtKodeBarangKnv,#txtNoSkaKnv,#txtTglSkaKnv,#txtKdValutaKnv,#txtNdpbmKnv,#txtJumlahSatuanKnv,#txtKdSatuanKnv,#txtNilaiPersatuanKnv,#txtNilaiPabeanKnv,#txtNilaiAsalLdpKnv,#txtKeteranganKnv").change(function () {
	if ($("#txtKdAsalBarangKnv").val() !== "" && $("#txtNoPpftzMasukKnv").val() !== "" && $("#txtTglPpftzMasukKnv").val() !== "" && $("#txtPosTarifKnv").val() !== "" && $("#txtUrBarangKnv").val() !== "" && $("#txtKodeBarangKnv").val() !== "" && $("#txtKdValutaKnv").val() !== "" && $("#txtNdpbmKnv").val() !== "" && $("#txtJumlahSatuanKnv").val() !== "" && $("#txtKdSatuanKnv").val() !== "" && $("#txtNilaiPersatuanKnv").val() !== "" && $("#txtNilaiPabeanKnv").val() !== "" && $("#txtNilaiAsalLdpKnv").val() !== "" && $("#txtKeteranganKnv").val() !== "") {
		$("#btnSaveKonversi").removeClass("hidden")
	} else {
		$("#btnSaveKonversi").addClass("hidden")
	}
});
var geteditKonversi = function (a) {
	$.ajaxQueue({
		url: "ppftz-konversi.html",
		type: "POST",
		data: {
			content: "getedit",
			IdPpftzKonversi: a
		}
	}).done(function (c, b) {
		var d = $.parseJSON(c);
		$("#txtIdPpftzKonversiKnv").val(a);
		$("#txtKdAsalBarangKnv").val(d.KdAsalBarangKonversi);
		$("#txtNoPpftzMasukKnv").val(d.NoPpftzMasuk);
		$("#txtTglPpftzMasukKnv").val(d.TglPpftzMasuk);
		$("#txtPosTarifKnv").val(d.PosTarif);
		$("#txtUrBarangKnv").val(d.UrBarang);
		$("#txtKodeBarangKnv").val(d.KodeBarang);
		$("#txtNoSkaKnv").val(d.NoSka);
		$("#txtTglSkaKnv").val(d.TglSka);
		$("#txtNmPenerbitSkaKnv").val(d.NmPenerbitSka);
		$("#txtJumlahSatuanKnv").val(d.JumlahSatuan);
		$("#txtKdSatuanKnv").val(d.KdSatuan);
		$("#txtKdSatuanKnv", window.parent.document).select2({
			width: "100%"
		});
		$("#txtKdValutaKnv").val(d.KdValuta);
		$("#txtKdValutaKnv", window.parent.document).select2({
			width: "100%"
		});
		$("#txtNdpbmKnv").val(d.Ndpbm);
		$("#txtNilaiPersatuanKnv").val(d.NilaiPersatuan);
		$("#txtNilaiPabeanKnv").val(d.NilaiPabean);
		$("#txtNilaiAsalLdpKnv").val(d.NilaiAsalLdp);
		$("#txtKeteranganKnv").val(d.Keterangan);
		if (d.tarifbm !== null) {
			$("#txtBeaMasukPerhitKnv").val(d.tarifbm.PerhitunganTarif);
			$("#txtBeaMasukPerhitKnv", window.parent.document);
			if (d.tarifbm.PerhitunganTarif === "2") {
				$("#fldkonversibeamasukjumlahsatuan").removeClass("hidden")
			} else {
				$("#fldkonversibeamasukjumlahsatuan").addClass("hidden")
			}
			$("#txtBeaMasukTarifKnv").val(d.tarifbm.Tarif);
			$("#txtBeaMasukFasTrfKnv").val(d.tarifbm.KdJenisFasilitasTarif);
			$("#txtBeaMasukFasTrfKnv", window.parent.document);
			if (d.tarifbm.KdJenisFasilitasTarif === "DBY" || d.tarifbm.KdJenisFasilitasTarif === "") {
				$("#fldkonversibeamasuktariffasilitas").addClass("hidden")
			} else {
				$("#fldkonversibeamasuktariffasilitas").removeClass("hidden")
			}
			$("#txtBeaMasukTarifFasilitasKnv").val(d.tarifbm.NilaiFasilitasTarif);
			$("#txtBeaMasukPerSatuanKnv").val(d.tarifbm.Jumlah);
			$("#txtBeaMasukKdSatuanKnv").val(d.tarifbm.KdSatuan);
			$("#txtBeaMasukKdSatuanKnv", window.parent.document)
		}
		if (d.tarifppnbm !== null) {
			$("#txtPPnBMTarifKnv").val(d.tarifppnbm.Tarif);
			$("#txtPPnBMFasTrfKnv").val(d.tarifppnbm.KdJenisFasilitasTarif);
			$("#txtPPnBMFasTrfKnv", window.parent.document);
			if (d.tarifppnbm.KdJenisFasilitasTarif === "DBY" || d.tarifppnbm.KdJenisFasilitasTarif === "") {
				$("#fldkonversippnbmtariffasilitas").addClass("hidden")
			} else {
				$("#fldkonversippnbmtariffasilitas").removeClass("hidden")
			}
			$("#txtPPnBMTarifFasilitasKnv").val(d.tarifppnbm.NilaiFasilitasTarif)
		}
		if (d.tarifpph !== null) {
			$("#txtPPhTarifKnv").val(d.tarifpph.Tarif);
			$("#txtPPhFasTrfKnv").val(d.tarifpph.KdJenisFasilitasTarif);
			$("#txtPPhFasTrfKnv", window.parent.document);
			if (d.tarifpph.KdJenisFasilitasTarif === "DBY" || d.tarifpph.KdJenisFasilitasTarif === "") {
				$("#fldkonversipphtariffasilitas").addClass("hidden")
			} else {
				$("#fldkonversipphtariffasilitas").removeClass("hidden")
			}
			$("#txtPPhTarifFasilitasKnv").val(d.tarifpph.NilaiFasilitasTarif)
		}
		if (d.tarifcukai !== null) {
			$("#txtCukaiPerhitKnv").val(d.tarifcukai.Tarif);
			$("#txtCukaiPerhitKnv", window.parent.document);
			$("#txtCukaiTarifKnv").val(d.tarifcukai.Tarif);
			$("#txtCukaiPerSatuanKnv").val(d.tarifcukai.Jumlah);
			$("#txtCukaiFasTrfKnv").val(d.tarifcukai.KdJenisFasilitasTarif);
			$("#txtCukaiFasTrfKnv", window.parent.document);
			$("#txtCukaiPerhitKnv").val(d.tarifcukai.PerhitunganTarif);
			$("#txtCukaiPerhitKnv", window.parent.document);
			if (d.tarifcukai.PerhitunganTarif === "2") {
				$("#fldkonversicukaijumlahsatuan").removeClass("hidden")
			} else {
				$("#fldkonversicukaijumlahsatuan").addClass("hidden")
			}
			$("#txtCukaiTarifKnv").val(d.tarifcukai.Tarif);
			$("#txtCukaiFasTrfKnv").val(d.tarifcukai.KdJenisFasilitasTarif);
			$("#txtCukaiFasTrfKnv", window.parent.document);
			if (d.tarifcukai.KdJenisFasilitasTarif === "DBY" || d.tarifcukai.KdJenisFasilitasTarif === "") {
				$("#fldkonversicukaitariffasilitas").addClass("hidden")
			} else {
				$("#fldkonversicukaitariffasilitas").removeClass("hidden")
			}
			$("#txtCukaiTarifFasilitasKnv").val(d.tarifcukai.NilaiFasilitasTarif);
			$("#txtCukaiPerSatuanKnv").val(d.tarifcukai.Jumlah);
			$("#txtCukaiKdSatuanKnv").val(d.tarifcukai.KdSatuan);
			$("#txtCukaiKdSatuanKnv", window.parent.document)
		}
		$("#btnSaveBarang").removeClass("hidden");
		$('#ppftz-konversi-form input[name="content"]').val("edit");
		openrowformaddkonversi()
	})
};
var tarikDataPPFTZMasuk = function () {
	if ($("#txtKdKantorAsal").val() !== "" && $("#txtNoPpftzMasukKnv").val() !== "" && $("#txtTglPpftzMasukKnv").val() !== "" && $("#txtSeriBrgPpftzMasukKnv").val() !== "") {
		$.ajaxQueue({
			url: "ppftz-konversi.html",
			type: "POST",
			data: {
				content: "tarikDataPPFTZMasuk",
				kdAsalBrgKnv: $("#txtKdAsalBarangKnv").val(),
				kdKantor: $("#txtKdKantorAsal").val(),
				noDaftar: $("#txtNoPpftzMasukKnv").val(),
				tglDaftar: $("#txtTglPpftzMasukKnv").val(),
				seriBarang: $("#txtSeriBrgPpftzMasukKnv").val()
			}
		}).done(function (b, a) {
			var c = $.parseJSON(b);
			if (c.error === null) {
				$("#txtNoPpftzMasukKnv").val(c.NoPPFTZMasuk);
				$("#txtTglPpftzMasukKnv").val(c.TglPPFTZMasuk);
				$("#txtSeriBrgPpftzMasukKnv").val(c.SeriBarang);
				$("#txtPosTarifKnv").val(c.PosTarif);
				$("#txtUrBarangKnv").val(c.UraianBarang);
				$("#txtJumlahSatuanKnv").val(c.JumlahSatuan);
				$("#txtKdSatuanKnv").val(c.KdSatuan);
				$("#txtKdSatuanKnv", window.parent.document).select2({
					width: "100%"
				});
				$("#txtKdValutaKnv").val(c.Valuta);
				$("#txtKdValutaKnv", window.parent.document).select2({
					width: "100%"
				});
				$("#txtNilaiPabeanKnv").val(c.NilaiPabean);
				getkurs("txtNdpbmKnv", c.Valuta);
				loaddatahscode(c.PosTarif, "labelpostarifkonversi", "validationTextPosTarifKnv", "txtBeaMasukPerhitKnv", "txtBeaMasukTarifKnv", "fldkonversibeamasukjumlahsatuang", "txtBeaMasukKdSatuanKnv", "", "txtPPnBMTarifKnv")
			} else {
				$.smallBox({
					title: "Error !",
					content: "<i class='fa fa-clock-o'>" + c.error + "</i> <i>1 seconds ago...</i>",
					color: "#C46A69",
					iconSmall: "fa fa-times fa-2x bounce animated",
					timeout: 6000
				})
			}
		})
	} else {
		$.smallBox({
			title: "<i class='fa fa-warning'></i> Mohon isi Kode Kantor, Nomor, Tanggal, Seri Barang PPFTZ Pemasukan<br/> ",
			content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
			color: "#C46A69",
			iconSmall: "fa fa-times fa-2x bounce animated",
			timeout: 6000
		})
	}
};
var openHideKonversi = function (a) {
	if ($("#TrKonversiBrg" + a).hasClass("hidden")) {
		$("#TrKonversiBrg" + a).removeClass("hidden");
		$("#btnopenhideknv" + a).html('<i class="fa fa-minus"></i>');
		if ($("#TdKonversiBrg" + a).html() === "") {
			loadPpftzKonversi(a)
		}
	} else {
		$("#TrKonversiBrg" + a).addClass("hidden");
		$("#btnopenhideknv" + a).html('<i class="fa fa-plus"></i>')
	}
};
var deleteKonversi = function (a) {
	$.SmartMessageBox({
		title: "<i class='fa fa-trash txt-color-orangeDark'></i> Delete Konversi !",
		content: "Anda yakin menghapus Konversi ini??",
		buttons: "[No][Yes]"
	}, function (b) {
		if (b === "Yes") {
			$("#btndeleteKonversi" + a).html('<i class="fa fa-spinner fa-spin"></i>');
			$.ajaxQueue({
				url: "ppftz-konversi.html",
				type: "POST",
				data: {
					content: "delete",
					txtIdPpftzKonversi: a
				}
			}).done(function (c) {
				if (c === "timeout") {
					$.smallBox({
						title: "Session time out!<br/>Silahkan login lagi! ...",
						content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
						color: "#C46A69",
						iconSmall: "fa fa-times fa-2x bounce animated",
						timeout: 4000
					})
				} else {
					if (c === "success") {
						$.smallBox({
							title: "Success ...<br/>Data Konversi berhasil dihapus ...",
							content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
							color: "#5F895F",
							iconSmall: "fa fa-times fa-2x bounce animated",
							timeout: 4000
						});
						$("#rowKonversi" + a).remove();
						loadvalidator()
					} else {
						$.smallBox({
							title: "Error system ...<br/> " + c,
							content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
							color: "#C46A69",
							iconSmall: "fa fa-times fa-2x bounce animated",
							timeout: 6000
						});
						$("#btndeleteKonversi" + a).html('<i class="fa fa-trash"></i>')
					}
				}
			})
		}
	})
};
$("#txtKotaTtdPengusaha,#txtTglTtdPengusaha,#txtNmTtdPengusaha").change(function () {
	if ($("#txtKotaTtdPengusaha").val() !== "" && $("#txtTglTtdPengusaha").val() !== "" && $("#txtNmTtdPengusaha").val() !== "") {
		$("#btnKirimDokumen").removeClass("hidden")
	} else {
		$("#btnKirimDokumen").addClass("hidden")
	}
});
var getkurs = function (b, a) {
	$.ajaxQueue({
		url: "ppftzref.html",
		type: "POST",
		data: {
			content: "getdatakurs",
			kdvaluta: a
		}
	}).done(function (e, c) {
		var d = $.parseJSON(e);
		var f = $.parseJSON(e).length;
		$("#" + b).val(d.ndpbm).removeAttr("disabled")
	})
};
var getBarangByFaktur = function (a) {
	$.SmartMessageBox({
		title: "Service Pajak!",
		content: "Ambil daftar Barang berdasarkan No Faktur ini ? ",
		buttons: "[No][Yes]"
	}, function (b) {
		if (b === "Yes") {
			$("#btnSaveDokumen").html('<i class="fa fa-cog fa-spin"></i> Loading').attr("disabled", "disabled");
			$.ajaxQueue({
				url: "ppftz-dokumen.html",
				type: "POST",
				data: {
					content: "getfakturpajak",
					txtNoDokumen: a,
					txtIdPpftz: idppftz
				}
			}).done(function (d, c) {
				if (d === "success") {
					$.smallBox({
						title: "Success ...<br/>simpan data barang ...",
						content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
						color: "#5F895F",
						iconSmall: "fa fa-times fa-2x bounce animated",
						timeout: 4000
					});
					clearFormDokumen();
					closeModal();
					loadPpftzDokumen();
					loadPpftzBarang(1);
					loadSelectPpftzDokumen();
					loadvalidator()
				} else {
					if (d === "notfound") {
						$.smallBox({
							title: "Gagal mendapatkan data barang !",
							content: "<i class='fa fa-clock-o'></i> <i>No Faktur tidak ditemukan</i>",
							color: "#C46A69",
							iconSmall: "fa fa-times fa-2x bounce animated",
							timeout: 6000
						})
					} else {
						$.smallBox({
							title: "Error !",
							content: "<i class='fa fa-clock-o'>" + d + "</i> <i>1 seconds ago...</i>",
							color: "#C46A69",
							iconSmall: "fa fa-times fa-2x bounce animated",
							timeout: 6000
						})
					}
				}
				$("#btnSaveDokumen").html('<i class="fa fa-save"></i> Save').removeAttr("disabled")
			})
		}
	})
};
var loadUraianPelabuhan = function (d, a, e) {
	$("#" + a).html('<i class="fa fa-cog fa-spin"></i> Loading...');
	if (e.length !== 5) {
		$("#" + a).html("<b>Kode Pelabuhan Harus 5 Huruf</b>")
	} else {
		var c = e.toUpperCase();
		if (localStorage.getItem(c) == null) {
			$.ajaxQueue({
				url: "ppftzref.html",
				type: "POST",
				data: {
					content: "getUrPelabuhan",
					kdPelabuhan: e
				}
			}).done(function (h, f) {
				var g = $.parseJSON(h);
				localStorage.setItem(c, h);
				$("#" + d).val(g.kode);
				$("#" + a).html("<b>" + g.uraian + "</b>")
			})
		} else {
			var b = $.parseJSON(localStorage.getItem(c));
			$("#" + d).val(b.kode);
			$("#" + a).html("<b>" + b.uraian + "</b>")
		}
	}
};
var openModalPelabuhan = function (a) {
	$("#txtForFieldPelabuhan").val(a);
	if ($("#tableListPelabuhan").html() !== "") {
		cariPelabuhan()
	}
};
var kodePelabuhan = "";
var uraianPelabuhan = "";
var jnPel = "";
var cariPelabuhan = function () {
	var a = $("#txtFieldCariPelabuhan").val();
	var b = $("#txtParameterCariPelabuhan").val();
	jnPel = $("#txtForFieldPelabuhan").val();
	$("#tableListPelabuhan").html('<tr><td colspan="2"><i class="fa fa-cog fa-spin"></i> Loading...</td></tr>');
	$.ajaxQueue({
		url: "ppftzref.html",
		type: "POST",
		data: {
			content: "getCariPelabuhan",
			fieldValue: a,
			paramValue: b
		}
	}).done(function (h, c) {
		var f = $.parseJSON(h);
		var g = "";
		if (f.error === null) {
			var j = f.listPel;
			var d = j.length;
			var e;
			for (e = 0; e < d; e++) {
				kodePelabuhan = j[e].kode;
				uraianPelabuhan = j[e].uraian;
				g += '<tr class="pointer" onclick="appendPelabuhan(\'' + kodePelabuhan + '\',\'' + jnPel + '\')"><td>' + kodePelabuhan + "</td><td>" + uraianPelabuhan + "</td></tr>"
			}
		} else {
			g = '<tr><td colspan="2">' + f.error + "</td></tr>"
		}
		$("#tableListPelabuhan").html(g)
	})
};
var appendPelabuhan = function (a, b) {
	loadUraianPelabuhan("txtKdPelabuhan" + b, "txtUraianPelabuhan" + b, a);
	closeModal()
};
var loadlistReverensiPure = function (f, e, c, b) {
	$("#" + f).find("option:gt(0)").remove();
	var a = document.getElementById(f);
	if (localStorage.getItem(e) === null) {
		$.ajaxQueue({
			url: "ppftzref.html",
			type: "POST",
			data: {
				content: e
			}
		}).done(function (l, g) {
			var j = $.parseJSON(l);
			localStorage.setItem(e, l);
			var m = $.parseJSON(l).length;
			var h;
			for (h = 0; h < m; h++) {
				var k = document.createElement("option");
				k.value = j[h].kode;
				k.innerHTML = j[h].kode + " - " + j[h].uraian;
				a.appendChild(k)
			}
		}).done(function () {
			$("#" + f).val(c);
			if (b === "y") {
				$("#" + f, window.parent.document).select2({
					width: "100%"
				})
			}
		})
	} else {
		var d = function () {
			var h = $.parseJSON(localStorage.getItem(e));
			var k = $.parseJSON(localStorage.getItem(e)).length;
			var g;
			for (g = 0; g < k; g++) {
				var j = document.createElement("option");
				j.value = h[g].kode;
				j.innerHTML = h[g].kode + " - " + h[g].uraian;
				a.appendChild(j)
			}
		};
		$.when(d()).done(function () {
			$("#" + f).val(c);
			if (b === "y") {
				$("#" + f, window.parent.document).select2({
					width: "100%"
				})
			}
		})
	}
};
var loadlistRevParPpftz = function (g, f, d, c, e) {
	$("#" + g).find("option:gt(0)").remove();
	var a = document.getElementById(g);
	if (localStorage.getItem(f + e) === null) {
		$.ajaxQueue({
			url: "ppftzref.html",
			type: "POST",
			data: {
				content: f,
				ppftz: e
			}
		}).done(function (m, h) {
			var k = $.parseJSON(m);
			localStorage.setItem(f + e, m);
			var n = $.parseJSON(m).length;
			var j;
			for (j = 0; j < n; j++) {
				var l = document.createElement("option");
				l.value = k[j].kode;
				l.innerHTML = k[j].kode + " - " + k[j].uraian;
				a.appendChild(l)
			}
		}).done(function () {
			$("#" + g).val(d);
			if (c === "y") {
				$("#" + g, window.parent.document).select2({
					width: "100%"
				})
			}
		})
	} else {
		var b = function () {
			var j = $.parseJSON(localStorage.getItem(f + e));
			var l = $.parseJSON(localStorage.getItem(f + e)).length;
			var h;
			for (h = 0; h < l; h++) {
				var k = document.createElement("option");
				k.value = j[h].kode;
				k.innerHTML = j[h].kode + " - " + j[h].uraian;
				a.appendChild(k)
			}
		};
		$.when(b()).done(function () {
			$("#" + g).val(d);
			if (c === "y") {
				$("#" + g, window.parent.document).select2({
					width: "100%"
				})
			}
		})
	}
};
var loadlistTPS = function (c) {
	$("#txtKdTps").find("option:gt(0)").remove();
	var b = document.getElementById("txtKdTps");
	if (localStorage.getItem("listTPS" + c) === null) {
		$.ajaxQueue({
			url: "ppftzref.html",
			type: "POST",
			data: {
				content: "listTPS",
				kdkantor: c
			}
		}).done(function (h, d) {
			var f = $.parseJSON(h);
			var j = $.parseJSON(h).length;
			localStorage.setItem("listTPS" + c, h);
			var e;
			for (e = 0; e < j; e++) {
				var g = document.createElement("option");
				g.value = f[e].kode;
				g.innerHTML = f[e].kode + " - " + f[e].uraian;
				b.appendChild(g)
			}
		}).done(function () {
			$("#txtKdTps option[value='']").attr("selected", "selected")
		})
	} else {
		var a = function () {
			var e = $.parseJSON(localStorage.getItem("listTPS" + c));
			var g = $.parseJSON(localStorage.getItem("listTPS" + c)).length;
			var d;
			for (d = 0; d < g; d++) {
				var f = document.createElement("option");
				f.value = e[d].kode;
				f.innerHTML = e[d].kode + " - " + e[d].uraian;
				b.appendChild(f)
			}
		};
		$.when(a()).done(function () {
			$("#txtKdTps option[value='']").attr("selected", "selected");
			$("#txtKdTps", window.parent.document).select2({
				width: "100%"
			})
		})
	}
};
var loadlistfastrf = function (c, b) {
	$("#" + c).find("option:gt(0)").remove();
	var a = document.getElementById(c);
	if (localStorage.getItem("listJenisFasilitasTarif" + b) === null) {
		$.ajaxQueue({
			url: "ppftzref.html",
			type: "POST",
			data: {
				content: "listJenisFasilitasTarif",
				flcukai: b
			}
		}).done(function (j, e) {
			var g = $.parseJSON(j);
			var k = $.parseJSON(j).length;
			localStorage.setItem("listJenisFasilitasTarif" + b, j);
			var f;
			for (f = 0; f < k; f++) {
				var h = document.createElement("option");
				h.value = g[f].kode;
				h.innerHTML = g[f].kode + " - " + g[f].uraian;
				a.appendChild(h)
			}
		})
	} else {
		var d = function () {
			var f = $.parseJSON(localStorage.getItem("listJenisFasilitasTarif" + b));
			var h = $.parseJSON(localStorage.getItem("listJenisFasilitasTarif" + b)).length;
			var e;
			for (e = 0; e < h; e++) {
				var g = document.createElement("option");
				g.value = f[e].kode;
				g.innerHTML = f[e].kode + " - " + f[e].uraian;
				a.appendChild(g)
			}
		};
		d()
	}
};
var loadlistsubkomoditicukai = function (d, b, c) {
	$("#" + d).find("option:gt(0)").remove();
	var a = document.getElementById(d);
	if (localStorage.getItem("listSubKomoditiCukai" + b) === null) {
		$.ajaxQueue({
			url: "ppftzref.html",
			type: "POST",
			data: {
				content: "listSubKomoditiCukai",
				komoditiCukai: b
			}
		}).done(function (k, f) {
			var h = $.parseJSON(k);
			var l = $.parseJSON(k).length;
			localStorage.setItem("listSubKomoditiCukai" + b, k);
			var g;
			for (g = 0; g < l; g++) {
				var j = document.createElement("option");
				j.value = h[g].kode;
				j.innerHTML = h[g].kode + " - " + h[g].uraian;
				a.appendChild(j)
			}
		}).done(function () {
			setTimeout(function () {
				$("#" + d).val(c)
			}, 500)
		})
	} else {
		var e = function () {
			var g = $.parseJSON(localStorage.getItem("listSubKomoditiCukai" + b));
			var j = g.length;
			var f;
			for (f = 0; f < j; f++) {
				var h = document.createElement("option");
				h.value = g[f].kode;
				h.innerHTML = g[f].kode + " - " + g[f].uraian;
				a.appendChild(h)
			}
		};
		$.when(e()).done(function () {
			setTimeout(function () {
				$("#" + d).val(c)
			}, 500)
		})
	}
};
var loadTarifCukai = function (b, a) {
	if (localStorage.getItem("tarifCukai" + a) === null) {
		$.ajaxQueue({
			url: "ppftzref.html",
			type: "POST",
			data: {
				content: "getTarifCukai",
				kdSubKomoditiCukai: a
			}
		}).done(function (g, d) {
			var e = $.parseJSON(g);
			var f = "";
			if (b === "") {
				f = "brg"
			} else {
				f = "konversi"
			}
			localStorage.setItem("tarifCukai" + a, g);
			$("#txtCukaiPerhit" + b).val("2");
			$("#txtCukaiTarif" + b).val(e.tarifSpesifik);
			$("#txtCukaiKdSatuan" + b).val(e.kodeSatuan);
			$("#txtCukaiKdSatuan" + b, window.parent.document).select2({
				width: "100%"
			});
			$("#txtCukaiHje" + b).val(e.hargaJualEceran);
			$("#fld" + f + "cukaijumlahsatuan").removeClass("hidden")
		})
	} else {
		var c = function () {
			var d = $.parseJSON(localStorage.getItem("tarifCukai" + a));
			var e = "";
			if (b === "") {
				e = "brg"
			} else {
				e = "konversi"
			}
			$("#txtCukaiPerhit" + b).val("2");
			$("#txtCukaiTarif" + b).val(d.tarifSpesifik);
			$("#txtCukaiKdSatuan" + b).val(d.kodeSatuan);
			$("#txtCukaiKdSatuan" + b, window.parent.document).select2({
				width: "100%"
			});
			$("#txtCukaiHje" + b).val(d.hargaJualEceran);
			$("#fld" + e + "cukaijumlahsatuan").removeClass("hidden")
		};
		c()
	}
};
var loadvalidator = function () {
	$.ajaxQueue({
		url: "validator.html",
		type: "POST",
		data: {
			content: "validator",
			txtIdPpftz: idppftz
		}
	}).done(function (f, b) {
		if (f === "null") {
			$("#listvalidator").html("error Null")
		} else {
			var h = $.parseJSON(f);
			var g = h.status;
			var e = "";
			if (g === "ready") {
				$("#btnStatusPengisianDokumen").html('<div class="statusready pointer" data-toggle="modal" data-target="#modalKirimDokumen"><i class="fa fa-paper-plane"></i> Kirim</div>')
			} else {
				var a = h.validate;
				var c = a.length;
				var d;
				for (d = 0; d < c; d++) {
					e += d + 1 + ". " + a[d].uraian + " <br>"
				}
				$("#listvalidator").html(e);
				$("#btnStatusPengisianDokumen").html('<div class="statusedit pointer" data-toggle="modal" data-target="#modalStatusPengisianDokumen">Edit</div>')
			}
		}
	})
};
var loaddatahscode = function (b, d, e, k, c, n, j, a, m) {
	if (b.length === 8) {
		if (localStorage.getItem(b) === null) {
			$.ajaxQueue({
				url: "ppftzref.html",
				type: "POST",
				data: {
					content: "getdatahscode",
					txtHsCode: b
				}
			}).done(function (u) {
				localStorage.setItem(b, u);
				var s = $.parseJSON(u);
				if (s.error !== null) {
					$("#" + d).removeClass("state-success").addClass("state-error").removeClass("valid");
					$("#" + e).removeClass("hidden").text(s.error)
				} else {
					$("#" + d).removeClass("state-error").addClass("state-success");
					$("#" + d).addClass("valid");
					$("#" + e).addClass("hidden");
					$("#" + k).val(s.KD_TRF_BM);
					if (s.KD_TRF_BM === "1") {
						$("#" + c).val(s.TRF_BM);
						$("#" + n).addClass("hidden")
					} else {
						$("#" + c).val(s.TRF_BM);
						$("#" + j).val(s.KD_SAT_BM);
						$("#" + j, window.parent.document).select2({
							width: "100%"
						});
						$("#" + n).removeClass("hidden")
					}
					$("#" + a).val(s.TRF_PPN);
					$("#" + m).val(s.TRF_PPNBM);
					if (d === "labelpostarif") {
						if (s.FL_AP === "") {
							$("#txtBarangFlLartas").val("N");
							$("#tombolLartas").addClass("hidden");
							$("#txtKeteranganLartas").html("")
						} else {
							$("#txtBarangFlLartas").val("Y");
							$("#tombolLartas").removeClass("hidden");
							var q = s.LIST_LARTAS;
							var t = "";
							for (var r = 0; r < q.length; r++) {
								if (q[r].FlagLartas === "P") {
									var w = "PEMBATASAN"
								} else {
									var w = "LARANGAN"
								}
								if (q[r].FlagAp === "1") {
									var v = "Barang mungkin membutuhkan SKEP"
								} else {
									var v = "Barang membutuhkan SKEP"
								}
								t += '<p style="padding-left: 2em; text-indent:-2em;"><b>' + w + '</b></p><p style="padding-left: 2em; text-indent:-2em;">Uraian Ijin : <b>' + q[r].UrIjin + '</b></p><p style="padding-left: 2em; text-indent:-2em;">Nomor Skep : <b>' + q[r].NoSkep + '</b></p><p style="padding-left: 2em; text-indent:-2em;">Ur Barang Skep : <b>' + q[r].UrBarangSkep + '</b></p><p style="padding-left: 2em; text-indent:-2em;">Keterangan : <b>' + v + "</b></p><br><br>"
							}
							$("#txtKeteranganLartas").html(t)
						}
					}
				}
			})
		} else {
			var o = $.parseJSON(localStorage.getItem(b));
			if (o.error !== null) {
				$("#" + d).removeClass("state-success").addClass("state-error").removeClass("valid");
				$("#" + e).removeClass("hidden").text(o.error)
			} else {
				$("#" + d).removeClass("state-error").addClass("state-success");
				$("#" + d).addClass("valid");
				$("#" + e).addClass("hidden");
				$("#" + k).val(o.KD_TRF_BM);
				if (o.KD_TRF_BM === "1") {
					$("#" + c).val(o.TRF_BM);
					$("#" + n).addClass("hidden")
				} else {
					$("#" + c).val(o.TRF_BM);
					$("#" + j).val(o.KD_SAT_BM);
					$("#" + j, window.parent.document).select2({
						width: "100%"
					});
					$("#" + n).removeClass("hidden")
				}
				$("#" + a).val(o.TRF_PPN);
				$("#" + m).val(o.TRF_PPNBM);
				if (d === "labelpostarif") {
					if (o.FL_AP === "") {
						$("#txtBarangFlLartas").val("N");
						$("#tombolLartas").addClass("hidden");
						$("#txtKeteranganLartas").html("")
					} else {
						$("#txtBarangFlLartas").val("Y");
						$("#tombolLartas").removeClass("hidden");
						var g = o.LIST_LARTAS;
						var l = "";
						for (var f = 0; f < g.length; f++) {
							if (g[f].FlagLartas === "P") {
								var p = "PEMBATASAN"
							} else {
								var p = "LARANGAN"
							}
							if (g[f].FlagAp === "1") {
								var h = "Barang mungkin membutuhkan SKEP"
							} else {
								var h = "Barang membutuhkan SKEP"
							}
							l += '<p style="padding-left: 2em; text-indent:-2em;"><b>' + p + '</b></p><p style="padding-left: 2em; text-indent:-2em;">Uraian Ijin : <b>' + g[f].UrIjin + '</b></p><p style="padding-left: 2em; text-indent:-2em;">Nomor Skep : <b>' + g[f].NoSkep + '</b></p><p style="padding-left: 2em; text-indent:-2em;">Ur Barang Skep : <b>' + g[f].UrBarangSkep + '</b></p><p style="padding-left: 2em; text-indent:-2em;">Keterangan : <b>' + h + "</b></p><br><br>"
						}
						$("#txtKeteranganLartas").html(l)
					}
				}
			}
		}
	}
};
var padNum = function (a, b) {
	b = b || 3;
	a += "";
	while (a.length < b) {
		a = "0" + a
	}
	return a
};
var pagefunction = function () {
	var a = "invalid";
	var b = "em";
	$("#ppftz-form").validate({
		errorClass: a,
		errorElement: b,
		submitHandler: function (c) {
			$(c).ajaxSubmit({
				type: "POST",
				url: "ppftz.html",
				data: "",
				success: function (d) {
					if (d === "timeout") {
						$.smallBox({
							title: "Session time out!<br></b>Silahkan login lagi! ...",
							content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
							color: "#C46A69",
							iconSmall: "fa fa-times fa-2x bounce animated",
							timeout: 4000
						})
					} else {
						if (d === "success") {
							$.smallBox({
								title: "Success ...<br/>Data Ppftz berhasil disimpan ...",
								content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
								color: "#5F895F",
								iconSmall: "fa fa-times fa-2x bounce animated",
								timeout: 4000
							});
							loadvalidator()
						} else {
							$.smallBox({
								title: "Error system ...<br/> " + d,
								content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
								color: "#C46A69",
								iconSmall: "fa fa-times fa-2x bounce animated",
								timeout: 6000
							})
						}
					}
				}
			})
		},
		errorPlacement: function (c, d) {
			c.insertAfter(d.parent())
		}
	});
	$("#ppftz-kemasan-form").validate({
		errorClass: a,
		errorElement: b,
		submitHandler: function (c) {
			$(c).ajaxSubmit({
				type: "POST",
				url: "ppftz-kemasan.html",
				data: "",
				success: function (d) {
					if (d === "timeout") {
						$.smallBox({
							title: "Session time out!<br/>Silahkan login lagi! ...",
							content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
							color: "#C46A69",
							iconSmall: "fa fa-times fa-2x bounce animated",
							timeout: 4000
						})
					} else {
						if (d === "success") {
							$.smallBox({
								title: "Success ...<br/>Data Ppftz Kemasan berhasil disimpan ...",
								content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
								color: "#5F895F",
								iconSmall: "fa fa-times fa-2x bounce animated",
								timeout: 4000
							});
							loadPpftzKemasan();
							closeModal();
							clearFormKemasan();
							loadvalidator()
						} else {
							$.smallBox({
								title: "Error system ...<br/> " + d,
								content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
								color: "#C46A69",
								iconSmall: "fa fa-times fa-2x bounce animated",
								timeout: 6000
							})
						}
					}
				}
			})
		},
		errorPlacement: function (c, d) {
			c.insertAfter(d.parent())
		}
	});
	$("#ppftz-kontainer-form").validate({
		errorClass: a,
		errorElement: b,
		submitHandler: function (c) {
			$(c).ajaxSubmit({
				type: "POST",
				url: "ppftz-kontainer.html",
				data: "",
				success: function (d) {
					if (d === "timeout") {
						$.smallBox({
							title: "Session time out!<br/>Silahkan login lagi! ...",
							content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
							color: "#C46A69",
							iconSmall: "fa fa-times fa-2x bounce animated",
							timeout: 4000
						})
					} else {
						if (d === "success") {
							$.smallBox({
								title: "Success ...<br/>Data Ppftz Kontainer berhasil disimpan ...",
								content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
								color: "#5F895F",
								iconSmall: "fa fa-times fa-2x bounce animated",
								timeout: 4000
							});
							loadPpftzKontainer();
							closeModal();
							clearFormKontainer();
							loadvalidator()
						} else {
							$.smallBox({
								title: "Error system ...<br/> " + d,
								content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
								color: "#C46A69",
								iconSmall: "fa fa-times fa-2x bounce animated",
								timeout: 6000
							})
						}
					}
				}
			})
		},
		errorPlacement: function (c, d) {
			c.insertAfter(d.parent())
		}
	});
	$("#ppftz-bank-devisa-ekspor-form").validate({
		errorClass: a,
		errorElement: b,
		submitHandler: function (c) {
			$(c).ajaxSubmit({
				type: "POST",
				url: "ppftz-bank-devisa-ekspor.html",
				data: "",
				success: function (d) {
					if (d === "timeout") {
						$.smallBox({
							title: "Session time out!<br/>Silahkan login lagi! ...",
							content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
							color: "#C46A69",
							iconSmall: "fa fa-times fa-2x bounce animated",
							timeout: 4000
						})
					} else {
						if (d === "success") {
							$.smallBox({
								title: "Success ...<br/>Data Ppftz Bank Devisa Ekspor berhasil disimpan ...",
								content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
								color: "#5F895F",
								iconSmall: "fa fa-times fa-2x bounce animated",
								timeout: 4000
							});
							loadPpftzBankDevisaEkspor();
							closeModal();
							clearFormBankDHE();
							loadvalidator()
						} else {
							$.smallBox({
								title: "Error system ...<br/> " + d,
								content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
								color: "#C46A69",
								iconSmall: "fa fa-times fa-2x bounce animated",
								timeout: 6000
							})
						}
					}
				}
			})
		},
		errorPlacement: function (c, d) {
			c.insertAfter(d.parent())
		}
	});
	$("#ppftz-konversi-form").validate({
		errorClass: a,
		errorElement: b,
		submitHandler: function (c) {
			$(c).ajaxSubmit({
				type: "POST",
				url: "ppftz-konversi.html",
				data: "",
				success: function (d) {
					if (d === "timeout") {
						$.smallBox({
							title: "Session time out!<br/>Silahkan login lagi! ...",
							content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
							color: "#C46A69",
							iconSmall: "fa fa-times fa-2x bounce animated",
							timeout: 4000
						})
					} else {
						if (d === "success") {
							$.smallBox({
								title: "Success ...<br/>Data Ppftz Konversi berhasil disimpan ...",
								content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
								color: "#5F895F",
								iconSmall: "fa fa-times fa-2x bounce animated",
								timeout: 4000
							});
							loadPpftzKonversi();
							loadvalidator();
							closerowformaddkonversi()
						} else {
							$.smallBox({
								title: "Error system ...<br/> " + d,
								content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
								color: "#C46A69",
								iconSmall: "fa fa-times fa-2x bounce animated",
								timeout: 6000
							})
						}
					}
				}
			})
		},
		errorPlacement: function (c, d) {
			c.insertAfter(d.parent())
		}
	});
	$("#ppftz-barang-form").validate({
		errorClass: a,
		errorElement: b,
		submitHandler: function (c) {
			$("#btnSaveBarang").html('<i class="fa fa-cog fa-spin"></i> Loading').attr("disabled", "disabled");
			$(c).ajaxSubmit({
				type: "POST",
				url: "ppftz-barang.html",
				data: {
					JenisPPFTZ: ppftz
				},
				success: function (d) {
					if (d === "timeout") {
						$.smallBox({
							title: "Session time out!<br/>Silahkan login lagi! ...",
							content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
							color: "#C46A69",
							iconSmall: "fa fa-times fa-2x bounce animated",
							timeout: 4000
						})
					} else {
						if (d === "success") {
							$.smallBox({
								title: "Success ...<br/>Data Ppftz Barang berhasil disimpan ...",
								content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
								color: "#5F895F",
								iconSmall: "fa fa-times fa-2x bounce animated",
								timeout: 4000
							});
							loadPpftzBarang(1);
							loadvalidator();
							closerowformaddbarang()
						} else {
							$.smallBox({
								title: "Error system ...<br/> " + d,
								content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
								color: "#C46A69",
								iconSmall: "fa fa-times fa-2x bounce animated",
								timeout: 6000
							})
						}
					}
					$("#btnSaveBarang").html('<i class="fa fa-save"></i> Save').removeAttr("disabled")
				}
			})
		},
		errorPlacement: function (c, d) {
			c.insertAfter(d.parent())
		}
	});
	$("#ppftz-dokumen-form").validate({
		errorClass: a,
		errorElement: b,
		submitHandler: function (c) {
			if ($("#txtKdDokumen option:selected").val() === "388" && ppftz === "PPFTZ03_1") {
				getBarangByFaktur($("#txtNoDokumen").val())
			} else {
				$(c).ajaxSubmit({
					type: "POST",
					url: "ppftz-dokumen.html",
					data: "",
					success: function (d) {
						if (d === "timeout") {
							$.smallBox({
								title: "Session time out!<br/>Silahkan login lagi! ...",
								content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
								color: "#C46A69",
								iconSmall: "fa fa-times fa-2x bounce animated",
								timeout: 4000
							})
						} else {
							if (d === "success") {
								$.smallBox({
									title: "Success ...<br/>Data Ppftz Dokumen berhasil disimpan ...",
									content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
									color: "#5F895F",
									iconSmall: "fa fa-times fa-2x bounce animated",
									timeout: 4000
								});
								loadPpftzDokumen();
								closeModal();
								clearFormDokumen();
								loadvalidator();
								loadSelectPpftzDokumen()
							} else {
								$.smallBox({
									title: "Error system ...<br/> " + d,
									content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
									color: "#C46A69",
									iconSmall: "fa fa-times fa-2x bounce animated",
									timeout: 6000
								})
							}
						}
					}
				})
			}
		},
		errorPlacement: function (c, d) {
			c.insertAfter(d.parent())
		}
	});
	$("#ppftz-tarik-bc11-form").validate({
		errorClass: a,
		errorElement: b,
		submitHandler: function (c) {
			$("#btnGetDataBc11").html('<i class="fa fa-cog fa-spin"></i> Save').attr("disabled", "disabled").button("refresh");
			$(c).ajaxSubmit({
				type: "POST",
				url: "ppftz-tarik-bc11.html",
				data: "",
				success: function (e) {
					var f = $.parseJSON(e);
					if (f.error === null) {
						var d = f.DataHeader;
						$.smallBox({
							title: "Success ...<br/>Data Manifes Berhasil Ditemukan ...",
							content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
							color: "#5F895F",
							iconSmall: "fa fa-times fa-2x bounce animated",
							timeout: 4000
						});
						loadPpftzDokumen();
						$("#txtNoBc11").val(d.NO_BC11);
						$("#txtTglBc11").val(d.TGL_BC11);
						$("#txtPosBc11").val(d.NO_POS.substring(0, 4));
						$("#txtSubposBc11").val(d.NO_POS.substring(4, 8));
						$("#txtSubsubposBc11").val(d.NO_POS.substring(8, 12));
						$("#txtKdCaraAngkut").val(d.KD_ANGKUT);
						$("#txtKdCaraAngkut", window.parent.document).select2({
							width: "100%"
						});
						$("#txtNmPengangkut").val(d.NM_PENGANGKUT);
						$("#txtKdBendera").val(d.KD_BENDERA);
						$("#txtKdBendera", window.parent.document).select2({
							width: "100%"
						});
						$("#txtNoVoyFlightPol").val(d.NO_VOYAGE);
						loadUraianPelabuhan("txtKdPelabuhanMuat", "txtUraianPelabuhanMuat", d.PEL_MUAT);
						loadUraianPelabuhan("txtKdPelabuhanTujuan", "txtUraianPelabuhanTujuan", d.PEL_BONGKAR);
						loadUraianPelabuhan("txtKdPelabuhanTransit", "txtUraianPelabuhanTransit", d.PEL_TRANSIT);
						closeModal();
						$("#btnGetDataBc11").html('<i class="fa fa-save"></i> Save').removeAttr("disabled").button("refresh")
					} else {
						$.smallBox({
							title: "<i class='fa fa-warning'></i> Warning ...<br/> " + f.error,
							content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
							color: "#C46A69",
							iconSmall: "fa fa-times fa-2x bounce animated",
							timeout: 6000
						});
						$("#btnGetDataBc11").html('<i class="fa fa-save"></i> Save').removeAttr("disabled").button("refresh")
					}
				}
			})
		},
		errorPlacement: function (c, d) {
			c.insertAfter(d.parent())
		}
	});
	$("#kirim-dokumen-form").validate({
		errorClass: a,
		errorElement: b,
		submitHandler: function (c) {
			$("#btnKirimDokumen").html('<i class="fa fa-cog fa-spin"></i> Kirim').attr("disabled", "disabled").button("refresh");
			$(c).ajaxSubmit({
				type: "POST",
				url: "kirim-dokumen.html",
				data: "",
				success: function (d) {
					if (d === "null" || d === null) {
						$.smallBox({
							title: "Error system ...<br/> Terjadi Kesalahan. Hubungi Petugas ",
							content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
							color: "#C46A69",
							iconSmall: "fa fa-times fa-2x bounce animated",
							timeout: 6000
						});
						$("#btnKirimDokumen").html('<i class="fa fa-paper-plane"></i> Kirim').removeAttr("disabled").button("refresh")
					} else {
						var e = $.parseJSON(d);
						if (e.error === null) {
							$.smallBox({
								title: "Success ...<br/>Dokumen Ppftz berhasil dikirim ...",
								content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
								color: "#5F895F",
								iconSmall: "fa fa-times fa-2x bounce animated",
								timeout: 4000
							});
							closeModal();
							setTimeout(function () {
								window.location = "#browse.html"
							}, 1000)
						} else {
							var g = "";
							for (var f = 0; f < e.error.length; f++) {
								g += e.error[f].uraian
							}
							$.smallBox({
								title: "Error system ...<br></e> " + g,
								content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
								color: "#C46A69",
								iconSmall: "fa fa-times fa-2x bounce animated",
								timeout: 6000
							});
							$("#btnKirimDokumen").html('<i class="fa fa-paper-plane"></i> Kirim').removeAttr("disabled").button("refresh")
						}
					}
				}
			})
		},
		errorPlacement: function (c, d) {
			c.insertAfter(d.parent())
		}
	});
	$("#upload-file-form").validate({
		errorClass: a,
		errorElement: b,
		submitHandler: function (c) {
			$(c).ajaxSubmit({
				type: "POST",
				url: "upload-file.html",
				data: "",
				headers: {
					txtMaxUkuran: "1280000"
				},
				success: function (e) {
					var d = $.parseJSON(e);
					if (d.urlFile === null) {
						$.smallBox({
							title: "File Gagal Diupload!! ...<br>" + d.message,
							content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
							color: "#C46A69",
							iconSmall: "fa fa-times fa-2x bounce animated",
							timeout: 4000
						})
					} else {
						$.smallBox({
							title: d.message + "... ",
							content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
							color: "#5F895F",
							iconSmall: "fa fa-times fa-2x bounce animated",
							timeout: 4000
						});
						$("#txtUrlFile").val(d.urlFile);
						$("#fieldButtonUpload").html('<a href="' + d.urlFile + '" target="_blank" class="btn btn-sm btn-success btn-block"><i class="fa fa-download"></i> Download</a>');
						$("#fieldButtonCancel").html('<div class="btn btn-sm btn-danger btn-block pointer" onclick="batalUpload()"><i class="fa fa-close"></i> Batal</div>')
					}
				}
			})
		},
		errorPlacement: function (c, d) {
			c.insertAfter(d.parent())
		}
	})
};
var loadPpftzDokumen = function () {
	$("#divPpftzDokumen").html('<h1><i class="fa fa-cog fa-spin"></i> Loading...</h1>');
	jQuery.ajaxQueue({
		url: "ppftz-dokumen.html",
		type: "POST",
		data: {
			content: "list",
			idppftz: idppftz
		}
	}).done(function (d, c) {
		var h = $.parseJSON(d);
		var j = h.Message;
		var a = "";
		if (j === "OK") {
			var f = h.Data;
			for (var e = 0; e < f.length; e++) {
				var g = e + 1;
				a += '<tr>  <td class="hidden">' + f[e].IdPpftzDokumen + "</td>  <td>" + g + "</td>  <td>" + f[e].KdDokumen + " - " + f[e].UrDokumen + "</td>  <td>" + f[e].NoDokumen + "</td>  <td>" + f[e].TglDokumen + '</td>  <td>     <a href="' + f[e].UrlDokumen + '" target="_blank" class="btn btn-warning btn-sm"><i class="fa fa-download"></i></a>     <div style="cursor: pointer" class="btnDeleteDokumen btn btn-danger btn-sm" ><i class="fa fa-trash"></i></div>  </td></tr>'
			}
		} else {
			a += '<tr><td colspan="5">Tidak ada dokumen ditemukan</td></tr>'
		}
		var b = '<table class="table table-striped table-bordered table-hover">  <thead>      <tr>          <th>NO</th>          <th>KODE DOKUMEN</th>          <th>NO DOKUMEN</th>          <th>TGL DOKUMEN</th>          <th>&nbsp;</th>      </tr>  </thead>  <tbody>' + a + "  </tbody></table>";
		$("#divPpftzDokumen").html(b)
	}).done(function () {
		$(".btnDeleteDokumen").each(function () {
			var a = $(this).parent().siblings(".hidden").html();
			$(this).attr("onclick", "return confirm('Anda Yakin Menghapus Dokumen Ini?') && deletedok('" + a + "')")
		})
	})
};
var deletedok = function (a) {
	$.post("ppftz-dokumen.html", {
		content: "delete",
		txtIdPpftzDokumen: a
	}).done(function (b) {
		if (b === "timeout") {
			$.smallBox({
				title: "Session time out!<br/>Silahkan login lagi! ...",
				content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
				color: "#C46A69",
				iconSmall: "fa fa-times fa-2x bounce animated",
				timeout: 4000
			})
		} else {
			if (b === "success") {
				$.smallBox({
					title: "Success ...<br/>Data Dokumen berhasil dihapus ...",
					content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
					color: "#5F895F",
					iconSmall: "fa fa-times fa-2x bounce animated",
					timeout: 4000
				});
				loadPpftzDokumen();
				loadSelectPpftzDokumen();
				loadvalidator()
			} else {
				$.smallBox({
					title: "Error system ...<br/> " + b,
					content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
					color: "#C46A69",
					iconSmall: "fa fa-times fa-2x bounce animated",
					timeout: 6000
				})
			}
		}
	})
};
var loadSelectPpftzDokumen = function () {
	$("#txtDokumenBrgDok").find("option:gt(0)").remove();
	var a = document.getElementById("txtDokumenBrgDok");
	$.ajaxQueue({
		url: "ppftzref.html",
		type: "POST",
		data: {
			content: "listSelectPpftzDokumen",
			idppftz: idppftz
		}
	}).done(function (e) {
		var c = $.parseJSON(e);
		var f = $.parseJSON(e).length;
		var b;
		for (b = 0; b < f; b++) {
			var d = document.createElement("option");
			d.value = c[b].kode;
			d.innerHTML = c[b].uraian;
			a.appendChild(d)
		}
	})
};
var loadDokumenPreferensi = function () {
	$("#txtDokumenPreferensiTarif").find("option:gt(0)").remove();
	var a = document.getElementById("txtDokumenPreferensiTarif");
	$.ajaxQueue({
		url: "ppftzref.html",
		type: "POST",
		data: {
			content: "listDokumenPreferensi",
			idppftz: idppftz
		}
	}).done(function (e) {
		var c = $.parseJSON(e);
		var f = $.parseJSON(e).length;
		var b;
		for (b = 0; b < f; b++) {
			var d = document.createElement("option");
			d.value = c[b].kode;
			d.innerHTML = c[b].uraian;
			a.appendChild(d)
		}
	})
};
var loadPpftzKemasan = function () {
	$("#divPpftzKemasan").html('<h1><i class="fa fa-cog fa-spin"></i> Loading...</h1>');
	$.ajaxQueue({
		url: "ppftz-kemasan.html",
		type: "POST",
		data: {
			content: "list",
			idppftz: idppftz
		}
	}).done(function (a) {
		$("#divPpftzKemasan").html(a)
	})
};
var loadPpftzKontainer = function () {
	$("#divPpftzKontainer").html('<h1><i class="fa fa-cog fa-spin"></i> Loading...</h1>');
	$.ajaxQueue({
		url: "ppftz-kontainer.html",
		type: "POST",
		data: {
			content: "list",
			idppftz: idppftz
		}
	}).done(function (a) {
		$("#divPpftzKontainer").html(a)
	})
};
var loadPpftzBankDevisaEkspor = function () {
	$("#divPpftzBankDevisaEkspor").html('<h1><i class="fa fa-cog fa-spin"></i> Loading...</h1>');
	$.ajaxQueue({
		url: "ppftz-bank-devisa-ekspor.html",
		type: "POST",
		data: {
			content: "list",
			idppftz: idppftz
		}
	}).done(function (a) {
		$("#divPpftzBankDevisaEkspor").html(a)
	})
};
var idbrguntukopenhide;
var loadPpftzBarang = function (a) {
	$("#divPpftzBarang").html('<h1><i class="fa fa-cog fa-spin"></i> Loading...</h1>');
	$.ajaxQueue({
		url: "ppftz-barang.html",
		type: "POST",
		data: {
			content: "listbarangjson",
			txtIdPpftz: idppftz,
			txtPage: a
		}
	}).done(function (j) {
		var n = $.parseJSON(j);
		if (n.error === null) {
			var e = n.list.length;
			var k;
			var g = "";
			for (k = 0; k < e; k++) {
				var l = n.list[k];
				var m = k + 1 + ((n.page - 1) * 10);
				idbrguntukopenhide = l.idPpftzBarang;
				g += "<tr><td>" + m + "</td><td>" + l.posTarif + "</td><td>" + l.UrBarang + "</td><td>" + l.Netto + "</td><td>" + l.JumlahSatuan + "</td><td>" + l.NilaiPabean + '</td><td><div class="btn btn-sm btn-default" title="Buka Row Konversi" onclick="openHideKonversi(' + idbrguntukopenhide + ')" id="btnopenhideknv' + l.idPpftzBarang + '"><i class="fa fa-plus"></i></div> <div class="btn btn-sm btn-primary" title="Edit Barang" onclick="geteditbarang(' + idbrguntukopenhide + ')" id="btneditbrg' + l.idPpftzBarang + '"><i class="fa fa-edit"></i></div> <div class="btn btn-sm btn-danger" title="Hapus Barang" onclick="deleteBarang(' + idbrguntukopenhide + ')" id="btndeleteBarang' + l.idPpftzBarang + '"><i class="fa fa-trash"></i></div></td></tr><tr id="TrKonversiBrg' + l.idPpftzBarang + '" class="hidden"><td colspan="7" ><div id="TdKonversiBrg' + l.idPpftzBarang + '"></div><div ><div class="btn btn-sm btn-success pull-right" onclick="tambahKonversi(' + idbrguntukopenhide + ')"><i class="fa fa-plus"></i> Tambah Konversi</div></div></td></tr>'
			}
			var b = "";
			var d = "";
			var h = "";
			var c = "";
			if (n.page === 1) {
				b = '<button class="btn btn-sm btn-default btn-paging" title="First Page" type="button" disabled> <i class="fa fa-fast-backward"></i> </button>';
				d = '<button class="btn btn-sm btn-default btn-paging" title="First Page" type="button" disabled> <i class="fa fa-backward"></i> </button>'
			} else {
				b = '<button class="btn btn-sm btn-default btn-paging" title="First Page" type="button" onclick="loadPpftzBarang(1)"> <i class="fa fa-fast-backward"></i> </button>';
				d = '<button class="btn btn-sm btn-default btn-paging" title="First Page" type="button" onclick="loadPpftzBarang(' + (json.page - 1) + ')"> <i class="fa fa-backward"></i> </button>'
			}
			if (n.page === n.totalPage) {
				h = '<button class="btn btn-sm btn-default btn-paging" title="First Page" type="button" disabled> <i class="fa fa-forward"></i> </button>';
				c = '<button class="btn btn-sm btn-default btn-paging" title="First Page" type="button" disabled> <i class="fa fa-fast-forward"></i> </button>'
			} else {
				h = '<button class="btn btn-sm btn-default btn-paging" title="First Page" type="button" onclick="loadPpftzBarang(' + (json.page + 1) + ')"> <i class="fa fa-forward"></i> </button>';
				c = '<button class="btn btn-sm btn-default btn-paging" title="First Page" type="button" onclick="loadPpftzBarang(' + json.totalPage + ')"> <i class="fa fa-fast-forward"></i> </button>'
			}
			var f = '<div class="row"><div class="col col-12 col-lg-12 col-md-12 col-sm-12 col-xs-12"><div ><table class="table table-responsive table-bordered table-hover"><thead><tr><th>No</th><th>Pos Tarif</th><th>Uraian</th><th>Netto, Bruto, Volume</th><th>Satuan, Kemasan</th><th>Nilai</th><th>Aksi</th></tr></thead><tbody>' + g + '</tbody></table></div></div></div><div class="row">&nbsp;</div><div class="row"><div class="col col-4 col-xs-4 col-sm-4 col-md-4 col-lg-4"><code>' + n.totalPage + "</code> page / <code>" + n.totalRow + '</code> row</div><div class="col col-8 col-xs-8 col-sm-8 col-md-8 col-lg-8"><div class="btn-group pull-right">' + b + d + '<button class="btn btn-sm btn-primary btn-page " type="button" >1</button>' + h + c + "</div></div></div>";
			$("#divPpftzBarang").html(f)
		} else {
			$("#divPpftzBarang").html("<h3>" + n.error + "</h3>")
		}
	})
};
var forIdBtnKnv = "";
var loadPpftzKonversi = function (a) {
	$("#TdKonversiBrg" + a).html('<h1><i class="fa fa-cog fa-spin"></i> Loading...</h1>');
	$.ajaxQueue({
		url: "ppftz-konversi.html",
		type: "POST",
		data: {
			content: "listbarangkonversijson",
			txtIdPpftzBarang: a
		}
	}).done(function (f) {
		var e = $.parseJSON(f);
		if (e.error === null) {
			var c = "";
			var j = e.list.length;
			var d;
			for (d = 0; d < j; d++) {
				var g = e.list[d];
				var h = d + 1;
				forIdBtnKnv = g.idPpftzKonversi;
				c += '<tr id="rowKonversi' + forIdBtnKnv + '"><td>' + h + "</td><td>" + g.noPpftzMasuk + " <br>" + g.tglPpftzMasuk + "</td><td>" + g.noSka + " <br>" + g.tglSka + "</td><td>" + g.posTarif + " <br>" + g.urBarang + ", " + g.kodeBarang + " <br></td><td>" + g.jumlahSatuan + "</td><td>" + g.nilaiPersatuan + '</td><td><div class="btn btn-primary btn-sm" id="btneditKonversi' + forIdBtnKnv + '" onclick="geteditKonversi(' + forIdBtnKnv + ')"><i class="fa fa-edit"></i></div> <div class="btn btn-danger btn-sm" id="btndeleteKonversi' + forIdBtnKnv + '" onclick="deleteKonversi(' + forIdBtnKnv + ')"><i class="fa fa-trash"></i></div></td><tr>'
			}
			var b = '<table class="table table-responsive table-bordered table-hover"><thead><tr><th>No</th><th>PPFTZ Pemasukan</th><th>SKA</th><th>Barang</th><th>Satuan</th><th>Nilai</th><th>Aksi</th></tr></thead><tbody>' + c + "</tbody></table>";
			$("#TdKonversiBrg" + a).html(b)
		} else {
			$("#TdKonversiBrg" + a).html("<h3>" + e.error + "</h3>")
		}
	})
};
var clearFormDokumen = function () {
	$("#txtKdDokumen").val("");
	$("#txtKdDokumen", window.parent.document).select2({
		width: "100%"
	});
	$("#txtNoDokumen").val("");
	$("#txtTglDokumen").val("");
	$("#txtUrlDokumen").val("");
	$("#txtKdFasilitas").val("");
	$("#txtKdFasilitas", window.parent.document).select2({
		width: "100%"
	});
	$("#txtKdSkemaTarif").val("");
	$("#txtKdSkemaTarif", window.parent.document).select2({
		width: "100%"
	});
	$("#btnSaveDokumen").addClass("hidden").html('<i class="fa fa-save"></i> Save').removeAttr("disabled");
	$("#ppftz-dokumen-form").removeAttr("enctype");
	$("#ppftz-dokumen-form label").removeClass("state-success");
	$("#txtUrlFile").val("");
	$("#fieldButtonUpload").html('<div class="btn btn-default btn-block btn-sm pointer" id="tombolPilihFile" onclick="klickFieldUrl()"><i class="fa fa-folder-open-o"></i> Pilih File</div>');
	$("#fieldButtonCancel").html('<div class="btn btn-primary btn-block btn-sm pointer hidden" id="tombolUploadAtas" onclick="klickButtonSubmit()"><i class="fa fa-upload"></i> Upload</div>').addClass("hidden")
};
var clearFormKemasan = function () {
	$("#txtJumlahKemasanKms").val("");
	$("#txtKdKemasanKms").val("");
	$("#txtKdKemasanKms", window.parent.document).select2({
		width: "100%"
	});
	$("#txtMerkKemasan").val("");
	$("#btnSaveKemasan").addClass("hidden");
	$("#ppftz-kemasan-form label").removeClass("state-success")
};
var clearFormKontainer = function () {
	$("#txtNoKontainer").val("");
	$("#txtKdTipeKontainer").val("");
	$("#txtKdUkuranKontainer").val("");
	$("#btnSaveKontainer").addClass("hidden");
	$("#ppftz-kontainer-form label").removeClass("state-success")
};
var clearFormBankDHE = function () {
	$("#txtKdBankDevisaEkspor").val("");
	$("#txtKdBankDevisaEkspor", window.parent.document).select2({
		width: "100%"
	});
	$("#ppftz-bank-devisa-ekspor-form label").removeClass("state-success")
};
var clearFormBarang = function () {
	$("#txtIdPpftzBarang,#txtBarangPosTarif,#txtBarangUrBarang,#txtBarangMerek,#txtBarangTipe,#txtBarangUkuran,#txtBarangSpesifikasiLain,#txtBarangKodeBarang,#txtBarangNetto,#txtBarangBruto,#txtBarangVolume,#txtBarangJumlahSatuan,#txtBarangKdSatuan,#txtBarangKdKemasan,#txtBarangJumlahKemasan,#txtBarangHeBarang,#txtBarangNilaiPabean,#txtBarangKdNegaraAsalBarang,#txtBarangKdDaerahAsal,#txtBeaMasukTarif,#txtBeaMasukPerSatuan,#txtBeaMasukFasTrf,#txtBeaMasukTarifFasilitas,#txtBeaMasukKdSatuan,#txtBeaKeluarPerhit,#txtBeaKeluarTarif,#txtBeaKeluarPerSatuan,#txtBeaKeluarFasTrf,#txtBeaKeluarTarifFasilitas,#txtBeaKeluarKdSatuan,#txtCukaiPerhit,#txtCukaiTarif,#txtCukaiPerSatuan,#txtCukaiFasTrf,#txtCukaiTarifFasilitas,#txtCukaiKdSatuan,#txtPPNTarif,#txtPPNFasTrf,#txtPPNTarifFasilitas,#txtPPnBMTarif,#txtPPnBMFasTrf,#txtPPnBMTarifFasilitas,#txtPPhTarif,#txtPPhFasTrf,#txtPPhTarifFasilitas").val("");
	var b = $('input[name="txtKdFasBarangDokValue"]').length;
	for (var a = 0; a < b - 1; a++) {
		removeDynamicBrgDoc("sectionDynamicBrg" + a)
	}
	var c = $('input[name="txtBarangNilaiTransaksi"]').length;
	for (var d = 0; d < c - 1; d++) {
		removeDynamicBarangTransaksi("sectionDynamicBrgTransaksi" + d)
	}
	countbrgdok = 0;
	countknvdok = 0;
	countbrgtrx = 0;
	$("#ppftz-barang-form option").removeAttr("selected");
	$("#txtKdTps,#txtBarangKdNegaraAsalBarang,#txtBarangKdSatuan,#txtBarangKdKemasan", window.parent.document).select2({
		width: "100%"
	});
	$("#ppftz-barang-form label").removeClass("state-success");
	$('#ppftz-barang-form input[name="content"]').val("rekam")
};
var clearFormKonversi = function () {
	$("#txtKdAsalBarangKnv,#txtNoPpftzMasukKnv,#txtTglPpftzMasukKnv,#txtPosTarifKnv,#txtUrBarangKnv,#txtKodeBarangKnv,#txtNoSkaKnv,#txtTglSkaKnv,#txtKdValutaKnv,#txtNdpbmKnv,#txtNmPenerbitSkaKnv,#txtJumlahSatuanKnv,#txtKdSatuanKnv,#txtNilaiPersatuanKnv,#txtNilaiPabeanKnv,#txtNilaiAsalLdpKnv,#txtKeteranganKnv,#txtBeaMasukPerhitKnv,#txtBeaMasukTarifKnv,#txtBeaMasukFasTrfKnv,#txtBeaMasukTarifFasilitasKnv,#txtBeaMasukJumlahKnv,#txtBeaMasukKdSatuanKnv,#txtCukaiKomoditiKnv,#txtCukaiSubKomoditiKnv,#txtCukaiPerhitKnv,#txtCukaiTarifKnv,#txtCukaiJumlahSatuanKnv,#txtCukaiKdSatuanKnv,#txtCukaiFasTrfKnv,#txtCukaiTarifFasilitasKnv,#txtPPNTarifKnv,#txtPPNFasTrfKnv,#txtPPNTarifFasilitasKnv,#txtPPnBMTarifKnv,#txtPPnBMFasTrfKnv,#txtPPnBMTarifFasilitasKnv,#txtPPhTarifKnv,#txtPPhFasTrfKnv,#txtPPhTarifFasilitasKnv").val("");
	$("#txtKdSatuanKnv,#txtKdValutaKnv", window.parent.document).select2({
		width: "100%"
	});
	$("#ppftz-konversi-form label").removeClass("state-success").removeClass("state-error");
	$('#ppftz-konversi-form input[name="content"]').val("rekam")
};
var txtKdPelabuhanMuat = "";
var txtKdPelabuhanTujuan = "";
var txtKdPelabuhanTransit = "";
if (txtKdPelabuhanMuat !== "") {
	loadUraianPelabuhan("txtKdPelabuhanMuat", "txtUraianPelabuhanMuat", txtKdPelabuhanMuat)
}
if (txtKdPelabuhanTujuan !== "") {
	loadUraianPelabuhan("txtKdPelabuhanTujuan", "txtUraianPelabuhanTujuan", txtKdPelabuhanTujuan)
}
if (txtKdPelabuhanTransit !== "") {
	loadUraianPelabuhan("txtKdPelabuhanTransit", "txtUraianPelabuhanTransit", txtKdPelabuhanTransit)
}
var txtNoIdentitasPenerima = "98765432178";
var txtKdKantorAsal = "";
var txtKdKantorTujuan = "";
ppftz = "PPFTZ02_1";
if (txtKdKantorAsal !== "") {
	if (valmasuk === "2") {
		loadlistRevParPpftz("txtKdKantorTujuan", "listKantorFtz", "", "y", ppftz)
	} else {
		loadlistRevParPpftz("txtKdKantorTujuan", "listKantor", "", "y", ppftz)
	}
}
if (txtKdKantorTujuan !== "") {
	loadlistTPS(txtKdKantorTujuan)
}
if (valmasuk === "2") {
	loadlistRevParPpftz("txtKdKantorAsal", "listKantorFtz", "", "y", ppftz);
	$("#textHeader").text("PPFTZ 02 Pemasukan dari Kawasan Bebas")
} else {
	loadlistRevParPpftz("txtKdKantorAsal", "listKantor", "", "y", ppftz)
}
if (valmasuk === "3") {
	$("#textHeader").text("PPFTZ 02 Pemasukan dari Tempat Penimbunan Berikat")
} else {
	if (valmasuk === "4") {
		$("#textHeader").text("PPFTZ 02 Pemasukan dari Kawasan Ekonomi Khusus")
	} else {
		if (valmasuk === "5") {
			$("#textHeader").text("PPFTZ 02 Pemasukan dari Kawasan Yang Mendapat Fasilitas Lainnya")
		}
	}
}
loadlistReverensiPure("txtBeaMasukKdSatuan", "listSatuan", "", "y");
loadlistReverensiPure("txtKdKategoriPemberitahuan", "listKategoriPemberitahuan", "", "");
loadlistRevParPpftz("txtKdKategoriMasuk", "listKategoriMasuk", "", "", ppftz);
loadlistRevParPpftz("txtKdTujuanMasuk", "listTujuanMasuk", "", "", ppftz);
loadlistRevParPpftz("txtKdAsalBarang", "listAsalBarang", "", "", ppftz);
loadlistRevParPpftz("txtKdKategoriBarang", "listKategoriBarang", "", "", ppftz);
loadlistReverensiPure("txtKdValuta", "listValuta", "", "y");
loadlistReverensiPure("txtKdValutaKnv", "listValuta", "", "y");
loadlistReverensiPure("txtKdJenisIdentitasPengirim", "listJenisIdentitas", "", "");
loadlistReverensiPure("txtKdJenisIdentitasPenerima", "listJenisIdentitas", "5", "");
loadlistReverensiPure("txtKdJenisIdentitasPembeli", "listJenisIdentitas", "", "");
loadlistReverensiPure("txtKdJenisIdentitasPenjual", "listJenisIdentitas", "", "");
loadlistReverensiPure("txtKdCaraAngkut", "listCaraAngkut", "", "y");
loadlistReverensiPure("txtKdBendera", "listNegara", "", "y");
loadPpftzDokumen();
loadlistReverensiPure("txtKdDokumen", "listDokumen", "", "y");
loadlistReverensiPure("txtKdSkemaTarif", "listSkemaTarif", "", "");
loadPpftzKemasan();
loadlistReverensiPure("txtKdKemasanKms", "listJenisKemasan", "", "y");
loadPpftzKontainer();
loadlistReverensiPure("txtKdUkuranKontainer", "listUkuranKontainer", "", "");
loadlistReverensiPure("txtKdTipeKontainer", "listTipeKontainer", "", "");
loadPpftzBarang(1);
loadlistReverensiPure("txtBarangKdSatuan", "listSatuan", "", "y");
loadlistReverensiPure("txtKdSatuanKnv", "listSatuan", "", "y");
loadlistReverensiPure("txtCukaiKdSatuan", "listSatuan", "", "y");
loadlistReverensiPure("txtCukaiKdSatuanKnv", "listSatuan", "", "y");
loadlistReverensiPure("txtBeaMasukKdSatuanKnv", "listSatuan", "", "y");
loadlistReverensiPure("txtBarangKdKemasan", "listJenisKemasan", "", "y");
loadlistReverensiPure("txtBarangKdNegaraAsalBarang", "listNegara", "", "y");
loadlistReverensiPure("txtCukaiKomoditi", "listKomoditiCukai", "", "");
loadSelectPpftzDokumen();
loadvalidator();
loadScript("js/plugin/jquery-form/jquery-form.min.js", pagefunction);
