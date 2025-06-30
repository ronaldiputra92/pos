        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="clearfix"></div>
            <div class="profile clearfix">
              <div class="profile_pic">
                <img src="<?php echo base_url('assets/img/profil/') . $toko->logo_toko ?>" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2><?php echo $user['nama_lengkap'] ?></h2>
              </div>
            </div>
            <br />
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                  <?php if ($user['tipe'] == "Administrator") { ?>
                    <li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="<?php echo base_url('dashboard') ?>">Dashboard</a></li>
                      </ul>
                    </li>
                  <?php } ?>
                  <li><a><i class="fa fa-calendar"></i> Booking Online <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo base_url('booking') ?>" target="_blank">Halaman Booking</a></li>
                      <li><a href="<?php echo base_url('booking/admin') ?>">Manajemen Booking</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-shopping-cart"></i> Transaksi <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo base_url('penjualan') ?>">Entry Penjualan</a></li>
                      <li><a href="<?php echo base_url('dpenjualan') ?>">Daftar Penjualan</a></li>

                      <li><a href="<?php echo base_url('pembelian') ?>">Entry Pembelian</a></li>
                      <li><a href="<?php echo base_url('dpembelian') ?>">Daftar Pembelian</a></li>
                      <li><a href="<?php echo base_url('hutang') ?>">Hutang</a></li>
                      <li><a href="<?php echo base_url('piutang') ?>">Piutang</a></li>
                    </ul>
                  </li>
                  <?php if ($user['tipe'] == "Administrator") { ?>
                    <li><a><i class="fa fa-desktop"></i> Master Data <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="<?php echo base_url('barang') ?>">Data Barang</a></li>
                        <li><a href="<?php echo base_url('kategori') ?>">Data Kategori Barang</a></li>
                        <li><a href="<?php echo base_url('satuan') ?>">Data Satuan Barang</a></li>
                        <li><a href="<?php echo base_url('supplier') ?>">Data Supplier</a></li>
                        <li><a href="<?php echo base_url('customer') ?>">Data Customer</a></li>
                        <li><a href="<?php echo base_url('karyawan') ?>">Data Karyawan</a></li>
                        <li><a href="<?php echo base_url('servis') ?>">Data Servis</a></li>
                        <!-- <li><a href="<?php //echo base_url('mutasi')
                                          ?>">Mutasi Barang</a></li> -->
                        <li><a href="<?php echo base_url('stokopname') ?>">Stok Opname</a></li>
                        <li><a href="<?php echo base_url('stok') ?>">Stok In/Out</a></li>
                        <li><a href="<?php echo base_url('gudang') ?>">Data Gudang</a></li>
                      </ul>
                    </li>
                    <li><a><i class="fa fa-money"></i> Keuangan <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="<?php echo base_url('kas') ?>">Kas</a></li>
                        <li><a href="<?php echo base_url('ppn') ?>">PPN</a></li>
                        <li><a href="<?php echo base_url('bank') ?>">Bank</a></li>
                      </ul>
                    </li>

                    <li><a><i class="fa fa-file-text-o"></i> Laporan <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="<?php echo base_url('laporan/barang') ?>">Laporan Barang</a></li>
                        <li><a href="<?php echo base_url('laporan/penjualan') ?>">Laporan Penjualan</a></li>
                        <li><a href="<?php echo base_url('laporan/pembelian') ?>">Laporan Pembelian</a></li>
                        <!-- <li><a href="<?php //echo base_url('laporan/mutasi')
                                          ?>">Laporan Mutasi Barang</a></li> -->
                        <li><a href="<?php echo base_url('laporan/stokopname') ?>">Laporan Stok Opname</a></li>
                        <li><a href="<?php echo base_url('laporan/laba_rugi') ?>">Laporan Laba Rugi</a></li>
                        <li><a href="<?php echo base_url('laporan/kas') ?>">Laporan Kas</a></li>
                        <li><a href="<?php echo base_url('laporan/kas_bank') ?>">Laporan Kas Bank</a></li>
                        <li><a href="<?php echo base_url('laporan/stok') ?>">Laporan Stok In/Out</a></li>
                        <li><a href="<?php echo base_url('laporan/hutang') ?>">Laporan Hutang</a></li>
                        <li><a href="<?php echo base_url('laporan/piutang') ?>">Laporan Piutang</a></li>
                      </ul>
                    </li>
                    <li><a><i class="fa fa-user"></i> Management User <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="<?php echo base_url('user') ?>">Data User</a></li>
                        <li><a href="<?php echo base_url('userlog') ?>">User Log</a></li>
                      </ul>
                    </li>

                    <li><a><i class="fa fa-bar-chart-o"></i> Grafik <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="<?php echo base_url('grafik') ?>">Grafik</a></li>
                      </ul>
                    </li>

                    <li><a><i class="fa fa-magic"></i> Tools <span class="fa fa-chevron-down"></span></a>

                      <ul class="nav child_menu">
                        <li><a href="<?php echo base_url('barcode') ?>">Generate Barcode</a></li>
                        <li><a href="<?php echo base_url('backup') ?>">Backup Data</a></li>
                      </ul>
                    </li>

                    <li><a><i class="fa fa-gift"></i> Prestasi <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="<?php echo base_url('prestasi') ?>">Index Prestasi</a></li>
                      </ul>
                    </li>

                    <li><a><i class="fa fa-gears"></i> Setting <span class="fa fa-chevron-down"></span></a>
                      <ul class="nav child_menu">
                        <li><a href="<?php echo base_url('profil') ?>">Profil</a></li>
                        <!-- <li><a href="<?php //echo base_url('promo')
                                          ?>">Setting Promo</a></li> -->
                      </ul>
                    </li>
                  <?php } ?>
                </ul>
              </div>
            </div>
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Settings" href="<?php echo base_url('profil') ?>">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" onclick="toggleFullScreen()" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="User Log" href="<?php echo base_url('userlog') ?>">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="<?php echo base_url('auth/logout') ?>">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
          </div>
        </div>