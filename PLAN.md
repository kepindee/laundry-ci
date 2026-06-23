# Codeigniter4 - Create, Update, dan Delete Data

!image.png

- Menyiapkan routes untuk CRUD data produk
    1. Setelah berhasil melakukan Read Data untuk data product dan user, lanjutkan dengan membuat aksi yang selanjutnya yaitu Create, Update, dan Delete data.
        
        Buat CRUD untuk manajemen data produk dulu saja, sediakan sebuah halaman untuk menu Produk yang digunakan untuk manajemen data dari database table product. 
        
        Langkah pertama, siapkan dulu routesnya, tambahkan tiga rute yang diperlukan pada file app/Config/Routes.php.
        
        !Untitled
        
        - Route untuk create atau insert data baru. Menggunakan **post** karena akan ada POST request untuk data yang dikirimkan dari form.
            
            ```php
            $routes->post('produk', 'ProdukController::create', ['filter' => 'auth']);
            ```
            
        - Route untuk ubah data yang sudah ada. Menggunakan **post** karena akan ada POST request untuk data yang dikirimkan dari form.
            
            ```php
            $routes->post('produk/edit/(:any)', 'ProdukController::edit/$1', ['filter' => 'auth']);
            ```
            
        - Route untuk hapus data yang sudah ada.
            
            ```php
            $routes->get('produk/delete/(:any)', 'ProdukController::delete/$1', ['filter' => 'auth']);
            ```
            
        
        Pada routes untuk aksi edit dan hapus, menggunakan **placeholder** yang berfungsi untuk menambahkan **parameter**, parameter ini nantinya digunakan oleh function pada controller yang bertanggung jawab atas rute ini. Parameter dibutuhkan pada rute ini karena ketika melakukan edit/hapus data harus harus menyertakan **id** supaya bisa tepat sasaran data mana yang akan diedit/dihapus.
        
        Saat sebuah rute menggunakan parameter, harus menentukan jenis data apa yang dikirimkan melalui parameter tersebut, apakah berbentuk angka, huruf, atau bentuk lainnya.
        
        Beberapa placeholder yang dapat digunakan adalah :
        
        - **(:any)** digunakan untuk menangkap seluruh jenis value
        - **(:segment)** digunakan untuk menangkap seluruh jenis value kecuali forward slash (/)
        - **(:num)** digunakan untuk menangkap value berupa angka
        - **(:alpha)** digunakan untuk menangkap value berupa huruf
        - **(:alphanum)** digunakan untuk menangkap value berupa huruf dan angka
        - **(:hash)** memiliki konsep yang sama dengan segment hanya saja ini lebih mudah digunakan jika ketika id pada routing menggunakan hash
        
        Berikut adalah contoh URL untuk melakukan edit data untuk data produk dengan id = 23 : 
        **http://localhost:8080/produk/edit/23**
        
        - **http://localhost:8080** → URL utama dari project atau base url.
        - **produk** → segment 1 yang merupakan nama controller (ProdukController).
        - **edit** → segment 2 yang merupakan nama function di dalam ProdukController (produk).
        - **23** → segment 3 yang merupakan nilai parameter pertama ($1) dari function edit yang ada pada ProdukController (produk).
        
        Sehingga, contoh code pada controller yang digunakan untuk menangani request edit tersebut adalah 
        
        ```php
        class ProdukController extends BaseController
        {
            protected $product; 
        
            function __construct()
            {
                $this->productModel = new ProductModel();
            } 
        
        		/*
            fungsi dibawah ini yang bertanggung jawab untuk
            menangani request dari **http://localhost:8080/produk/edit/23
            */**
            public function edit($id)
            {
        		    //pada fungsi harus diberi variable untuk menerima value dari parameter
        		    //contohnya menggunakan variable $id
        		    
        		    **$dataForm = [
                    'nama' => $this->request->getPost('nama'),
                    'harga' => $this->request->getPost('harga'),
                    'jumlah' => $this->request->getPost('jumlah')
                ];
                
                $this->**productModel**->update($id, $dataForm);**
            }    
        }
        ```
        
        Jika diperhatikan, rute-rute untuk produk mempunyai pola dan konfigurasi yang sama. Sama-sama diawali (prefix) ‘produk’ dan juga semuanya menggunakan filter auth.
        
        !Untitled
        
        Oleh karena hal tersebut, penulisan rute-rute untuk produk bisa dibuat group seperti ini supaya routing lebih rapi dan mudah dimaintenance. Perhatikan perbedaannya.
        
        !Untitled
        
        ```php
        $routes->group('produk', ['filter' => 'auth'], function ($routes) { 
            $routes->get('', 'ProdukController::index');
            $routes->post('', 'ProdukController::create');
            $routes->post('edit/(:any)', 'ProdukController::edit/$1');
            $routes->get('delete/(:any)', 'ProdukController::delete/$1');
        });
        ```
        
