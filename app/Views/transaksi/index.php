<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashData('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashData('failed')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('failed') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-4">
        <h5 class="card-title fw-bold text-info p-0 mb-4"><i class="bi bi-list-stars me-1"></i> Antrian Laundry & Status Pesanan</h5>

        <?php if (empty($transaksi)) : ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-journal-x fs-1 d-block mb-2 text-secondary"></i>
                <p class="mb-0">Tidak ada antrian laundry saat ini.</p>
            </div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="table-light">
                            <th>ID & Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Alamat & Catatan</th>
                            <th>Total Harga</th>
                            <th>Status Pesanan</th>
                            <?php if (session()->get('role') == 'admin') : ?>
                                <th>Atur Status (Admin)</th>
                                <th>Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transaksi as $t) : ?>
                            <tr>
                                <td>
                                    <strong class="text-info">#<?= $t['id'] ?></strong><br>
                                    <small class="text-muted"><?= date('d M Y H:i', strtotime($t['created_at'])) ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"><i class="bi bi-person me-1"></i><?= esc($t['username']) ?></span>
                                </td>
                                <td>
                                    <span class="text-secondary" style="font-size: 0.9rem;"><?= nl2br(esc($t['alamat'])) ?></span>
                                    <div class="mt-2">
                                        <!-- Collapsible details of items -->
                                        <button class="btn btn-outline-info btn-xs py-0 px-2 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#details-<?= $t['id'] ?>" aria-expanded="false" style="font-size: 0.75rem;">
                                            <i class="bi bi-eye me-1"></i> Rincian Pakaian
                                        </button>
                                        <div class="collapse mt-2" id="details-<?= $t['id'] ?>">
                                            <div class="p-2 border rounded bg-light" style="font-size: 0.85rem;">
                                                <ul class="list-unstyled mb-0">
                                                    <?php foreach ($t['details'] as $detail) : ?>
                                                        <li class="d-flex align-items-center justify-content-between border-bottom py-1">
                                                            <span>
                                                                <i class="bi bi-check-circle-fill text-success me-1"></i> 
                                                                <?= esc($detail['nama_produk']) ?> 
                                                                <strong>x<?= $detail['jumlah'] ?> <?= esc($detail['satuan'] ?? 'kg') ?></strong>
                                                            </span>
                                                            <span class="text-secondary"><?= number_to_currency($detail['subtotal_harga'], 'IDR') ?></span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark"><?= number_to_currency($t['total_harga'], 'IDR') ?></span>
                                </td>
                                <td>
                                    <?php if ($t['status'] == 0) : ?>
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="bi bi-clock me-1 animate-pulse"></i>Antrian Baru</span>
                                    <?php elseif ($t['status'] == 1) : ?>
                                        <span class="badge bg-info px-3 py-2 rounded-pill"><i class="bi bi-arrow-repeat me-1"></i>Sedang Dicuci</span>
                                    <?php else : ?>
                                        <span class="badge bg-success px-3 py-2 rounded-pill"><i class="bi bi-check2-all me-1"></i>Selesai / Bisa Diambil</span>
                                    <?php endif; ?>
                                </td>
                                <?php if (session()->get('role') == 'admin') : ?>
                                    <td>
                                        <?= form_open(base_url('transaksi/update-status/' . $t['id']), ['class' => 'd-flex align-items-center gap-1']) ?>
                                            <select name="status" class="form-select form-select-sm w-auto">
                                                <option value="0" <?= $t['status'] == 0 ? 'selected' : '' ?>>Antrian Baru</option>
                                                <option value="1" <?= $t['status'] == 1 ? 'selected' : '' ?>>Sedang Dicuci</option>
                                                <option value="2" <?= $t['status'] == 2 ? 'selected' : '' ?>>Selesai / Diambil</option>
                                            </select>
                                            <button type="submit" class="btn btn-info btn-sm text-white" title="Simpan Perubahan"><i class="bi bi-save"></i></button>
                                        <?= form_close() ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('transaksi/delete/' . $t['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin membatalkan/menghapus pesanan ini?')" title="Batalkan / Hapus Pesanan">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
