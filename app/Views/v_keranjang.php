<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php
if (session()->getFlashData('success')) {
?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
?>

<?= form_open('keranjang/edit') ?>

<!-- Table with stripped rows -->
<table class="table datatable">
    <thead>
        <tr>
            <th scope="col">Nama</th>
            <th scope="col">Foto</th>
            <th scope="col">Harga</th> 
            <th scope="col">Jumlah</th>
            <th scope="col">Subtotal</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        if (!empty($items)) :
            foreach ($items as $index => $item) :
        ?>
                <tr>
                    <td><?= $item['name'] ?></td>
                    <td>
                        <?php if (isset($item['options']['foto']) && $item['options']['foto'] != '' && file_exists("img/" . $item['options']['foto'])) : ?>
                            <img src="<?= base_url() . "img/" . $item['options']['foto'] ?>" width="100px">
                        <?php else : ?>
                            Tidak ada gambar
                        <?php endif; ?>
                    </td>
                    <td><?= number_to_currency($item['price'], 'IDR') ?> / <?= esc($item['options']['satuan'] ?? 'kg') ?></td> 
                    <td>
                        <div class="input-group input-group-sm" style="max-width: 140px;">
                            <input type="number" min="1" name="qty<?= $i++ ?>" class="form-control text-center" value="<?= $item['qty'] ?>">
                            <span class="input-group-text bg-light text-secondary fw-semibold"><?= esc($item['options']['satuan'] ?? 'kg') ?></span>
                        </div>
                    </td>
                    <td><?= number_to_currency($item['subtotal'], 'IDR') ?></td>
                    <td>
                        <a href="<?= base_url('keranjang/delete/' . $item['rowid']) ?>" class="btn btn-danger">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
        <?php
            endforeach;
        endif;
        ?>
    </tbody>
</table> 

<button type="submit" class="btn btn-primary">Perbarui Keranjang</button>
 
<?= form_close() ?>

<div class="alert alert-info mt-3">
    <?= "Total = " . number_to_currency($total, 'IDR') ?>
</div>

<div class="d-flex gap-2 mb-4">
    <a class="btn btn-warning" href="<?= base_url('keranjang/clear') ?>">Kosongkan Keranjang</a>
</div>

<?php if (!empty($items)) : ?>
<div class="card border-0 shadow-sm rounded-3 mt-4">
    <div class="card-body p-4">
        <h5 class="card-title fw-bold text-info p-0 mb-3"><i class="bi bi-calendar-plus me-1"></i> Buat Antrian Pesanan</h5>
        <?= form_open('transaksi/checkout') ?>
        
        <div class="mb-3">
            <label for="alamat" class="form-label fw-bold text-dark">Alamat & Catatan Pesanan</label>
            <textarea name="alamat" id="alamat" rows="3" class="form-control" placeholder="Tuliskan alamat penjemputan/pengantaran & catatan khusus jika ada (misal: cuci cepat express, pewangi jasmine, lipat rapi)" required></textarea>
        </div>
        
        <button type="submit" class="btn btn-success fw-bold text-white px-4 py-2 rounded-pill shadow-sm">
            <i class="bi bi-send-fill me-1"></i> Kirim ke Antrian Laundry
        </button>
        
        <?= form_close() ?>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>