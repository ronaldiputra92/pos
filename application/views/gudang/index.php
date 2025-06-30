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
            <div class="col-md-12 col-sm-12 col-xs-12">
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
                        <?php echo $this->session->flashdata('message'); ?>
                        <table width="100%" class="table table-striped table-bordered datatable">
                            <thead>
                                <tr>
                                    <th>Gambar</th>
                                    <th>Barcode</th>
                                    <th>Nama Item</th>
                                    <th>Satuan</th>
                                    <th>Stok</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($item as $i) { ?>
                                    <tr>
                                        <td>
                                            <div class="text-center">
                                                <img src="<?php echo base_url('assets/img/produk/') . $i['gambar'] ?>" class=" mx-auto d-block" width="80px" height="80px" alt="...">
                                            </div>
                                        </td>
                                        <td><?php echo $i['barcode'] ?></td>
                                        <td><?php echo $i['nama_barang'] ?></td>
                                        <td><?php echo $i['satuan'] ?></td>
                                        <td><?php echo $i['stok'] ?></td>
                                        <td>
                                            <a href="<?php echo base_url('gudang/masuk/') . encrypt_url($i['id_gudang']) ?>" class="btn btn-primary btn-xs" title="Transfer Masuk"><i class="fa fa-sign-in"></i></a>
                                            <a href="<?php echo base_url('gudang/keluar/') . encrypt_url($i['id_gudang']) ?>" class="btn btn-danger btn-xs" title="Transfer Keluar"><i class="fa fa-sign-out"></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>