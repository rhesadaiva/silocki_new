<div class="main">
    <style>
        tbody>tr {
            text-align: center;
        }
    </style>
    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Tabel -->
            <div class="main-content approved">
                <div class="panel panel-headline panel-primary" id="approved-content">
                    <div class="panel-heading panel-title">
                        <div class="col-sm">
                            <h3 style="margin-top: -10px; margin-bottom: -10px;"><?= $title; ?></h3>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-hover table-bordered" id="configTable">
                            <thead>
                                <tr class="success">
                                    <th scope="col" class="text-center">config_desc</th>
                                    <th scope="col" class="text-center">config_params</th>
                                    <th scope="col" class="text-center">config_edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($config as $con) : ?>
                                    <td><?= $con['config_desc'] ?></td>
                                    <td><?= $con['config_params'] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm btnConfigData" data-toggle="modal" cnfg="<?= $con['config_id']; ?>" data-target="#configModal">Detail</button>
                                    </td>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END MAIN CONTENT -->

    <!-- Modal -->
    <div class="modal fade" id="configModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="" method="POST" id="configForm">
                        <input type="hidden" name="configId" class="configId">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Desc</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control configDesc" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Parameter</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control configParams" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Value</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control configValue">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Is_active?</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control configActive">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary saveConfig">Simpan Konfigurasi</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

</div>