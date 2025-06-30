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
                        <button type="button" class="btn btn-sm btn-primary" onclick="tambahServis()" title="Tambah Data" id="tambahServis"><i class="fa fa-plus"></i> Tambah Data</button>
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
                        <table width="100%" class="table datatable table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama Servis</th>
                                    <th>Keterangan</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1 ?>
                                <?php foreach ($servis as $val) { ?>
                                    <tr>
                                        <td><?php echo $i++ ?></td>
                                        <td><?php echo $val['kode'] ?></td>
                                        <td><?php echo $val['nama_servis'] ?></td>
                                        <td><?php echo $val['keterangan'] ?></td>
                                        <td>Rp. <?php echo number_format($val['harga'], '0', '.', '.') ?></td>
                                        <td>
                                            <?php if ($val['status'] == "Aktif") { ?>
                                                <span class="label label-success"><?php echo $val['status'] ?></span>
                                            <?php } else { ?>
                                                <span class="label label-danger"><?php echo $val['status'] ?></span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <a href="#" onclick="editServis('<?php echo $val['id_servis'] ?>')" title="Edit Data" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>
                                            <a href="#" onclick="hapusServis('<?php echo $val['id_servis'] ?>')" title="Hapus Data" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
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
<?php include 'script.php' ?>
<?php include 'add.php' ?>
<?php include 'edit.php' ?>