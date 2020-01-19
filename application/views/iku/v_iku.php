<div class="main">
    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Tabel -->
            <div class="panel panel-headline panel-primary">
                <div class="panel-heading panel-title">
                    <div class="col-sm">
                        <h3 style="margin-top: -10px; margin-bottom: -10px;"><?= $title; ?></h3>
                        <a class="btn btn-success btn-sm pull-right" style="margin-top: -20px;" data-toggle="modal" data-target="#newIKUModal"><i class="fas fa-fw fa-folder-plus"></i> Tambah Indikator Kinerja Utama</a>
                    </div>
                    <div class="col-sm">
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover table-bordered" id="ikuTable">
                        <thead>
                            <tr class="success">
                                <th scope="col">#</th>
                                <th scope="col" class="text-center">Nomor Kontrak Kinerja</th>
                                <?php if ($user['role_id'] == 1) : ?>
                                    <th scope="col" class="text-center">Pemilik Kontrak Kinerja</th>
                                <?php endif; ?>
                                <th scope="col" class="text-center">Nomor IKU</th>
                                <th scope="col" class="text-center">Nama IKU</th>
                                <th scope="col" class="text-center">Target IKU</th>
                                <th scope="col" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="usersData">
                            <?php $i = 1; ?>
                            <?php foreach ($listIKU as $iku) : ?>
                                <tr>
                                    <th scope="row"><?= $i; ?></th>
                                    <td style="width: 20px;"><?= $iku['nomorkk'] ?></td>
                                    <?php if ($user['role_id'] == 1) : ?>
                                        <td><?= $iku['nama'] ?></td>
                                    <?php endif; ?>
                                    <td><?= $iku['kodeiku'] ?></td>
                                    <td><?= $iku['namaiku'] ?></td>
                                    <td><?= $iku['targetiku'] ?> dari <?= $iku['nilaitertinggi']; ?></td>
                                    <td style="width: 10%">
                                        <?php if ($iku['iku_validated'] == 0) : ?>
                                            <button class="btn btn-primary btn-xs btnEditIKU" name="btnEditIKU" id="btnEditIKU" data-toggle="modal" data-target="#editIKUModal" iku-id="<?= $iku['id_iku'] ?>"><i class="fas fa-fw fa-edit"></i> Edit IKU</button>
                                            <button class="btn btn-danger btn-xs btnDeleteIKU" data-toggle="modal" data-target="#deleteIKUModal" iku-id="<?= $iku['id_iku'] ?>" name="btnDeleteIKU"><i class=" fas fa-fw fa-trash"></i> Hapus IKU</button>
                                        <?php else : ?>
                                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createLogbookModal" iku-id="<?= $iku['id_iku'] ?>" name="btnCreateLogbook"><i class="fas fa-fw fa-chart-line"></i> Logbook </button>
                                            <button class="btn btn-primary btnAddendumIKU" name="btnAddendumIKU" id="btnAddendumIKU" data-toggle="modal" data-target="#editIKUModal" iku-id="<?= $iku['id_iku'] ?>"><i class="fas fa-fw fa-edit"></i> Addendum IKU</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- MODAL ADD IKU -->
        <div class="modal fade" id="newIKUModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-primary" id="myModalLabel"><b>Rekam Indikator Kinerja Utama</b></h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" action="" id="newIKUForm" method="POST">
                            <div class="form-group">
                                <?php if ($user['role_id'] == 1) : ?>
                                    <label class="control-label col-sm-3" for="setIKUPegawai">Set Pegawai</label>
                                    <div class="col-sm-8">
                                        <select class="selectpicker" name="setIKUPegawai" data-live-search="true" id="setIKUPegawai">
                                            <?php foreach ($userList as $userList) : ?>
                                                <option value="<?= $userList['nip'] ?>"><?= $userList['nama'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php else : ?>
                                    <div class="col-sm-8">
                                        <input type="hidden" class="form-control" id="setIKUPegawai" name="setIKUPegawai" value="<? $user['nip']; ?>">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="setKontrakPegawai">Seri Kontrak Kinerja</label>
                                <div class="col-sm-8">
                                    <select class="selectpicker" name="setKontrakPegawai" data-live-search="true" data-width="80%" id="setKontrakPegawai">
                                        <?php if ($user['role_id'] == 1) : ?>
                                            <?php foreach ($kontrakKinerjaAdmin as $kontrakKinerja) : ?>
                                                <option value="<?= $kontrakKinerja['id_kontrak'] ?>"><?= $kontrakKinerja['nomorkk']; ?></option>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <?php if ($getKontrak >= 2) : ?>
                                                <?php foreach ($kontrakKinerja as $kontrakKinerja) : ?>
                                                    <option value="<?= $kontrakKinerja['id_kontrak'] ?>"><?= $kontrakKinerja['nomorkk']; ?></option>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <option value="<?= $kontrakKinerja['id_kontrak'] ?>"><?= $kontrakKinerja['nomorkk']; ?></option>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="kodeIKU">Kode IKU</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="kodeIKU" name="kodeIKU" placeholder="Kode IKU">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="namaIKU">Nama IKU</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" id="namaIKU" name="namaIKU" placeholder="Nama IKU"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="formulaIKU">Formula IKU</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" id="formulaIKU" name="formulaIKU" placeholder="Formula IKU"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="targetIKU">Target IKU</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="targetIKU" name="targetIKU" placeholder="Target IKU">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="nilaiTertinggiIKU">Nilai Tertinggi IKU</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="nilaiTertinggiIKU" name="nilaiTertinggiIKU" placeholder="Nilai Tertinggi IKU">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="satuanPengukuranIKU">Satuan Pengukuran IKU</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="satuanPengukuranIKU" name="satuanPengukuranIKU" placeholder="Satuan Pengukuran IKU">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="aspekTargetIKU">Aspek Target</label>
                                <div class="col-sm-8">
                                    <select class="selectpicker" name="aspekTargetIKU" data-live-search="true" data-width="fit" id="aspekTargetIKU">
                                        <?php foreach ($refAspekTarget as $aspekTarget) : ?>
                                            <option value="<?= $aspekTarget['AspekTarget_ket'] ?>"><?= $aspekTarget['AspekTarget_ket'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="penanggungJawabIKU">Unit/Pihak Penanggung Jawab IKU
                                </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="penanggungJawabIKU" name="penanggungJawabIKU" placeholder="Penanggung Jawab IKU">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="penyediaDataIKU">Unit/Pihak Penyedia Data IKU
                                </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="penyediaDataIKU" name="penyediaDataIKU" placeholder="Penyedia Data IKU">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="sumberDataIKU">Sumber Data
                                </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="sumberDataIKU" name="sumberDataIKU" placeholder="Sumber Data">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="konsolidasiPeriodeIKU">Konsolidasi Periode IKU</label>
                                <div class="col-sm-8">
                                    <select class="selectpicker" name="konsolidasiPeriodeIKU" data-live-search="true" data-width="fit" id="konsolidasiPeriodeIKU">
                                        <?php foreach ($refKonsolidasiPeriode as $konsolidasiPeriode) : ?>
                                            <option value="<?= $konsolidasiPeriode['KonsolidasiPeriode_ket'] ?>"><?= $konsolidasiPeriode['KonsolidasiPeriode_ket'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="periodePelaporanIKU">Periode Pelaporan IKU</label>
                                <div class="col-sm-8">
                                    <select class="selectpicker" name="periodePelaporanIKU" data-live-search="true" data-width="fit" id="periodePelaporanIKU">
                                        <?php foreach ($refPeriodePelaporan as $periodePelaporan) : ?>
                                            <option value="<?= $periodePelaporan['PeriodePelaporan_ket'] ?>"><?= $periodePelaporan['PeriodePelaporan_ket'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="konversi120IKU">Konversi 120?</label>
                                <div class="col-sm-8">
                                    <select class="selectpicker" name="konversi120IKU" data-live-search="true" data-width="fit" id="konversi120IKU">
                                        <?php foreach ($refKonversi120 as $konversi120) : ?>
                                            <option value="<?= $konversi120['Konversi_ket'] ?>"><?= $konversi120['Konversi_ket'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class=" modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                <button type="button" class="btn btn-success hidden" id="btnNewIKU"><i class="fa fa-save"></i> Simpan IKU</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL CONFIRM DELETE IKU -->
        <div class="modal fade" id="deleteIKUModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title text-primary" id="exampleModalLongTitle"><b>Konfirmasi hapus kontrak kinerja</b></h3>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin menghapus kontrak kinerja ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                        <button class="btn btn-danger btnConfirmDeleteIKU"><i class="fas fa-fw fa-trash"></i> Hapus IKU</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL EDIT IKU -->
        <div class="modal fade" id="editIKUModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-primary" id="myModalLabel"><b>Ubah Data IKU</b></h4>
                        <input type="hidden" readonly name="" id="idIKU">
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" action="" id="editIKUForm" method="POST">
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editKodeIKU">Kode IKU</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="editKodeIKU" name="editKodeIKU" placeholder="Kode IKU">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editNamaIKU">Nama IKU</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" id="editNamaIKU" name="editNamaIKU" placeholder="Nama IKU"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editFormulaIKU">Formula IKU</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" id="editFormulaIKU" name="editFormulaIKU" placeholder="Formula IKU"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editTargetIKU">Target IKU</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="editTargetIKU" name="editTargetIKU" placeholder="Target IKU">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editNilaiTertinggiIKU">Nilai Tertinggi IKU</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="editNilaiTertinggiIKU" name="editNilaiTertinggiIKU" placeholder="Nilai Tertinggi IKU">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editSatuanPengukuranIKU">Satuan Pengukuran IKU</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="editSatuanPengukuranIKU" name="editSatuanPengukuranIKU" placeholder="Satuan Pengukuran IKU">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editAspekTargetIKU">Aspek Target</label>
                                <div class="col-sm-8">
                                    <select class="selectpicker" name="editAspekTargetIKU" data-live-search="true" data-width="fit" id="editAspekTargetIKU">
                                        <?php foreach ($refAspekTarget as $aspekTarget) : ?>
                                            <option value="<?= $aspekTarget['AspekTarget_ket'] ?>"><?= $aspekTarget['AspekTarget_ket'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editPenanggungJawabIKU">Unit/Pihak Penanggung Jawab IKU
                                </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="editPenanggungJawabIKU" name="editPenanggungJawabIKU" placeholder="Penanggung Jawab IKU">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editPenyediaDataIKU">Unit/Pihak Penyedia Data IKU
                                </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="editPenyediaDataIKU" name="editPenyediaDataIKU" placeholder="Penyedia Data IKU">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editSumberDataIKU">Sumber Data
                                </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="editSumberDataIKU" name="editSumberDataIKU" placeholder="Sumber Data">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editKonsolidasiPeriodeIKU">Konsolidasi Periode IKU</label>
                                <div class="col-sm-8">
                                    <select class="selectpicker" name="editKonsolidasiPeriodeIKU" data-live-search="true" data-width="fit" id="editKonsolidasiPeriodeIKU">
                                        <?php foreach ($refKonsolidasiPeriode as $konsolidasiPeriode) : ?>
                                            <option value="<?= $konsolidasiPeriode['KonsolidasiPeriode_ket'] ?>"><?= $konsolidasiPeriode['KonsolidasiPeriode_ket'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editPeriodePelaporanIKU">Periode Pelaporan IKU</label>
                                <div class="col-sm-8">
                                    <select class="selectpicker" name="editPeriodePelaporanIKU" data-live-search="true" data-width="fit" id="editPeriodePelaporanIKU">
                                        <?php foreach ($refPeriodePelaporan as $periodePelaporan) : ?>
                                            <option value="<?= $periodePelaporan['PeriodePelaporan_ket'] ?>"><?= $periodePelaporan['PeriodePelaporan_ket'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3" for="editKonversi120IKU">Konversi 120?</label>
                                <div class="col-sm-8">
                                    <select class="selectpicker" name="editKonversi120IKU" data-live-search="true" data-width="fit" id="editKonversi120IKU">
                                        <?php foreach ($refKonversi120 as $konversi120) : ?>
                                            <option value="<?= $konversi120['Konversi_ket'] ?>"><?= $konversi120['Konversi_ket'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class=" modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                <button type="button" class="btn btn-success btnConfirmEditIKU" id="btnConfirmEditIKU"><i class="fa fa-save"></i> Update IKU</button>
                                <button type="button" class="btn btn-success btnConfirmAddendumIKU hidden" id="btnConfirmAddendumIKU"><i class="fa fa-save"></i> Addendum IKU</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL LOGBOOK -->
        <div class="modal fade" id="createLogbookModal" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg" role="document" style="width: 1366px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-primary"><b>Logbook Pegawai</b></h4>
                    </div>
                    <div class="modal-body">
                        <div class="loadingAnimation hidden">
                            <h2><i class="fa fa-cog fa-spin"></i> Loading...</h2>
                        </div>
                        <div class="ikuLogbookContent">
                            <!-- PANEL MASTER IKU -->
                            <div class="panel panel-default panel-primary" id="panelIKU" style="margin-bottom: 20px;">
                                <div class="panel-heading">
                                    <h3 class="panel-title" style="margin-top: -10px; margin-bottom: -10px;"><b>Data Indikator Kinerja Utama</b></h3>
                                    <input type="hidden" id="idIKULogbook">
                                </div>
                                <div class="panel-body">
                                    <table class="table">
                                        <tbody class="masterIKU">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- PANEL FORM NEW LOGBOOK -->
                            <div class="formLogbook hidden">
                                <div class="panel panel-default panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title newLogbookTitle" style="margin-top: -10px; margin-bottom: -10px;"><b>Rekam Logbook</b></h3>
                                        <h3 class="panel-title hidden editLogbookTitle" style="margin-top: -10px; margin-bottom: -10px;"><b>Edit Logbook</b></h3>
                                    </div>
                                    <div class="panel-body">
                                        <form class="form-horizontal" action="" method="POST" id="newLogbookForm">
                                            <div class="form-group">
                                                <label for="periodePelaporanLogbook" class="col-md-3 control-label">Periode Pelaporan</label>
                                                <div class="col-md-8">
                                                    <select class="selectpicker" name="periodePelaporanLogbook" data-live-search="true" id="periodePelaporanLogbook">
                                                        <?php foreach ($refBulanLogbook as $bulanPelaporan) : ?>
                                                            <option value="<?= $bulanPelaporan['Bulan_ket'] ?>"><?= $bulanPelaporan['Bulan_ket'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="perhitunganLogbook" class="col-md-3 control-label">Perhitungan</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" id="perhitunganLogbook" rows="3" placeholder="Perhitungan Logbook"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="realisasiBulanPelaporanLogbook" class="col-md-3 control-label">Realisasi pada Bulan Pelaporan</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" id="realisasiBulanPelaporanLogbook" placeholder="Realisasi pada Bulan Pelaporan">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="realisasiTerakhirLogbook" class="col-md-3 control-label">Realisasi s.d Bulan Pelaporan</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" id="realisasiTerakhirLogbook" placeholder="Realisasi s.d Bulan Pelaporan">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="keteranganLogbook" class="col-md-3 control-label">Keterangan</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" id="keteranganLogbook" rows="3" placeholder="Keterangan Logbook"></textarea>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-default pull-right closeFormLogbook"><i class="fas fa-undo-alt"></i> Tutup Form</button>
                                            <button type="button" class="btn btn-info pull-right ml-2 saveNewLogbook"><i class="fas fa-fw fa-save"></i> Simpan Logbook</button>
                                            <button type="button" class="btn btn-info pull-right ml-2 btnConfirmEditLogbook hidden"><i class="fas fa-fw fa-save"></i> Simpan Perubahan Logbook</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- PANEL LOGBOOK -->
                            <div class="panel panel-default panel-success" id="panelLogbook">
                                <div class="panel-heading">
                                    <h3 class="panel-title" style="margin-top: -10px; margin-bottom: -10px;"><b>Data Logbook Pegawai</b></h3>
                                    <a type="button" class="btn btn-success btn-sm pull-right" id="btnOpenFormLogbook" style="margin-top: -15px;"><i class="fas fa-fw fa-chart-line"></i> Rekam Logbook</a>
                                </div>
                                <div class="panel-body">
                                    <!-- Logbook -->
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr class="success">
                                                <th scope="col" class="text-center">Periode Pelaporan</th>
                                                <th scope="col" class="text-center">Perhitungan</th>
                                                <th scope="col" class="text-center">Realisasi Periode Pelaporan</th>
                                                <th scope="col" class="text-center">Realisasi s.d Periode Pelaporan</th>
                                                <th scope="col" class="text-center">Keterangan</th>
                                                <th scope="col" class="text-center" style="width:10%;">Waktu Rekam</th>
                                                <th scope="col" class="text-center" style="width:10%;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="logbookData">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" id="closeIKUModal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END MAIN CONTENT -->
</div>