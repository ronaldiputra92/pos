<!-- top navigation -->
<div class="top_nav">
  <div class="nav_menu">
    <nav>
      <div class="nav toggle">
        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
      </div>
      <ul class="nav navbar-nav navbar-right">
        <li class="">
          <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <img src="<?php echo base_url('assets/production/') ?>images/user.png" alt=""> <?php echo $user['nama_lengkap'] ?>
            <span class=" fa fa-angle-down"></span>
          </a>
          <ul class="dropdown-menu dropdown-usermenu pull-right">
            <?php if ($user['username'] == 'admin') : ?>
              <li><a href="<?php echo base_url('user/change_password') ?>"><i class="fa fa-key pull-right"></i> Ubah Password</a></li>
            <?php endif; ?>
            <li><a href="<?php echo base_url('auth/logout') ?>"><i class="fa fa-sign-out pull-right"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</div>