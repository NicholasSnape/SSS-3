<?php

require_once __DIR__ . '/../../src/Session/UserSession.php';
require_once __DIR__ . '/../../src/Database/Database.php';

include __DIR__ . '/setUpDatabaseForSessions.php';


$sessionLoggedInName = '';
$sessionLoggedInAuthorisation = '';
$sessionLoggedOutName = '';

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
	$sessionLoggedOutName = $sessionLoggedInName = $sessionLoggedInAuthorisation = $e->getMessage();
}

if($db) {
    $user = new UserSession($db);

    if($user->isLoggedIn()) {
		$sessionLoggedInName = $user->username();
		$sessionLoggedInAuthorisation = $user->authorisation();

		$user->logOut();
	} else {
		if($user->logIn('phil', 'pass')) {
			$sessionLoggedInName = 'Logged in this page: ' . $user->username();
			$sessionLoggedInAuthorisation = 'Logged in this page: ' . $user->authorisation();

			$user->logOut();
		} else {
			$sessionLoggedInName = "Failed to log in";
		}
    }
    $sessionLoggedOutName = $user->username();
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
    <dt>Logged in username test</dt>
    <dd><?php echo $sessionLoggedInName; ?></dd>
    <dt>Logged in authorisation test</dt>
    <dd><?php echo $sessionLoggedInAuthorisation; ?></dd>
    <dt>Logged out test</dt>
    <dd>Nothing should appear here: <?php echo $sessionLoggedOutName; ?></dd>
</dl>

<a href="UserSessionTestPage1.php">Go to first page</a>

</body>
</html>