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
		 						<?php include 'inputcs.php' ?>
		 						<button type="button" class="btn btn-sm btn-primary" onclick="tambahcustomer()" title="Tambah Data" id="tambahcustomer"><i class="fa fa-plus"></i> Tambah Data</button>
		 						<!-- <a href="<?php //echo base_url('customer/poin') 
												?>" class="btn btn-sm btn-primary" title="Customer Poin" id="customerPoin"><i class="fa fa-heart"></i> Customer Poin</a> -->
		 						<a href="<?php echo base_url('report/customer') ?>" class="btn btn-sm btn-primary" title="Export Data" target="_blank"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
		 						<button type="button" class="btn btn-sm btn-default" onclick="importCus()" title="Import Data" id="importCus"><i class="fa fa-upload"></i> Import Data</button>
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
		 						<table id="datacustomer" width="100%" class="table table-striped table-bordered">
		 							<thead>
		 								<tr>
		 									<th>Kode</th>
		 									<th>Nama Customer</th>
		 									<th>Jenis Kelamin</th>
		 									<th>Telp</th>
		 									<th>Alamat</th>
		 									<th>Pilih Paket</th>
		 									<th>Tanggal Booking</th>
		 									<th>Opsi</th>
		 								</tr>
		 							</thead>
		 							<tbody>
		 								<?php foreach ($customer as $row) { ?>
		 									<tr>
		 										<td><?php echo $row->kode_cs ?></td>
		 										<td><?php echo $row->nama_cs ?></td>
		 										<td><?php echo $row->jenis_kelamin ?></td>
		 										<td><?php echo $row->telp ?></td>
		 										<td><?php echo $row->alamat ?></td>
		 										<td>
		 											<?php
														switch ($row->jenis_cs) {
															case 'Studio 1':
																echo 'Studio 1 (Self Photo)';
																break;
															case 'Studio 2':
																echo 'Studio 2 (Self Photo)';
																break;
															case 'Studio 3':
																echo 'Studio 3 (Wide Photobox)';
																break;
															case 'Studio 4':
																echo 'Studio 4 (Photo Elevator)';
																break;
															default:
																echo $row->jenis_cs;
																break;
														}
														?>
		 										</td>
		 										<td><?php echo date('d-m-Y H:i', strtotime($row->tanggal_booking)) ?></td>
		 										<td>
		 											<a href="javascript:void(0)" class="btn btn-xs btn-info" onclick="editCustomer('<?php echo $row->id_cs ?>')" title="Edit Data"><i class="fa fa-edit"></i></a>
		 											<a href="javascript:void(0)" class="btn btn-xs btn-danger" onclick="hapusCustomer('<?php echo $row->id_cs ?>')" title="Hapus Data"><i class="fa fa-trash"></i></a>
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
		 <?php include 'editcs.php' ?>
		 <?php include 'import.php' ?>
		 <?php include 'script.php' ?>