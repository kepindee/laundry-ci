<?php

namespace App\Controllers;

use App\Models\ProductModel; 

class Home extends BaseController
{
    protected $productModel;

    function __construct(){
        $this->productModel = new ProductModel();
        helper(['number', 'form']);

        // Self-healing schema update to add 'satuan' column
        $db = \Config\Database::connect();
        if (!$db->fieldExists('satuan', 'product')) {
            $db->query("ALTER TABLE product ADD COLUMN satuan VARCHAR(50) DEFAULT 'kg'");
            
            // Seed initial data with correct units based on product names
            $db->query("UPDATE product SET satuan = 'pcs' WHERE nama LIKE '%Bed Cover%' OR nama LIKE '%Sepatu%'");
        }
    }

    public function index(): string
    {
        return view('v_home', [
            'products' => $this->productModel->findAll()
        ]);// baru sampe step 4
    }
}
