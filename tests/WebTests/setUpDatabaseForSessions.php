<?php

require_once __DIR__ . '/../../src/Database/Database.php';

function setUpDatabaseForSessions(Database $db) {
	$createTable = <<<TABLE
CREATE TABLE IF NOT EXISTS `sessions` (
    `sess_id` char(32) NOT NULL,
    `sess_data` text NOT NULL,
    `modified` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
TABLE;
	$setUniqueKey = <<<KEY
ALTER TABLE `sessions` ADD UNIQUE KEY `sess_id` (`sess_id`);
KEY;
	$createTable = <<<TABLE
CREATE TABLE IF NOT EXISTS `users` (
    `username` varchar(50) NOT NULL,
    `password` varchar(50) NOT NULL,
    `auth` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
TABLE;
	$setUniqueKey = <<<KEY
ALTER TABLE `users` ADD UNIQUE KEY `username` (`username`);
KEY;

	$testUser = array(
		'fields'=>array('username', 'password', 'auth'),
		'table'=>'users',
		'records'=>array(
			array('phil', 'pass', 'admin')
		)
	);

	$db->executeRaw($createTable);
	$db->executeRaw($setUniqueKey);

	$db->executeRaw($createTable);
	$db->executeRaw($setUniqueKey);
	$db->insert($testUser);
}