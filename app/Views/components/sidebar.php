<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <li class="nav-item">
      <a class="nav-link <?php echo (uri_string() == '') ? "" : "collapsed" ?>" href="<?= base_url('/') ?>">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li><!-- End Dashboard Nav -->

    <li class="nav-item">
      <a class="nav-link <?php echo (uri_string() == 'keranjang') ? "" : "collapsed" ?>"
        href="<?= base_url('keranjang') ?>">
        <i class="bi bi-cart-check"></i>
        <span>Keranjang</span>
      </a>
    </li><!-- End Dashboard Nav -->

    <li class="nav-item">
      <a class="nav-link <?php echo (uri_string() == 'transaksi' || strpos(uri_string(), 'transaksi/') === 0) ? "" : "collapsed" ?>" href="<?= base_url('transaksi') ?>">
        <i class="bi bi-clock-history"></i>
        <span>Antrian Pesanan</span>
      </a>
    </li><!-- End Antrian Nav -->
    <?php
    if (session()->get('role') == 'admin') {
      ?>
      <!-- munculkan menu produk -->
      <li class="nav-item">
        <a class="nav-link <?php echo (uri_string() == 'produk' || strpos(uri_string(), 'produk/') === 0) ? "" : "collapsed" ?>"
          href="<?= base_url('produk') ?>">
          <i class="bi bi-receipt"></i>
          <span>Layanan Laundry</span>
        </a>
      </li><!-- End Dashboard Nav -->
      <?php
    }
    ?>
  </ul>

</aside><!-- End Sidebar-->