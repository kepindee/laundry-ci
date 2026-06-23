# Codeigniter4 - Library

Pada tutorial sebelumnya, dilakukan penambahan proses validasi pada form login menggunakan library **Validation** yang disediakan di dalam CodeIgniter. Namun, apa sebenarnya pengertian dari library?

**Library** adalah kumpulan fungsi, modul, atau kelas yang telah dikompilasi sebelumnya dan dapat diimpor atau digunakan kembali dalam suatu program untuk mempermudah proses development project. 

Walaupun mirip dengan kegunaan dari **Helper**, Library berbeda dengan Helper. 

**Library** biasanya terdiri dari **class yang memiliki function yang lebih kompleks** dan terstruktur, sedangkan **Helper** adalah kumpulan **function yang sederhana dan independen**. 

Jadi, untuk menggunakan helper bisa dengan langsung memanggil fungsi atau method yang dibutuhkan tanpa membuat object dulu. Sedangkan untuk menggunakan Library, harus membuat instantiasi object dari class Library tersebut.

Berikut ini adalah Helper dan Library yang sudah disediakan di dalam framework CodeIgniter.

**Helper**

!image.png

https://codeigniter.com/user_guide/helpers/
index.html 

**Library** 

!image.png

https://codeigniter.com/user_guide/
libraries/index.html

Namun walaupun sudah cukup lengkap library yang sudah disediakan di dalam framework CodeIgniter, terkadang pada saat development ada hal yang tidak bisa diselesaikan oleh library-library bawaan dari Codeigniter tersebut. 

Misalnya pada project ini, butuh fitur yang digunakan untuk download data dalam format **file PDF,** kemudian butuh juga fitur ****untuk membuat **cart (**keranjang belanja). 

Untuk menyelesaikan fitur-fitur tersebut, solusinya adalah dengan menggunakan library dari luar framework atau **external** **library**. 

