<?php
// module directory name
$HmvcConfig['accounting']["_title"]       = "Accounting Management System";
$HmvcConfig['accounting']["_description"] = "Simple Accounting System";


// register your module tables
// only register tables are imported while installing the module
$HmvcConfig['accounting']['_database'] = false;
$HmvcConfig['accounting']["_tables"] = array( 
	'acc_account',
	'acc_bank',  
);
