 <div class="modal fade" id="inputDataBarang">
 	<div class="modal-dialog">
 		<div class="modal-content">

 			<div class="modal-header">
 				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
 				</button>
 				<h4 class="modal-title" id="inputDataBarang">Entry Data Barang</h4>
 			</div>
 			<div class="modal-body">
 				<form class="form-horizontal" method="post" action="<?php echo base_url('barang/inputbarang') ?>" enctype="multipart/form-data">
 					<div class="form-group">
 						<label class="control-label col-md-3 col-sm-3 col-xs-12">BarcodeID</label>
 						<div class="col-md-9 col-sm-9 col-xs-12">
 							<input type="text" class="form-control" id="barcodeitem" name="barcode" autocomplete="off">
 						</div>
 					</div>
 					<div class="form-group">
 						<label class="control-label col-md-3 col-sm-3 col-xs-12">Nama Barang</label>
 						<div class="col-md-9 col-sm-9 col-xs-12">
 							<input type="text" class="form-control" id="namabarang" name="namabarang" autocomplete="off">
 						</div>
 					</div>
 					<div class="form-group">
 						<label class="control-label col-md-3 col-sm-3 col-xs-12">Harga Beli</label>
 						<div class="col-md-9 col-sm-9 col-xs-12">
 							<input type="number" class="form-control" id="beli" name="beli" autocomplete="off">
 						</div>
 					</div>
 					<div class="form-group">
 						<label class="control-label col-md-3 col-sm-3 col-xs-12">Harga Jual (Umum)</label>
 						<div class="col-md-9 col-sm-9 col-xs-12">
 							<input type="number" class="form-control" id="jual" name="jual" autocomplete="off">
 						</div>
 					</div>
 					<div class="form-group">
 						<label class="control-label col-md-3 col-sm-3 col-xs-12">Harga Pelanggan</label>
 						<div class="col-md-9 col-sm-9 col-xs-12">
 							<input type="number" class="form-control" id="pelanggan" name="pelanggan" autocomplete="off">
 						</div>
 					</div>
 					<div class="form-group">
 						<label class="control-label col-md-3 col-sm-3 col-xs-12">Harga Toko</label>
 						<div class="col-md-9 col-sm-9 col-xs-12">
 							<input type="number" class="form-control" id="toko" name="toko" autocomplete="off">
 						</div>
 					</div>
 					<div class="form-group">
 						<label class="control-label col-md-3 col-sm-3 col-xs-12">Harga Sales</label>
 						<div class="col-md-9 col-sm-9 col-xs-12">
 							<input type="number" class="form-control" id="sales" name="sales" autocomplete="off">
 						</div>
 					</div>
 					<div class="form-group">
 						<label class="control-label col-md-3 col-sm-3 col-xs-12">Kategori *</label>
 						<div class="col-md-9 col-sm-9 col-xs-12">
 							<select id="kategori" name="kategori" class="form-control" required>
 								<option value="">- Pilih -</option>
 								<?php foreach ($kategori as $k) : ?>
 									<option value="<?php echo $k['id_kategori'] ?>"><?php echo $k['kategori'] ?></option>
 								<?php endforeach; ?>
 							</select>
 						</div>
 					</div>
 					<div class="form-group">
 						<label class="control-label col-md-3 col-sm-3 col-xs-12">Satuan *</label>
 						<div class="col-md-9 col-sm-9 col-xs-12">
 							<select id="satuan" name="satuan" class="form-control" required>
 								<option value="">- Pilih -</option>
 								<?php foreach ($satuan as $s) : ?>
 									<option value="<?php echo $s['id_satuan'] ?>"><?php echo $s['satuan'] ?></option>
 								<?php endforeach; ?>
 							</select>
 						</div>
 					</div>
 					<div class="form-group">
 						<label class="control-label col-md-3 col-sm-3 col-xs-12">Supplier *</label>
 						<div class="col-md-9 col-sm-9 col-xs-12">
 							<select id="supplier" name="supplier" class="form-control" required>
 								<option value="">- Pilih -</option>
 								<?php foreach ($supplier as $sup) : ?>
 									<option value="<?php echo $sup['id_supplier'] ?>"><?php echo $sup['nama_supplier'] ?></option>
 								<?php endforeach; ?>
 							</select>
 						</div>
 					</div>
 					<div class="form-group">
 						<label class="control-label col-md-3 col-sm-3 col-xs-12">Gambar max 2 MB</label>
 						<div class="col-md-9 col-sm-9 col-xs-12">
 							<input type="file" class="form-control" id="gambar" name="gambar">
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