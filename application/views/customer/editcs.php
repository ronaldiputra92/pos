 <div class="modal fade" id="editCustomerModal">
 	<div class="modal-dialog">
 		<div class="modal-content">

 			<div class="modal-header">
 				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
 				</button>
 				<h4 class="modal-title" id="editCustomerModal">Edit Data Customer</h4>
 			</div>
 			<div class="modal-body">
 				<form class="form-horizontal" method="post" action="<?php echo base_url('customer/editcustomer') ?>">
 					<input type="hidden" class="form-control has-feedback-left" id="idcustomer" name="idcustomer">
 					<div class="form-group">
 						<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Lengkap</label>
 						<div class="col-md-9 col-sm-9 col-xs-12">
 							<input type="text" class="form-control" id="editnamacs" name="editnamacs" autocomplete="off">
 						</div>
 					</div>
 					<div class="form-group">
 						<label class="control-label col-md-3 col-sm-3 col-xs-12">Jenis Kelamin</label>
 						<div class="col-md-9 col-sm-9 col-xs-12">
 							<select id="editkelamin" name="editkelamin" class="form-control" required>
 								<option value=""></option>
 								<option value="Laki-Laki">Laki-Laki</option>
 								<option value="Perempuan">Perempuan</option>
 							</select>
 						</div>
 					</div>
 					<div class="form-group">
 						<label class="control-label col-md-3 col-sm-3 col-xs-12">No. Telp</label>
 						<div class="col-md-9 col-sm-9 col-xs-12">
 							<input type="text" class="form-control" id="edittelpcs" name="edittelpcs" autocomplete="off">
 						</div>
 					</div>
 					<div class="form-group">
 						<label class="control-label col-md-3 col-sm-3 col-xs-12">Alamat</label>
 						<div class="col-md-9 col-sm-9 col-xs-12">
 							<textarea col="7" rows="2" class="form-control" name="editalamat" id="editalamat"></textarea>
 						</div>
 					</div>
 					<div class="form-group">
 						<label class="control-label col-md-3 col-sm-3 col-xs-12">Pilih Paket</label>
 						<div class="col-md-9 col-sm-9 col-xs-12">
 							<select id="editjenis" name="editjenis" class="form-control" required>
 								<option value="">--Pilih Paket--</option>
 								<option value="Studio 1">Studio 1 (Self Photo)</option>
 								<option value="Studio 2">Studio 2 (Self Photo)</option>
 								<option value="Studio 3">Studio 3 (Wide Photobox)</option>
 								<option value="Studio 4">Studio 4 (Photo Elevator)</option>
 							</select>
 						</div>
 					</div>
 					<div class="form-group">
 						<label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Booking</label>
 						<div class="col-md-9 col-sm-9 col-xs-12">
 							<input type="datetime-local" class="form-control" id="edittanggalbooking" name="edittanggalbooking" autocomplete="off">
 						</div>
 					</div>
 					<div class="modal-footer">
 						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
 						<button type="submit" class="btn btn-primary">Save changes</button>
 					</div>
 				</form>
 			</div>
 		</div>
 	</div>
 </div>