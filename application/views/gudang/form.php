<?php cek_user() ?>
<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3><?php echo $title ?></h3>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-6 col-sm-12 col-xs-12">
                <?php echo $this->session->flashdata('message'); ?>
                <div class="x_panel">
                    <div class="x_title">
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form class="form-horizontal" method="post" action="<?php echo base_url($action) ?>">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Barcode</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" class="form-control" name="barcode" id="barcode" readonly value="<?= $item['barcode'] ?>">
                                    <input type="hidden" class="form-control" name="id_barang" id="id_barang" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Barang</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" class="form-control" name="namabarang" id="namabarang" readonly value="<?= $item['nama_barang'] ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Stok Gudang</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" class="form-control" name="stok_gudang" id="stok_gudang" readonly value="<?= $item['stok_gudang'] ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Stok Toko</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" class="form-control" name="stok_toko" id="stok_toko" readonly value="<?= $item['stok_toko'] ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Jumlah</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="number" class="form-control" name="stok" id="stok" required autocomplete="off">
                                    <br>
                                    <?php if ($type == 'in') : ?>
                                        <p class=""> <i><b>Note : </b> Transfer masuk merupakan perpindahan stok dari toko ke gudang.</i></p>
                                    <?php else : ?>
                                        <p class=""> <i><b>Note : </b> Transfer keluar merupakan perpindahan stok dari gudang ke toko.</i></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div style="text-align: right">
                                <a href="<?= base_url('gudang') ?>" class="btn btn-danger"><i class="fa fa-recycle m-right-xs"></i> Batal</a>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane-o m-right-xs"></i> Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>