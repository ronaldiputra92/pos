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
		 						<button type="button" class="btn btn-sm btn-danger" onclick="deluserlog()" title="Hapus Data User Log"><i class="fa fa-trash"></i> Hapus Data</button>
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
		 									<th>Username</th>
		 									<th>Nama Lengkap</th>
		 									<th>Tipe User</th>
		 									<th>Waktu Login</th>
		 									<th>Waktu Logout</th>
		 								</tr>
		 							<tbody>
		 								<?php foreach ($logs as $val) { ?>
		 									<tr>
		 										<td><?php echo $val['username'] ?></td>
		 										<td><?php echo $val['nama_lengkap'] ?></td>
		 										<td><?php echo $val['tipe'] ?></td>
		 										<td><?php echo $val['login'] ?></td>
		 										<td>
		 											<?php if ($val['logout'] == null) { ?>
		 												<span class="label label-success">Sedang Aktif</span>
		 											<?php } else { ?>
		 												<?php echo $val['logout'] ?>
		 											<?php } ?>
		 										</td>
		 									</tr>
		 								<?php } ?>
		 							</tbody>
		 							</thead>
		 						</table>
		 					</div>
		 				</div>
		 			</div>
		 		</div>
		 	</div>
		 </div>
		<?php include 'script.php' ?>