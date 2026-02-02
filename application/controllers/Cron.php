<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        // Allow CLI only (BEST PRACTICE)
        if (!$this->input->is_cli_request()) {
            show_404();
        }

        $this->load->model('leave/Leave_model');
        $this->load->model('attendance/Csv_model');

        date_default_timezone_set('Asia/Kolkata');
    }

    /**
     * DEFAULT ENTRY POINT
     * CLI Usage:
     * php index.php cron daily
     * php index.php cron monthly
     * php index.php cron yearly
     */
    public function index($task = null)
    {
        $allowedTasks = [
            'daily'   => 'auto_checkout',
            'monthly' => 'monthly_leave',
            'yearly'  => 'yearly_reset',
        ];

        if (!isset($allowedTasks[$task])) {
            log_message('error', "Invalid cron task: $task");
            exit;
        }

        $method = $allowedTasks[$task];
        $this->$method();
    }

    /* ================= DAILY ================= */

    private function auto_checkout()
    {
        $today_date   = date('Y-m-d');
        $checkout_time = $today_date . ' 18:00:00';

        echo "Running Auto Checkout for: $today_date\n";

        $office = $this->db->select('Latitude, Longitude')
            ->from('setting')
            ->get()
            ->row();

        if (!$office) {
            log_message('error', 'Office location not set');
            return;
        }

        $checkins = $this->db
            ->select('uid, MAX(time) as last_checkin')
            ->from('attendance_history')
            ->where('DATE(time)', $today_date)
            ->where('state', 1)
            ->group_by('uid')
            ->get()
            ->result();

        foreach ($checkins as $checkin) {
            $checkout_exists = $this->db
                ->where('uid', $checkin->uid)
                ->where('DATE(time)', $today_date)
                ->where('state', 0)
                ->where('time >', $checkin->last_checkin)
                ->get('attendance_history')
                ->row();

            if (!$checkout_exists) {
                $this->db->insert('attendance_history', [
                    'uid'       => $checkin->uid,
                    'state'     => 0,
                    'time'      => $checkout_time,
                    'latitude'  => $office->Latitude,
                    'longitude' => $office->Longitude,
                    'remarks'   => 'Auto checkout by system'
                ]);

                log_message('info', "Auto checkout done for UID {$checkin->uid}");
            }
        }
    }

    /* ================= MONTHLY ================= */

    private function monthly_leave()
    {
        $year  = date('Y');
        $month = date('n');

        log_message('info', "Processing monthly leave: $year-$month");
        $this->Leave_model->process_monthly_leave($year, $month);
    }

    /* ================= YEARLY ================= */

    private function yearly_reset()
    {
        $year = date('Y');

        log_message('info', "Resetting yearly leave for: $year");
        $this->Leave_model->reset_yearly_leave($year);
    }
}