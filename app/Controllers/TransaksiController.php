<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class TransaksiController extends BaseController
{
    protected $cart;
    
    public function __construct()
    {
        helper(['number', 'form']);
        $this->cart = service('cart');
    }

    public function index()
    {  
        $data = [
            'items' => $this->cart->contents(),
            'total' => $this->cart->total()
        ];
    
        return view('v_keranjang', $data);
    }
    
    public function cart_add()
    {
        $this->cart->insert([
            'id'      => $this->request->getPost('id'),
            'qty'     => 1,
            'price'   => $this->request->getPost('harga'),
            'name'    => $this->request->getPost('nama'),
            'options' => [
                'foto'   => $this->request->getPost('foto'),
                'satuan' => $this->request->getPost('satuan')
            ]
        ]);
        
        session()->setFlashdata(
            'success',
            'Produk berhasil ditambahkan ke keranjang. <a href="' . base_url('keranjang') . '">Lihat</a>'
        );
        
        return redirect()->to(base_url('/'));
    } 

    public function cart_edit()
    {
        $i = 1;
        foreach ($this->cart->contents() as $item) {
            $qty = $this->request->getPost('qty' . $i++);
    
            $this->cart->update([
                'rowid' => $item['rowid'],
                'qty'   => $qty
            ]);
        }
    
        session()->setFlashdata(
            'success',
            'Keranjang berhasil diperbarui'
        );
    
        return redirect()->to(base_url('keranjang'));
    }

    public function cart_delete($rowid)
    {
        $this->cart->remove($rowid);
    
        session()->setFlashdata(
            'success',
            'Produk berhasil dihapus dari keranjang'
        );
    
        return redirect()->to(base_url('keranjang'));
    }

    public function cart_clear()
    {
        $this->cart->destroy();
    
        session()->setFlashdata(
            'success',
            'Keranjang berhasil dikosongkan'
        );
    
        return redirect()->to(base_url('keranjang'));
    }

    public function checkout()
    {
        $items = $this->cart->contents();
        if (empty($items)) {
            return redirect()->to(base_url('keranjang'))->with('failed', 'Keranjang belanja kosong');
        }

        $transactionModel = new TransactionModel();
        $transactionDetailModel = new TransactionDetailModel();

        // 1. Insert into transaction
        $dataTransaksi = [
            'username'    => session()->get('username'),
            'total_harga' => $this->cart->total(),
            'alamat'      => $this->request->getPost('alamat'),
            'ongkir'      => 0,
            'status'      => 0 // 0 = Baru/Antrian, 1 = Diproses, 2 = Selesai
        ];

        $transactionId = $transactionModel->insert($dataTransaksi);

        // 2. Insert into transaction_detail
        foreach ($items as $item) {
            $dataDetail = [
                'transaction_id' => $transactionId,
                'product_id'     => $item['id'],
                'jumlah'         => $item['qty'],
                'diskon'         => 0,
                'subtotal_harga' => $item['subtotal']
            ];
            $transactionDetailModel->insert($dataDetail);
        }

        // 3. Clear cart
        $this->cart->destroy();

        return redirect()->to(base_url('transaksi'))->with('success', 'Pesanan berhasil dikirim ke antrian! Admin akan segera memproses.');
    }

    public function list_transaksi()
    {
        $transactionModel = new TransactionModel();
        $db = \Config\Database::connect();

        if (session()->get('role') == 'admin') {
            $transaksi = $transactionModel->orderBy('created_at', 'DESC')->findAll();
        } else {
            $transaksi = $transactionModel->where('username', session()->get('username'))->orderBy('created_at', 'DESC')->findAll();
        }

        foreach ($transaksi as &$t) {
            $t['details'] = $db->table('transaction_detail')
                ->select('transaction_detail.*, product.nama as nama_produk, product.foto, product.satuan')
                ->join('product', 'product.id = transaction_detail.product_id')
                ->where('transaction_id', $t['id'])
                ->get()
                ->getResultArray();
        }

        return view('transaksi/index', [
            'transaksi' => $transaksi
        ]);
    }

    public function update_status($id)
    {
        if (session()->get('role') != 'admin') {
            return redirect()->to(base_url('transaksi'))->with('failed', 'Hanya admin yang dapat memperbarui status');
        }

        $transactionModel = new TransactionModel();
        $status = $this->request->getPost('status');

        $transactionModel->update($id, ['status' => $status]);

        return redirect()->to(base_url('transaksi'))->with('success', 'Status pesanan berhasil diperbarui');
    }

    public function delete_transaksi($id)
    {
        if (session()->get('role') != 'admin') {
            return redirect()->to(base_url('transaksi'))->with('failed', 'Hanya admin yang dapat membatalkan pesanan');
        }

        $transactionModel = new TransactionModel();
        $transactionDetailModel = new TransactionDetailModel();

        // Soft delete transaction & its details
        $transactionModel->delete($id);
        $transactionDetailModel->where('transaction_id', $id)->delete();

        return redirect()->to(base_url('transaksi'))->with('success', 'Pesanan berhasil dihapus/dibatalkan');
    }
}