- Membuat Read Data
    1. Routes sudah siap, lanjutkan untuk konfigurasi pada controller yang digunakan, yaitu pada file app/Controllers/ProductController.php. Supaya dapat berinteraksi dengan table produk yang ada di database, tambahkan code penggunaan ProductModel seperti ini
        
        !image.png
        
        ```php
        use App\Models\ProductModel;
        ```
        
        ```php
        protected $productModel; 
        
        function __construct()
        {
            $this->productModel = new ProductModel();
        }
        ```
        
    2. Sebelum lanjut untuk menampilkan (read) data produk pada menu manajemen data produk, lakukan sedikit perubahan pada file app\Views\v_produk.php ini.
        
        !image.png
        
        Ubah nama file menjadi ‘index.php’ kemudian pindahkan ke dalam folder baru bernama ‘produk’ seperti ini. Folder produk ada di dalam folder Views.
        
        !image.png
        
        Pada menu manajemen produk ini nantinya akan terdapat beberapa file view, yaitu view untuk tampilan utama data produk (index.php ini), untuk menambah data produk baru, dan untuk mengedit data produk yang sudah ada. Sehingga akan lebih rapi jika view untuk menu produk dibuat subfolder seperti ini.
        
        Di CodeIgniter 4, folder Views sering dikelompokkan ke subfolder seperti ini supaya struktur lebih rapi dan mudah dikelola, terutama saat aplikasi mulai besar. Pengelompokannya biasanya berdasarkan :
        
        - Fitur atau modul, misalnya : auth, produk, transaksi
        - Role user, misalnya : admin, guest
        - Area aplikasi, misalnya : frontend, backend
        
    3. View siap digunakan, namun kembali ke controller dulu untuk menggunakan productModel yang tadi sudah dipanggil. Pada file app/Controllers/ProductController.php, tambahkan code untuk menampilkan data product pada fungsi index() menggunakan fungsi findAll() seperti ini
        
        !image.png
        
        ```php
        return view('produk/index', [
            'products' => $this->productModel->findAll()
        ]);
        ```
        
        Ingat, file view yang digunakan sudah bukan ‘v_produk’ lagi, tapi ‘produk/index’.
        
    4. Lanjutkan pada file app/Views/produk/index.php. Tampilkan data produk menggunakan perulangan, mirip dengan saat menampilkan data produk pada halaman Home di tutorial sebelumnya. Namun bedanya, jika pada halaman Home data produk ditampilkan menggunakan card. Sedangkan pada menu Produk, data akan ditampilkan menggunakan table. Sehingga isi file app/Views/produk/index.php menjadi seperti ini
        
        ```php
        <?= $this->extend('layout') ?>
        <?= $this->section('content') ?> 
        <!-- Table with stripped rows -->
        <table class="table datatable">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Foto</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $index => $produk) : ?>
                    <tr>
                        <th scope="row"><?= $index + 1 ?></th>
                        <td><?= $produk['nama'] ?></td>
                        <td><?= $produk['harga'] ?></td>
                        <td><?= $produk['jumlah'] ?></td>
                        <td>
                            <?php if ($produk['foto'] != '' and file_exists("img/" . $produk['foto'] . "")) : ?>
                                <img src="<?= base_url() . "img/" . $produk['foto'] ?>" width="100">
                            <?php endif; ?>
                        </td>
                        <td>
                            aksi
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <!-- End Table with stripped rows --> 
        <?= $this->endSection() ?>
        ```
        
        !image.png
        
        Pada bagian code untuk menampilkan foto produk terdapat pengecekan kondisi. Foto produk akan ditampilkan jika isi field foto di table product tidak kosong. Kemudian jika data foto tidak kosong, dicek juga apakah file foto tersebut ditemukan pada folder ‘public/img’. Hasil tampilannya
        
        !Untitled
        
