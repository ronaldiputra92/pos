<br><br><br>
	<div class="col-md-30 col-sm-30 col-xs-30">
		<div class="x_panel">
			<div class="x_content">
				<section class="mt-5">
				    
					<h2 class="text-center" style="padding-top: 25px; padding-bottom: 25px"><img src="<?php echo base_url('assets/img/profil/') . $toko->logo_toko ?>" width="40"> <?php echo $toko->nama_toko ?></h2>
					<?php if ($this->session->flashdata('message')): ?>
						<div class="alert-container">
							<?php echo $this->session->flashdata('message'); ?>
						</div>
					<?php endif; ?>
					
					<form action="<?php echo base_url('auth') ?>" method="post">
					    
						<div class="form-group">
							<input type="text" class="form-control" name="username" placeholder="Username" autocomplete="off" autofocus>
							<?php echo form_error('username', '<small class="text-danger">', '</small>'); ?>
						</div>

						<div class="form-group">
							<div class="input-group">
						      <input type="password" class="form-control" id="password" name="password" placeholder="Password">
						      <div class="input-group-addon">
						      	<span id="showHide"><i class="glyphicon glyphicon-eye-open"></i></span>
						      </div>
						    </div>
							<?php echo form_error('password', '<small class="text-danger">', '</small>'); ?>
						</div>
						
						<div class="form-group" style="padding-top: 10px">
							<button class="btn btn-success btn-block" type="submit"><i class="fa fa-lock"></i> Login</button>
						</div>
						
						<div class="clearfix"></div>
						<div class="separator"></div>
						
					</form>
					
				</section>
			</div>
		</div>
	</div>