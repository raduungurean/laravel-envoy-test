<?php

use Dotenv\Dotenv;
use Illuminate\Database\Seeder;

class MySeeder extends Seeder
{
    protected $importUrl;

    public function __construct()
    {
        $dotenv = Dotenv::create(base_path());

        try {
            $dotenv->load();
            $dotenv->required(['IMPORT_DATA_URL'])->notEmpty();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $importUrl = getenv('IMPORT_DATA_URL');

        $this->importUrl = preg_replace('/\\\\/', '', $importUrl);
    }
}