- Membuat Create Data
    1. Data produk sudah berhasil ditampilkan (read), lanjutkan dengan menambahkan beberapa code untuk tampilan aksi menambah data baru (create). 
        
        Pertama, tambahkan div untuk menampilkan pesan success dan failed dari flashdata. Lalu kedua, tambahkan tombol yang akan digunakan untuk menampilkan sebuah modal (pop up) yang berisi form untuk menambah data baru. 
        
        Letakkan semua code itu pada baris sebelum tulisan <!-- Table with stripped rows -- >.
        
        ```php
        <?php
        if (session()->getFlashData('success')) {
        ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?= session()->getFlashData('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php
        }
        ?>
        <?php
        if (session()->getFlashData('failed')) {
        ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashData('failed') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php
        }
        ?>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            Tambah Data
        </button>
        ```
        
        !image.png
        
        Modal adalah jendela pop-up yang muncul di atas halaman utama untuk menampilkan informasi atau form tanpa pindah halaman. Contohnya seperti ini
        
        !image.png
        
        Dalam penerapannya, biasanya sebuah modal butuh trigger (pemicu) supaya bisa muncul, misalnya user harus klik sebuah tombol, harus mengarahkan kursor ke sebuah area, dll. 
        
        Untuk menghubungkan modal dengan element yang menjadi pemicunya, tambahkan atribut-atribut yang dibutuhkan. Pada kasus kali ini, pemicu yang digunakan adalah sebuah tombol, perhatikan pada button yang baru saja dibuat tadi. 
        
        Pada elemen button tersebut terdapat atribut-atribut yang menjelaskan bahwa tombol ini adalah pemicu untuk menampilkan elemen yang memiliki atribut **id=”addModal”** dalam bentuk modal yang akan dibuat pada langkah selanjutnya.
        
        ```html
        data-bs-toggle="modal" data-bs-target="#addModal"
        ```
        
        !image.png
        
    2. Supaya code lebih rapi, buat elemen modal dalam sebuah file baru bernama app\Views\produk\modal_add.php. Di dalam file tersebut, buat sebuah elemen <div> yang akan digunakan sebagai elemen model dengan id=”addModal”. 
        
        ```html
        <!-- Add Modal Begin -->
        <div class="modal fade" id="addModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div> 
                    <div class="modal-body">
                         
                    </div> 
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button> 
                    </div> 
                </div>
            </div>
        </div>
        <!-- Add Modal End -->
        ```
        
        !image.png
        
        Panggil modal_add pada file app\Views\produk\index.php seperti ini
        
        !image.png
        
        Coba klik tombol Tambah Data, maka akan muncul modal seperti ini
        
        !image.png
        
    3. Masih pada file app\Views\produk\modal_add.php, tambahkan sebuah form yang akan digunakan untuk menambah data produk baru. Form tersebut harus memiliki atribut action yang berisi rute ‘produk’ dan atribut method yang nilainya post. 
        
        Hal ini supaya aksi untuk tambah data produk alurnya sesuai dengan salah satu rute yang sudah dibuat pada langkah no 1 tadi (file app\Config\Routes.php). 
        
        !image.png
        
        Ganti bagian div dengan class="modal-content" pada file app\Views\produk\modal_add.php dengan code yang berisi form berikut ini
        
        ```php
        <div class="modal-content">
        	<div class="modal-header">
        	    <h5 class="modal-title">Tambah Data</h5>
        	    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        	</div>
        	<?= form_open_multipart(base_url('produk')); ?>
        	<?= csrf_field(); ?>
        	
        	<div class="modal-body">
        	    <div class="mb-3">
        	        <?= form_label('Nama', 'nama'); ?>
        	        <?= form_input([
        	            'name'        => 'nama',
        	            'id'          => 'nama',
        	            'class'       => 'form-control',
        	            'placeholder' => 'Nama Barang',
        	            'required'    => true
        	        ]); ?>
        	    </div>
        	
        	    <div class="mb-3">
        	        <?= form_label('Harga', 'harga'); ?>
        	        <?= form_input([
        	            'name'        => 'harga',
        	            'id'          => 'harga',
        	            'class'       => 'form-control',
        	            'placeholder' => 'Harga Barang',
        	            'required'    => true
        	        ]); ?>
        	    </div>
        	
        	    <div class="mb-3">
        	        <?= form_label('Jumlah', 'jumlah'); ?>
        	        <?= form_input([
        	            'type'        => 'number',
        	            'name'        => 'jumlah',
        	            'id'          => 'jumlah',
        	            'class'       => 'form-control',
        	            'placeholder' => 'Jumlah Barang',
        	            'required'    => true
        	        ]); ?>
        	    </div>
        	
        	    <div class="mb-3">
        	        <?= form_label('Foto', 'foto'); ?>
        	        <?= form_upload([
        	            'name'  => 'foto',
        	            'id'    => 'foto',
        	            'class' => 'form-control'
        	        ]); ?>
        	    </div>
        	</div>
        	
        	<div class="modal-footer">
        	    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        	        Close
        	    </button>
        	
        	    <?= form_submit('submit', 'Simpan', ['class' => 'btn btn-primary']); ?>
        	</div>
        	
        	<?= form_close(); ?>
        </div>
        ```
        
        !image.png
        
        Di dalam form terdapat fungsi **csrf_field()** yang akan menghasilkan input dengan type hidden yang isinya token untuk melindungi aplikasi web dari serangan Cross-Site Request Forgery (CSRF). Token ini unik untuk setiap sesi pengguna dan digunakan oleh server untuk memverifikasi apakah permintaan yang diterima adalah valid atau bukan hasil dari serangan CSRF.  
        
        https://www.w3.org/Security/wiki/Cross_Site_Attacks
        
        !image.png
        
        https://codeigniter.com/user_guide/libraries/security.html#cross-site-request-forgery-csrf
        
    4. Karena pada modal terdapat form, jangan lupa tambahkan form helper pada file app\Controllers\ProdukController.php
        
        !image.png
        
        Sampai langkah ini, hasilnya pada menu Produk adalah saat tombol Tambah Data diklik maka form untuk tambah data akan muncul dalam sebuah modal.
        
        !Untitled
        
    5. Selanjutnya perlu membuat fungsi untuk menangani data yang dikirimkan dari form tersebut. Ingat kembali, rute yang digunakan oleh form tersebut adalah
        
        !image.png
        
        Sesuai rute tersebut maka controller yang digunakan adalah ProductController. Buka file app/Controllers/ProductController.php, tambahkan fungsi baru bernama **create,** letakkan saja dibawah fungsi index.
        
        ```php
        public function create()
        {
            $dataFoto = $this->request->getFile('foto');
        
            $dataForm = [
                'nama' => $this->request->getPost('nama'),
                'harga' => $this->request->getPost('harga'),
                'jumlah' => $this->request->getPost('jumlah') 
            ];
        
            if ($dataFoto->isValid()) {
                $fileName = $dataFoto->getRandomName(); 
                $dataFoto->move('img/', $fileName);
                
                $dataForm['foto'] = $fileName;
            }
        
            $this->productModel->insert($dataForm);
        
            return redirect('produk')->with('success', 'Data Berhasil Ditambah');
        } 
        ```
        
        !image.png
        
        Data nama, harga, dan jumlah yang dikirim (post) dari form ditangkap dengan fungsi **getPost**, yang kemudian ditampung dalam satu variable array baru yang bernama **dataForm**. 
        
        Sedangkan untuk file foto yang dikirim dari form, ditangkap dengan fungsi **getFile**. Jika ada file valid yang dipilih oleh user, maka file tersebut akan tersimpan pada direktori **public/img** menggunakan fungsi move(). Nama dari file akan direname menggunakan fungsi **getRandomName()** supaya menghindari file ter-replace jika ada user yang mengunggah file dengan nama yang sama. Hasil dari fungsi ini juga ditampung dalam variable array dataForm untuk mengisi field foto di database table product.
        
        Terakhir, isi dari variable array dataForm (nama, harga, jumlah, dan foto) diinsert ke database table product menggunakan fungsi insert() pada productModel.
        
        https://codeigniter.com/user_guide/database/query_builder.html#insert
        
        Coba dengan menambahkan satu data produk baru, pastikan foto yang dipilih formatnya **jpg**.
        
        !Untitled
        
        Hasilnya, data product baru berhasil ditambahkan dan foto product berhasil diunggah.
        
        !Untitled
        
        !image.png
        
        !image.png
        
        Perhatikan kolom created_at dan updated_at yang otomatis terupdate dengan timestamp pada saat ada data baru yang dibuat.
        