- Fitur download data menggunakan Dompdf
    1. Untuk membuat file PDF, external library yang bisa digunakan misalnya adalah **DomPDF**
    https://github.com/dompdf/dompdf
        
        Baca petunjuk penggunaannya pada dokumentasi tersebut. Hal pertama yang harus dilakukan adalah install dompdf melalui terminal, dengan perintah
        
        ```php
        composer require dompdf/dompdf
        ```
        
        !image.png
        
        Tunggu sampai selesai installasi. Hasilnya, pada folder **vendor** akan muncul folder baru bernama **dompdf**.
        
        !image.png
        
        Detail dompdf yang digunakan juga akan tercatat pada file **composer.json**
        
        !image.png
        
    2. Kemudian tambahkan **route** nya pada file app/Config/Routes.php, misalnya untuk download data produk 
        
        ```php
        $routes->get('download', 'ProdukController::download');
        ```
        
        !image.png
        
    3. Sesuai dengan route yang baru dibuat, tambahkan sebuah fungsi bernama **download** pada file app/Controllers/ProdukController.php. Namun, panggil dulu Dompdf nya di bagian atas controller.
        
        ```php
        use Dompdf\Dompdf;
        ```
        
        !image.png
        
        Lalu tambahkan fungsi download() yang digunakan untuk menampilkan semua data product dalam tampilan file PDF yang dibuat dari object Dompdf. Letakkan fungsi download() di bawah fungsi delete() misalnya.
        
        ```php
        public function download()
        {
            // Ambil data produk dari database
            $products = $this->productModel->findAll();
        
            // Render view menjadi HTML
            $html = view('produk/download_pdf', [
                'products' => $products
            ]);
        
            // Nama file PDF
            $filename = date('Y-m-d-H-i-s') . '-produk.pdf';
        
            // Inisialisasi Dompdf
            $dompdf = new Dompdf();
        
            // Load HTML ke Dompdf
            $dompdf->loadHtml($html);
        
            // Setting ukuran kertas dan orientasi
            $dompdf->setPaper('A4', 'portrait');
        
            // Generate PDF
            $dompdf->render();
        
            // Download / tampilkan PDF
            $dompdf->stream($filename, [
                'Attachment' => true
            ]);
        }
        ```
        
        !image.png
        
        Cara penggunaan ini sesuai dengan yang dicontohkan pada dokumentasi Dompdf.
        
        !image.png
        
    4. Untuk tampilan file PDFnya, sesuai code tadi siapkan sebuah file view baru bernama download_pdf.php dan letakkan pada folder app\Views\produk. Isi file dengan html sederhana, data ditampilkan dalam bentuk tabel.
        
        ```php
        <h1>Data Produk</h1>
        
        <table border="1" width="100%" cellpadding="5">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Foto</th>
            </tr>
        
            <?php foreach ($products as $index => $produk) : ?>
            <?php
        		    $path = FCPATH . 'img/' . $produk['foto'];
                $base64 = '';
                
                if (file_exists($path)) {
        	        $type = pathinfo($path, PATHINFO_EXTENSION);
        	        $data = file_get_contents($path);
        	        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        				}
            ?>
                <tr>
                    <td align="center"><?= $index + 1 ?></td>
                    <td><?= $produk['nama'] ?></td>
                    <td align="right">Rp <?= number_format($produk['harga'], 2, ",", ".") ?></td>
                    <td align="center"><?= $produk['jumlah'] ?></td>
                    <td align="center">
                        <?php if ($base64) : ?>
                            <img src="<?= $base64 ?>" width="50">
                        <?php else : ?>
                            Tidak ada gambar
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        Downloaded on <?= date("Y-m-d H:i:s") ?>
        ```
        
        !image.png
        
        Pada bagian gambar, terdapat code untuk membaca file gambar lalu mengubahnya menjadi format Base64 Data URI, sehingga gambar bisa ditampilkan langsung di HTML tanpa perlu mengakses file gambar terpisah.
        
        View atau tampilan inilah nanti yang akan menjadi isi dari file PDF nya.
        
    5. Terakhir, supaya user bisa download file PDF nya, tambahkan element link untuk download pada menu Produk. Link ini jika diklik akan mengarah sesuai rute yang dibuat pada langkah no 2 tadi. 
        
        !image.png
        
        Pada file app\Views\produk\index.php tambahkan elemen link dengan tampilan tombol Tambah Data seperti ini
        
        ```php
        <a class="btn btn-success" target="_blank" href="<?= base_url()?>produk/download">
            Download Data
        </a>
        ```
        
        !image.png
        
        !Untitled
        
        Jika tombol Download Data diklik akan menghasilkan file PDF yang isinya seperti di bawah ini
        
        !Untitled
        
    
