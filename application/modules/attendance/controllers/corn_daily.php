<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Corn_daily extends Mx_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('attendance/Csv_model');
    }

    public function test()
{
    echo "cron controller working";
}

    // public function auto_checkout()
    // {
    //     // 🔐 Simple security token
    //     $token = $this->input->get('token');
    //     if ($token !== 'SECURE123') {
    //         show_error('Unauthorized access', 403);
    //     }

    //     $currentTime = date('H:i');

    //     // ⏰ Run only after 8 PM
    //     if ($currentTime >= '21:00') {

    //         $this->Csv_model->auto_checkout_missing_users();
    //         echo "Auto checkout completed at ".date('H:i:s');

    //     } else {
    //         echo "Cron triggered before 8 PM";
    //     }
    // }
}