- Membuat Update dan Delete Data
    1. Setelah berhasil membuat fitur Create Data, lanjutkan dengan membuat fitur untuk Edit dan Delete Data. Awali dengan menyiapkan button yang akan menjadi trigger modal untuk aksi Edit dan elemen link untuk aksi delete.
        
        Pada file app\Views\produk\index.php, cari tulisan **aksi** lalu ganti dengan code ****berikut ****ini.
        
        ```php
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editModal-<?= $produk['id'] ?>">
            Ubah
        </button>
        <a href="<?= base_url('produk/delete/' . $produk['id']) ?>" class="btn btn-danger" onclick="return confirm('Yakin hapus data ini ?')">
            Hapus
        </a>
        ```
        
        !image.png
        
        !image.png
        
        Perhatikan, value dari atribut data-bs-target milik button dibuat dinamis menggunakan id dari data produk. Hal ini karena tombol trigger dan form edit modal diperlukan untuk setiap baris data. Sehingga misalnya terdapat tiga buah data product dengan id 1, 2, dan 3 maka id yang terbentuk adalah ‘editModal-1’, ‘editModal-2, dan ‘editModal-3’. 
        
        Begitu pula dengan value dari atribut href milik elemen link untuk aksi Hapus. Perhatikan gambar di bawah ini.
        
        !image.png
        
    2. Sama seperti langkah untuk aksi tambah data produk baru, siapkan sebuah file untuk modal yang nantinya akan diisi dengan form yang digunakan untuk edit data produk.
        
        Buat file baru bernama app\Views\produk\modal_edit.php, isi dengan code ini.
        
        ```php
        <?php foreach ($products as $index => $produk) : ?>    
            <!-- Edit Modal Begin -->
            <div class="modal fade" id="editModal-<?= $produk['id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Data</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <?= form_open_multipart(base_url('produk/edit/' . $produk['id'])); ?>
                        <?= csrf_field(); ?>
        
                        <div class="modal-body">
                            <div class="mb-3">
                                <?= form_label('Nama', 'nama'); ?>
                                <?= form_input([
                                    'name'        => 'nama',
                                    'id'          => 'nama',
                                    'class'       => 'form-control',
                                    'value'       => $produk['nama'],
                                    'placeholder' => 'Nama Barang',
                                    'required'    => true
                                ]); ?>
                            </div>
        
                            <div class="mb-3">
                                <?= form_label('Harga', 'harga'); ?>
                                <?= form_input([
                                    'name'        => 'harga',
                                    'id'          => 'harga',
                                    'class'       => 'form-control',
                                    'value'       => $produk['harga'],
                                    'placeholder' => 'Harga Barang',
                                    'required'    => true
                                ]); ?>
                            </div>
        
                            <div class="mb-3">
                                <?= form_label('Jumlah', 'jumlah'); ?>
                                <?= form_input([
                                    'type'        => 'number', 
                                    'name'        => 'jumlah',
                                    'id'          => 'jumlah',
                                    'class'       => 'form-control',
                                    'value'       => $produk['jumlah'],
                                    'placeholder' => 'Jumlah Barang',
                                    'required'    => true
                                ]); ?>
                            </div>
        
                            <div class="mb-3">
                                <img src="<?= base_url('img/' . $produk['foto']); ?>" width="100">
                            </div>
        
                            <div class="form-check mb-3">
                                <?= form_checkbox([
                                    'name'    => 'check',
                                    'id'      => 'check',
                                    'value'   => '1',
                                    'class'   => 'form-check-input'
                                ]); ?>
        
                                <?= form_label(
                                    'Ceklis jika ingin mengganti foto',
                                    'check',
                                    ['class' => 'form-check-label']
                                ); ?>
                            </div>
        
                            <div class="mb-3">
                                <?= form_label('Foto', 'foto'); ?>
                                <?= form_upload([
                                    'name'  => 'foto',
                                    'id'    => 'foto',
                                    'class' => 'form-control'
                                ]); ?>
                            </div>
                        </div>
        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
        
                            <?= form_submit('submit', 'Simpan', ['class' => 'btn btn-primary']); ?>
                        </div>
        
                        <?= form_close(); ?>
                    </div>
                </div>
            </div>
            <!-- Edit Modal End -->
        <?php endforeach ?>
        ```
        
        !image.png
        
        Berbeda dengan modal yang dibuat untuk kebutuhan tambah data produk baru yang jumlahny hanya ada satu, modal untuk aksi edit data produk ini dibuat sesuai dengan jumlah dari data produk. Seperti yang sudah dijelaskan tadi, misalnya terdapat tiga buah data product dengan id 1, 2, dan 3 maka butuh tiga buah modal yang memiliki id ‘editModal-1’, ‘editModal-2, dan ‘editModal-3’. Supaya lebih mudah, buat modal menggunakan code perulangan yang sama dengan code perulangan untuk menampilkan data produk, menggunakan foreach.
        
        Di dalam masing-masing modalnya terdapat sebuah form yang memiliki atribut action yang berisi rute ‘produk/edit/[id_produk]’ dan atribut method yang nilainya post
        
        !image.png
        
         
        Hal ini supaya aksi untuk edit data produk alurnya sesuai dengan salah satu rute yang sudah dibuat pada langkah no 1 tadi (file app\Config\Routes.php). 
        
        !image.png
        
        Sehingga untuk aksi edit data produk, URL yang digunakan misalnya
        http://localhost:8080/produk/edit/1 
        http://localhost:8080/produk/edit/2 
        http://localhost:8080/produk/edit/3  dst.
        
    3. Panggil modal_edit pada file app\Views\produk\index.php seperti ini
        
        ```php
        <?= $this->include('produk/modal_edit') ?>
        ```
        
        !image.png
        
        Coba klik tombol ubah (ubah data produk ASUS TUF A15 FA506NF), data berhasil ditampilkan dalam form, namun jika diklik tombol Simpan pasti akan muncul error karena fungsi untuk edit data belum dibuat pada ProdukController.
        
        !Untitled
        
    4. Sekalian untuk aksi delete data pada file app\Views\produk\index.php. Untuk menghapus sebuah data produk menggunakan elemen link <a> yang memiliki atribut href sesuai dengan salah satu rute yang sudah dibuat pada langkah no 1 tadi (file app\Config\Routes.php). 
        
        !image.png
        
        !image.png
        
        Sehingga untuk aksi delete, URL yang digunakan misalnya
        http://localhost:8080/produk/delete/1 
        http://localhost:8080/produk/delete/2 
        http://localhost:8080/produk/delete/3 dst.
        
        Coba klik tombol hapus (hapus data produk Lenovo Idepad D330), akan muncul pop up confirmation untuk menghapus data namun jika diklik tombol OK pasti akan muncul error karena fungsi untuk delete  data belum dibuat pada ProdukController.
        
        !Untitled
        
    5. Selanjutnya perlu membuat fungsi untuk menangani data yang dikirimkan dari form edit dan juga fungsi untuk menghapus data product. 
        
        Lihat kembali routes yang sudah dibuat untuk aksi edit dan delete product. Supaya aksi edit dan delete tepat sasaran, perlu menggunakan sebuah parameter untuk kondisi (where). Biasanya yang menjadi parameter adalah field **id** yang merupakan primary key pada table di database.
        
        !image.png
        
        Maka berdasarkan routes yang sudah ada tadi, pada file app\Controllers\ProdukController.php tambahkan dua fungsi baru bernama **edit** dan **delete** yang menggunakan satu parameter, biasanya nama parameter yang digunakan adalah **$id**. Letakkan kedua fungsi ini pada setelah fungsi **create**. Fungsi edit digunakan untuk aksi ubah data product. Fungsi delete digunakan untuk aksi hapus data product.
        
        ```php
        public function edit($id)
        {
            $dataProduk = $this->productModel->find($id);
        
            $dataForm = [
                'nama' => $this->request->getPost('nama'),
                'harga' => $this->request->getPost('harga'),
                'jumlah' => $this->request->getPost('jumlah') 
            ];
        
            if ($this->request->getPost('check') == 1) {
                if ($dataProduk['foto'] != '' and file_exists("img/" . $dataProduk['foto'] . "")) {
                    unlink("img/" . $dataProduk['foto']);
                }
        
                $dataFoto = $this->request->getFile('foto');
        
                if ($dataFoto->isValid()) {
                    $fileName = $dataFoto->getRandomName();
                    $dataFoto->move('img/', $fileName);
                    
                    $dataForm['foto'] = $fileName;
                }
            }
        
            $this->productModel->update($id, $dataForm);
        
            return redirect('produk')->with('success', 'Data Berhasil Diubah');
        }
        
        public function delete($id)
        {
            $dataProduk = $this->productModel->find($id);
            $this->productModel->delete($id);
        
            return redirect('produk')->with('success', 'Data Berhasil Dihapus');
        }
        ```
        
        Pada fungsi edit dan delete ini, alurnya yang pertama adalah data product dicari dulu berdasarkan $id yang didapatkan dari parameter. Ini dilakukan untuk mendapatkan detail dari data sebuah product dengan id tersebut, terutama nama foto. 
        
        Kemudian pada fungsi edit, foto akan terupdate jika user mengisi ceklis untuk mengganti foto. 
        Jika pada data product tersebut sudah ada foto sebelumnya, maka file foto akan dihapus terlebih dahulu dari direktori **public/img** dengan fungsi **unlink** (built-in function di php), dan diganti dengan foto yang baru. Kemudian pada database table product, data dengan $id yang sesuai akan diupdate isinya dengan data product yang baru menggunakan perintah **update**.
        
        https://codeigniter.com/user_guide/database/query_builder.html#update
        
        Dan pada fungsi delete, file foto juga akan dihapus dari direktori **public/img** dengan fungsi **unlink,** kemudian data pada database table product, data dengan $id yang sesuai akan dihapus dengan menggunakan perintah **delete.**
        
        https://codeigniter.com/user_guide/database/query_builder.html#delete
        
        Jadi isi file app\Controllers\ProdukController.php saat ini ada lima fungsi yaitu 
        __construct(), index(), create(), edit(), dan delete().
        
        Coba klik tombol ubah (ubah data produk ASUS TUF A15 FA506NF)
        
        !Untitled
        
        Hasilnya, setelah klik tombol Simpan 
        
        !Untitled
        
        !image.png
        
        Perhatikan kolom updated_at yang otomatis terupdate dengan timestamp yang baru pada saat data diubah.
        
        Coba klik tombol hapus (hapus data produk Lenovo Idepad D330)
        
        !Untitled
        
        Hasilnya, setelah klik tombol OK 
        
        !image.png
        
        !image.png
        
        Perhatikan kolom updated_at dan deleted_at yang otomatis terupdate dengan timestamp yang baru pada saat ada data yang dihapus.
        
        Terisinya timestamp pada kolom deleted_at digunakan sebagai penanda bahwa data telah "terhapus" tapi tanpa benar-benar menghilangkannya dari database
        
        Jadi, inilah yang dinamakan soft delete. Seolah-olah seperti data hanya disembunyikan dari pengguna, sehingga jika data dicari menggunakan fungsi findAll() saja maka data tersebut tidak akan ikut ditampilkan karena kolom deleted_at isinya tidak NULL.
        
        Beberapa contoh code cara mengambil data product.
        
        ```php
        // Mengambil semua data yang aktif saja
        $data['products'] = $this->productModel->findAll()
        
        // Mengambil semua data (data aktif + data yang sudah dihapus)  
        $data['products'] = $this->productModel->withDeleted()->findAll();
        
        // Mengambil semua data yang sudah dihapus saja
        $data['products'] = $this->productModel->onlyDeleted()->findAll();
        ```
        
        Soft delete ini sangat berguna ketika developer ingin memberikan opsi kepada user untuk memulihkan data yang terhapus atau hanya ingin menjaga rekam jejak data tanpa benar-benar kehilangan informasi.