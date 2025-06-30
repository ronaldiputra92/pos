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
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="row">
              <div class="col-md-9 col-sm-12 col-xs-12">
                <h1>Saldo</h1>
              </div>
              <div class="col-md-3 col-sm-12 col-xs-12">
                <h1 style="text-align: right" id="totalkas">Rp. <?php echo number_format($total, '0', '.', '.') . ',-' ?></h1>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <button type="button" class="btn btn-sm btn-primary" onclick="tambahBank()" title="Tambah Data Bank"><i class="fa fa-plus"></i> Tambah Data Bank</button>
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
                  <th>No.</th>
                  <th>Waktu</th>
                  <th>Jenis</th>
                  <th>Nominal (Rp)</th>
                  <th>Keterangan</th>
                  <th>User</th>
                  <th>Opsi</th>
                </tr>
              </thead>
              <tbody>
                <?php $i = 1 ?>
                <?php foreach ($bank as $val) { ?>
                  <tr>
                    <td><?php echo $i++ ?></td>
                    <td><?php echo $val['tanggal'] ?></td>
                    <td><?php echo $val['jenis'] ?></td>
                    <td><?php echo $val['nominal'] ?></td>
                    <td><?php echo $val['keterangan'] ?></td>
                    <td><?php echo $val['nama_lengkap'] ?></td>
                    <td>
                      <a href="#" class="btn btn-danger btn-xs" title="Hapus Data" onclick="hapusBank('<?php echo $val['id_bank'] ?>')"><i class="fa fa-trash "></i></a>
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
<?php include 'edit.php' ?>
<?php include 'add.php' ?>
<?php include 'Js.php' ?>
<!-- <script src="<?php //echo base_url('assets/Javascript/mod-kas.js')
                  ?>"></script> -->