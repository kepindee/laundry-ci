<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // membuat data layanan laundry
        $data = [
            [
                'nama'       => 'Cuci Kering Setrika Regular 2-3 Hari',
                'harga'      => 8000,
                'jumlah'     => 999,
                'foto'       => 'cuci_regular.jpg',
                'satuan'     => 'kg',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'nama'       => 'Cuci Kering Setrika Express 1 Hari',
                'harga'      => 15000,
                'jumlah'     => 999,
                'foto'       => 'cuci_express.jpg',
                'satuan'     => 'kg',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'nama'       => 'Cuci Kering Saja',
                'harga'      => 6000,
                'jumlah'     => 999,
                'foto'       => 'cuci_kering.jpg',
                'satuan'     => 'kg',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'nama'       => 'Setrika Saja',
                'harga'      => 5000,
                'jumlah'     => 999,
                'foto'       => 'setrika_saja.jpg',
                'satuan'     => 'kg',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'nama'       => 'Cuci Bed Cover',
                'harga'      => 25000,
                'jumlah'     => 999,
                'foto'       => 'cuci_bedcover.jpg',
                'satuan'     => 'pcs',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ],
            [
                'nama'       => 'Cuci Sepatu Premium',
                'harga'      => 35000,
                'jumlah'     => 999,
                'foto'       => 'cuci_sepatu.jpg',
                'satuan'     => 'pcs',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ]
        ];

        foreach ($data as $item) {
            // insert semua data ke tabel
            $this->db->table('product')->insert($item);
        }
    }
}