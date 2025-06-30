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
            <div class="col-md-5 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Prestasi Karyawan Hari Ini</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Settings 1</a>
                                    </li>
                                    <li><a href="#">Settings 2</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <ul class="list-unstyled top_profiles scroll-view">
                        <?php if ($prestasi->num_rows() < 1) { ?>
                            <img src="<?php echo base_url('assets/img/illustrations/undraw_printing_invoices_5r4r.svg') ?>" width="360" alt="">
                        <?php } else { ?>
                            <?php foreach ($prestasi->result_array() as $val) { ?>
                                <li class="media event">
                                    <a class="pull-left border-green profile_thumb">
                                        <i class="fa fa-user green"></i>
                                    </a>
                                    <div class="media-body">
                                        <a class="title" href="#"><?php echo $val['nama_karyawan'] ?></a>
                                        <p><strong>Rp. <?php echo number_format($val['total'], '0', '.', '.') ?>. </strong> Perolehan Komisi Hari Ini </p>
                                        <?php foreach ($banyaknya as $banyak) { ?>
                                            <?php if ($val['id_karyawan'] == $banyak['id_karyawan']) { ?>
                                                <p> <small><?php echo $banyak['banyaknya'] ?> Servis Hari Ini</small> </p>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </li>
                            <?php } ?>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-7 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Export Data</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <form class="form-horizontal" method="post" action="<?php echo base_url('report/prestasi') ?>">
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label for="">Tanggal Awal :</label>
                                    <input type="date" id="awal" class="form-control datepicker" name="awal" required />
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <label for="">Tanggal Akhir :</label>
                                    <input type="date" id="akhir" class="form-control datepicker" name="akhir" required />
                                </div>
                            </div>
                            <p style="margin-left: 9px"><i><b>Perhatian:</b> Fitur ini berfungsi untuk mengetahui prestasi karyawan dan perolehan komisi karyawan per periode tanggal yang anda tentukan.</i> </p>
                            <div class="form-group">
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    <button type="submit" class="btn btn-sm btn-primary btn-block"><i class="fa fa-file-pdf-o"></i> Export PDF</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>