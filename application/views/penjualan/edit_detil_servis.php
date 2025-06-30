<div class="modal fade" id="editDetilServisModal">
    <div class="modal-dialog bs-example-modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="editDetilServisModal">Edit Detail Penjualan</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" action="<?php echo base_url('penjualan/editdetiljual') ?>">
                    <div class="form-group">
                        <div class="col-md-12 col-sm-6 col-xs-12">
                            <input type="hidden" class="form-control" id="id_detil_jual" name="id_detil_jual">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" class="form-control" name="edit_kode_servis" id="edit_kode_servis" autocomplete="off" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Servis</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" class="form-control" name="nama_detil_servis" id="nama_detil_servis" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Harga Servis</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" class="form-control" name="harga_detil_servis" id="harga_detil_servis" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <!-- <label class="control-label col-md-3 col-sm-3 col-xs-12">Qty</label> -->
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="hidden" class="form-control" name="detil_qty" id="detil_qty" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Total (Rp)</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" class="form-control" name="detil_total_servis" id="detil_total_servis" readonly autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" data-dismiss="modal" onclick="editServisJual()" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>