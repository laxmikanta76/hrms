<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cache extends CI_Controller {

    public function clear()
    {
        // Delete cache files
        $path = APPPATH . 'cache/';
        $files = glob($path . '*');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        // Delete system cache (if used)
        $sys_cache = BASEPATH . 'cache/';
        $sys_files = glob($sys_cache . '*');

        foreach ($sys_files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        echo "Cache cleared successfully!";
    }
}