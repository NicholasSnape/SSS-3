<?php

require_once __DIR__ . '/../../src/Session/UserSession.php';
require_once __DIR__ . '/../../src/Database/Database.php';

include __DIR__ . '/setUpDatabaseForSessions.php';


$sessionPreLoggedInName = '';
$sessionPostLoggedInName = '';
$sessionPostLoggedInAuthorisation = '';

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
	$sessionPreLoggedInName = $sessionPostLoggedInName = $sessionPostLoggedInAuthorisation = $e->getMessage();
}

if($db) {
    setUpDatabaseForSessions($db);

    $user = new UserSession($db);

    $sessionPreLoggedInName = $user->username();

    if($user->logIn('phil', 'pass')) {

		$sessionPostLoggedInName = $user->username();
		$sessionPostLoggedInAuthorisation = $user->authorisation();
	} else {
        $sessionPostLoggedInName = "Failed to log in";
    }
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
    <dt>Session started but not logged in test</dt>
    <dd>Nothing should appear here: <?php echo $sessionPreLoggedInName; ?></dd>
    <dt>Logged in username test</dt>
    <dd><?php echo $sessionPostLoggedInName; ?></dd>
    <dt>Logged in authorisation test</dt>
    <dd><?php echo $sessionPostLoggedInAuthorisation; ?></dd>
</dl>

<a href="UserSessionTestPage2.php">Go to second page</a>

</body>
</html>