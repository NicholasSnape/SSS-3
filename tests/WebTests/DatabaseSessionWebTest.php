<?php

require_once __DIR__ . '/../../src/Session/DatabaseSession.php';
require_once __DIR__ . '/../../src/Database/Database.php';

include __DIR__ . '/setUpDatabaseForSessions.php';


$sessionNotStarted = '';
$sessionStarted = '';
$sessionValue = '';

$cfg = array(
	'db'=>array(
		'host'=>'localhost',
		'user'=>'test',
		'pass'=>'test',
		'db'=>'test'
	)
);

try {
	$db = new Database($cfg);
} catch(Exception $e) {
	$sessionNotStarted = $sessionStarted = $sessionValue = $e->getMessage();
}


if($db) {
    setUpDatabaseForSessions($db);

	session_start();
	try {
		$sess = new DatabaseSession($db);
	} catch (Exception $e) {
		$sessionNotStarted = $e->getMessage();
	}
	session_destroy();

	try {
		$sess = new DatabaseSession($db);
		$sessionStarted = 'set_save_session_handler() executed.';
	} catch (Exception $e) {
		$sessionStarted = $e->getMessage();
	}

	session_start();

	$_SESSION['username'] = 'user';

	session_write_close();

    $sessionValue = $db->select(
        array(
            'fields' => array('sess_data'),
            'table' => 'sessions',
            'conditions' => array('sess_id' => session_id())
        )
    )[0]['sess_data'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Session Tests</title>
</head>
<body>
<dl>
    <dt>Session already started test</dt>
    <dd><?php echo $sessionNotStarted; ?></dd>
    <dt>Session handler changed test</dt>
    <dd><?php echo $sessionStarted; ?></dd>
    <dt>Session value in database test</dt>
    <dd><?php echo $sessionValue; ?></dd>
</dl>

</body>
</html>