- Fitur Keranjang Belanja (Cart)
    1. Library external kedua yang akan digunakan pada project ini adalah **cart** yang akan digunakan pada menu Keranjang Belanja, library ini digunakan untuk tempat penyimpanan sementara untuk daftar produk pilihan user yang akan dibeli. Data akan disimpan pada session yang sedang aktif, belum tersimpan secara permanen pada database.
        
        Sebenarnya dulu pada CodeIgniter versi 3 terdapat Cart pada library internalnya, namun pada CodeIgniter 4 sudah tidak bisa digunakan lagi (Deprecated).
        
        https://codeigniter.com/userguide3/libraries/cart.html
        
        Namun, untuk menggunakan fitur cart ini, ada library external yang fiturnya sangat mirip dengan library internal CodeIgniter 3 yang sekarang sudah deprecated itu.
        https://github.com/jason-napolitano/CodeIgniter4-Cart-Module
        Petunjuk penggunaannya ada pada url dokumentasi tersebut. 
        
        !image.png
        
        Hal pertama yang harus dilakukan adalah install codeigniter4-cart-module melalui terminal, dengan perintah 
        
        ```jsx
        composer require jason-napolitano/codeigniter4-cart-module
        ```
        
        !image.png
        
        Hasilnya akan muncul folder baru pada vendor, bernama **jason-napolitano**
        
        !image.png
        
        Akan tercatat juga pada file **composer.json**
        
        !image.png
        
    2. Class cart yang akan digunakan berada pada file vendor\jason-napolitano\codeigniter4-cart-module\src\Cart.php.
        
        !image.png
        
        Kemudian tambahkan konfigurasi namespacenya di file app/Config/Autoload.php pada variable **$psr4** supaya pada saat development bisa mengunakan class cart ini dengan ringkas. Tambahkan saja baris code ini, tanpa menghapus baris lain apapun.
        
        ```jsx
        'CodeIgniterCart' => ROOTPATH . 'vendor/jason-napolitano/codeigniter4-cart-module/src'
        ```
        
        !image.png
        
        Namespace yang ditambahkan adalah namespace yang akan digunakan untuk library ini yaitu **‘CodeIgniterCart’.** File ini berisi sebuah class bernama **cart** yang memiliki banyak fungsi yang bisa digunakan dalam pengelolaan keranjang belanja seperti fungsi :
        
        - `insert()` : Menambahkan item ke dalam cart dan menyimpannya ke session table.
        - `update()` : Fungsi ini memungkinkan quantity dari item tertentu diubah.
        - `saveCart()` : Menyimpan array cart ke session database.
        - `total()` : Total cart.
        - `remove()` : Menghapus item dari cart.
        - `totalItems()` : Mengembalikan total jumlah item.
        - `contents()` : Mengembalikan seluruh array cart.
        - `getItem()` : Mengembalikan detail item tertentu di dalam cart.
        - `hasOptions()` : Mengembalikan nilai `TRUE` jika `rowid` yang diberikan berkaitan dengan item yang memiliki options.
        - `productOptions()` : Mengembalikan array options untuk `row ID` produk tertentu.
        - `formatNumber()` : Mengembalikan angka yang diberikan dengan format koma dan titik desimal.
        - `destroy()` : Mengosongkan cart dan menghapus session.
        
    3. Sebelum menggunakan cart buat dulu routesnya, buka file app/Config/Routes.php dan tambahkan route untuk **menampilkan data keranjang, menambahkan data ke keranjang, mengubah data di keranjang, menghapus data dari keranjang, mengosongkan keranjang.**
        
        Rapikan semua rutenya dalam sebuah group route keranjang.
        
        ```jsx
        $routes->group('keranjang', ['filter' => 'auth'], function ($routes) {
            $routes->get('', 'TransaksiController::index');
            $routes->post('', 'TransaksiController::cart_add');
            $routes->post('edit', 'TransaksiController::cart_edit');
            $routes->get('delete/(:any)', 'TransaksiController::cart_delete/$1');
            $routes->get('clear', 'TransaksiController::cart_clear');
        });
        ```
        
        !image.png
        
        Berikut penjelasan rute-rutenya.
        
        ```php
        //Rute ini digunakan untuk menampilkan isi keranjang belanja
        $routes->get('', 'TransaksiController::index');
        
        //Rute ini digunakan untuk menambah produk ke keranjang belanja
        $routes->post('', 'TransaksiController::cart_add');
        
        //Rute ini digunakan untuk mengubah jumlah produk pada keranjang belanja
        $routes->post('edit', 'TransaksiController::cart_edit');
        
        //Rute ini digunakan untuk menghapus produk dari keranjang belanja
        $routes->get('delete/(:any)', 'TransaksiController::cart_delete/$1');
        
        //Rute ini digunakan untuk mengosongkan keranjang belanja
        $routes->get('clear', 'TransaksiController::cart_clear');
        ```
        
    4. Awali dengan membuat tampilan yang akan digunakan user nanti pada saat menambahkan produk ke dalam keranjang. Aksi ini dilakukan pada menu home dengan cara klik tombol yang tersedia pada tampilan gambar produk. Dengan user klik tombol ini, sebenarnya dia mengirimkan (post) beberapa hidden data melalui sebuah form. Data yang dikirimkan adalah id, nama, harga, foto.
        
        Aksi untuk post data ini menggunakan salah satu dari rute di group route keranjang yang sudah dibuat sebelumnya.
        
        !image.png
        
        Karena aksi ini dilakukan pada menu home, tambahkan code untuk form berikut ini pada file app\Views\v_home.php.
        
        ```php
        <?= form_open('keranjang') ?>
        <?= form_hidden([
            'id'    => $item['id'],
            'nama'  => $item['nama'],
            'harga' => $item['harga'],
            'foto'  => $item['foto']]) ?>
        ```
        
        ```php
        <button type="submit" class="btn btn-info rounded-pill">Beli</button>
        ```
        
        ```php
        <?= form_close() ?>
        ```
        
        !image.png
        
        !image.png
        
        Tambahkan code untuk Flashdata pada baris sebelum <!-- Table with stripped rows -->
        
        ```php
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
        ```
        
        !image.png
        
        Sekalian lakukan format angka untuk tampilan harga produk.
        
        ```php
        <?= number_to_currency($item['harga'], 'IDR') ?>
        ```
        
        !image.png
        
        Untuk menggunakan form dan format angka pada halaman Home, tambahkan helper number dan form pada file app/Controllers/Home.php.  
        
        ```php
        helper(['number', 'form']);
        ```
        
        !image.png
        
    5. Selanjutnya sesuai dengan route yang digaunkan untuk keranjang belanja, lakukan konfigurasi pada file app\Controllers\TransaksiController.php. 
        
        Pertama siapkan sebuah variable pada class TransaksiController untuk menyimpan object cart, misalnya **protected $cart;**, kemudian isi valuenya dengan service **cart()** pada fungsi __construct. Library ini memakai konsep Service Locator milik CI4, jadi object cart dibuat dan dikelola lewat service().
        
        Selanjutnya, variable **$this->cart** (menggunakan keyword this karena variable milik class) bisa menggunakan fungsi-fungsi pada library cart, seperti fungsi **insert(), update(), total()**, dll.  
        
        ```php
        protected $cart;
        
        public function __construct()
        {
            helper(['number', 'form']);
            $this->cart = service('cart');
        }
        ```
        
        !image.png
        
    6. Kemudian pada file app\Controllers\TransaksiController.php tambahkan fungsi cart_add() dan sesuaikan isi fungsi index() seperti ini.
        
        !image.png
        
        ```php
        public function index()
        {  
            $data = [
                'items' => $this->cart->contents() 
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
        	        'foto' => $this->request->getPost('foto')
        	    ]
        	]);
        	
        	session()->setFlashdata(
        	    'success',
        	    'Produk berhasil ditambahkan ke keranjang. 
        	    <a href="' . base_url('keranjang') . '">Lihat</a>'
        	);
        	
        	return redirect()->to(base_url('/'));
        } 
        
        ```
        
        !image.png
        
        Function **cart_add**() digunakan untuk menambahkan data produk ke keranjang, dengan menggunakan fungsi **insert()** bawaan dari modul. Data keranjang akan tersimpan pada session dengan bentuk array yang memiliki index '**id**', '**qty**', '**price**', dan '**name**'. Jadi misalkan butuh data selain index tersebut, bia ditambahkan pada index ‘**options’**, seperti pada contoh diatas untuk menyimpan data 'foto'.
        
        Kemudian pada function **index(),** isi dari data keranjang diambil dengan menggunakan fungsi **contents()** bawaan dari modul kemudian ditampung dalam variable array **$data** pada index **items**. Variable ini akan ditampilkan pada file view app\Views\v_keranjang.php.
        
    7. Untuk cek hasilnya pada menu Keranjang, sementara tambahkan code untuk menampilkan isi variable $items pada file app\Views\v_keranjang.php.
        
        ```php
        <?= d($items)?>
        ```
        
        !image.png
        
        Coba klik tombol beli pada salah satu produk di menu Home, lalu lihat hasilnya pada menu Keranjang.
        
        !image.png
        
        !image.png
        
        Satu data berhasil masuk ke dalam cart (keranjang belanja).
        
    8. Setelah memastikan data berhasil dirender dari controller ke view, buat tampilan data keranjang pada file app\Views\v_keranjang.php menggunakan datatable. Tambahkan juga bagian flashdata untuk memberikan informasi setelah nanti user melakukan aksi di keranjang, sehingga codenya menjadi seperti ini
        
        ```php
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
        <!-- Table with stripped rows -->
        <table class="table datatable">
            <thead>
                <tr>
                    <th scope="col">Nama</th>
                    <th scope="col">Foto</th>
                    <th scope="col">Harga</th> 
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
                            <td><img src="<?= base_url() . "img/" . $item['options']['foto'] ?>" width="100px"></td>
                            <td><?= number_to_currency($item['price'], 'IDR') ?></td> 
                        </tr>
                <?php
                    endforeach;
                endif;
                ?>
            </tbody>
        </table> 
        <?= $this->endSection() ?>
        ```
        
        !image.png
        
    9. Pada keranjang belanja ini terdapat fitur untuk edit untuk mengubah jumlah (quantity) dari barang yang dibeli. Rute untuk aksi ini sudah dibuat pada langkah sebelumnya.
        
        !image.png
        
        Untuk mengirimkan data yang akan diubah, pada file app\Views\v_keranjang.php tambahkan form yang memiliki action sesuai dengan rutenya yaitu ‘keranjang/edit’. Tambahkan juga kolom table untuk menampilkan data jumlah produk dan harga produk.
        
        ```php
        <?= form_open('keranjang/edit') ?>
        ```
        
        ```php
        <th scope="col">Jumlah</th>
        <th scope="col">Subtotal</th>
        <th scope="col">Aksi</th>
        ```
        
        !image.png
        
        Lengkapi pada bagian bawah untuk table dan form. Form edit ini nanti hanya akan mengirimkan satu data yaitu jumlah produk yang atribut namenya dibuat dinamis menggunakan iterasi variable $i.
        
        Jadi misalnya ada 1 produk di dalam keranjang brarti ada inputan form dengan name qty1, jika 3 produk di dalam keranjang brarti ada inputan form dengan name qty1, qty2, qty3.
        
        ```php
        <td><input type="number" min="1" name="qty<?= $i++ ?>" class="form-control" value="<?= $item['qty'] ?>"></td>
        <td><?= number_to_currency($item['subtotal'], 'IDR') ?></td>
        <td>
            aksi
        </td>
        ```
        
        ```php
        <button type="submit" class="btn btn-primary">Perbarui Keranjang</button>
         
        <?= form_close() ?>
        ```
        
        !image.png
        
        !image.png
        
        Sampai disini, tampilan sudah bisa dilihat tapi aksi untuk edit belum bisa dilakukan.
        
    10. Tambahkan dulu fungsinya pada controller sesuai dengan yang ada di file app\Config\Routes.php. Tambahkan fungsi cart_edit() pada file app\Controllers\TransaksiController.php.
        
        !image.png
        
        Function **cart_edit**() digunakan untuk mengubah jumlah data produk di keranjang, dengan menggunakan fungsi **update** bawaan dari modul. Proses ubah data berdasarkan **rowid** dari data keranjang. 
        
        Data yang dikirimkan (post) dari form pada view bisa dijadikan acuan (pasti match urutannya) berdasarkan atribut namenya (qty1, qty2, qty3, …, qtyn) karena cara membaca data cart pada view dan pada controller dilakukan dengan cara yang sama yaitu menggunakan fungsi contents().
        
        ```php
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
        ```
        
        !image.png
        
        Misalnya coba ubah jumlah produk menjadi 2 lalu klik tombol Perbarui Keranjang.
        
        !image.png
        
    11. Selain edit, pada keranjang belanja ini juga terdapat fitur untuk edit untuk menghapus item barang yang dibeli. Rute untuk aksi ini sudah dibuat pada langkah sebelumnya.
        
        !image.png
        
        Tambahkan elemen link pada file app\Views\v_keranjang.php yang memiliki atribut href yang sesuai untuk aksi delete sesuai dengan rute yang sudah disiapkan. Pada elemen link ini buat tampilan seperti tombol dan tambahkan icon supaya lebih representatif.
        
        ```php
        <a href="<?= base_url('keranjang/delete/' . $item['rowid'] . '') ?>" class="btn btn-danger"><i class="bi bi-trash"></i></a>
        ```
        
        !image.png
        
        !image.png
        
        Sampai disini, tampilan sudah bisa dilihat tapi aksi untuk delete belum bisa dilakukan.
        
    12. Tambahkan dulu fungsinya pada controller sesuai dengan yang ada di file app\Config\Routes.php. Tambahkan fungsi cart_delete() pada file app\Controllers\TransaksiController.php.
        
        !image.png
        
        Function **cart_delete**() digunakan untuk menghapus data produk dari keranjang sesuai dengan rowid nya, dengan menggunakan fungsi **remove** bawaan dari modul.
        
        ```php
        public function cart_delete($rowid)
        {
            $this->cart->remove($rowid);
        
            session()->setFlashdata(
                'success',
                'Produk berhasil dihapus dari keranjang'
            );
        
            return redirect()->to(base_url('keranjang'));
        }
        ```
        
        !image.png
        
        Coba hapus data dari keranjang.
        
        !image.png
        
    13. Fitur terakhir yang ada pada keranjang belanja ini adalah fitur untuk mengosongkan cart atau menghapus semua item barang yang dibeli sekaligus. Rute untuk aksi ini sudah dibuat pada langkah sebelumnya.
        
        !image.png
        
        Sebelum membuat fitur itu, tambahkan dulu di fungsi index() pada file app\Controllers\TransaksiController.php yaitu variable yang digunakan untuk menampilkan total jumlah harga.
        
        ```php
        'total' => $this->cart->total() 
        ```
        
        !image.png
        
        Lalu tambahkan fungsi **cart_clear**() ****yang di dalamnya menjalankan fungsi **destroy()** bawaan dari modul. Fungsi destroy() inilah yang digunakan untuk mengosongkan isi dari cart.
        
        ```php
        public function cart_clear()
        {
            $this->cart->destroy();
        
            session()->setFlashdata(
                'success',
                'Keranjang berhasil dikosongkan'
            );
        
            return redirect()->to(base_url('keranjang'));
        }
        ```
        
        !image.png
        
    14. Fungsi di controller sudah siap, lanjutkan dengan menambahkan elemen link pada file app\Views\v_keranjang.php supaya user bisa melakukan aksi untuk mengosongkan keranjang. Elemen link ini memiliki atribut href yang sesuai untuk aksi mengosongkan keranjang sesuai dengan rute yang sudah disiapkan. Pada elemen link ini buat tampilan seperti tombol. Sekalian juga tambahkan tampilan untuk info nominal total harga keseluruhan pada bagian bawah table.
        
        ```php
        <div class="alert alert-info">
            <?= "Total = " . number_to_currency($total, 'IDR') ?>
        </div>
        ```
        
        ```php
        <a class="btn btn-warning" href="<?= base_url() ?>keranjang/clear">Kosongkan Keranjang</a>
        ```
        
        !image.png
        
        Coba tambahkan kembali beberapa data ke dalam keranjang, lalu coba klik tombol ‘Kosongkan Keranjang’.
        
        !image.png
        
        !image.png
        
        Cara penggunaan fungsi-fungsi cart yang telah dilakukan ini sesuai dengan yang dicontohkan pada dokumentasi codeigniter4-cart-module.
        
        https://github.com/jason-napolitano/CodeIgniter4-Cart-Module