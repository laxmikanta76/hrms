<?php
class Seeder extends CI_Controller {

public function seed_employee_leave()
{
$employees = $this->db->get('employee')->result();

foreach ($employees as $emp) {

$exists = $this->db->where('employee_id', $emp->id)
->get('employee_leave')
->row();

if (!$exists) {
$this->db->insert('employee_leave', [
'employee_id' => $emp->id,
'sick_balance' => 1,
'casual_balance' => 2,
'sick_taken' => 0,
'casual_taken' => 0,
'last_updated' => date('Y-m-d')
]);
}
}

echo "Employee leave seeded successfully";
}
}