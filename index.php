<!DOCTYPE html>
<?php


require_once __DIR__ . "/src/Session/UserSession.php";
require_once __DIR__ . '/src/Database/Database.php';
require_once __DIR__ . "/cfg/cfg.php";
global $cfg;

try {
    $db = new Database($cfg);
    $pizzaParameters = array(
        'fields'=>array("*"),
        'table'=>$cfg['db']['prefix'] . "pizzas"
    );

    $allPizzaData = $db->select($pizzaParameters);

    $toppingsParameters = array(
        'fields' => array("*"),
        'table' => $cfg['db']['prefix'] . "toppings",
    );

    $allToppingsData = $db->select($toppingsParameters);
} catch(Exception $e) {
    $sessionPreLoggedInName = $sessionPostLoggedInName = $sessionPostLoggedInAuthorisation = $e->getMessage();
}
echo session_id() . "<br/>";
$user = new UserSession($db);
echo session_id();
$ErrorMessages = [];
$currentOrder = array();

if (!(isset($_SESSION['currentOrder']))){
    $_SESSION['currentOrder'] = array();
}

if ($_POST) {
    if (isset($_POST['login'])) {  //If they are logging in
        if (isset($_POST['username']) and isset($_POST['password'])) { //Make sure they filled in all required fields
            require_once __DIR__ . "/src/Validator/ValidatorSet.php";
            require_once __DIR__ . "/src/Validator/NameValidator.php";
            require_once __DIR__ . "/src/Validator/PasswordValidator.php";
            $vSet = new ValidatorSet();
            $name = new NameValidator($_POST['username'], true);
            $name->setLength(15);
            $vSet->addItem($name);
            $pass = new PasswordValidator($_POST['password'], true);
            $vSet->addItem($pass);


            $ErrorMessages = $vSet->getErrors();
            if (count($ErrorMessages) == 0) {
                $userParams = array(
                    'fields' => array("*"),
                    'table' => $cfg['db']['prefix'] . "users",
                    'conditions' => array('username' => $name->getSanitisedValue(), 'password' => $pass->getSanitisedValue()),
                    'operator' => "="
                );

                $userData = $db->select($userParams);
                if ($userData == false) {
                    $parameters['fields'] = array('username', 'password');
                    $parameters['table'] = $cfg['db']['prefix'] . 'users';
                    $parameters['records'] = array(array('username' => $name->getSanitisedValue(), 'password' => $pass->getSanitisedValue()));
                    $db->insert($parameters);
                }

                $user->logIn($name->getSanitisedValue(), $pass->getSanitisedValue());
            }
        }
    } elseif (isset($_POST['logoff'])) {
        $user->logOut();
    } elseif (isset($_POST['add-order'])){
        if (isset($_POST['pizza']) && isset($_POST['size'])){
            $toppings = array();

            if ($_POST['pizza'] == 5){
                if (isset($_POST['toppings'])){
                    $toppings = $_POST['toppings'];
                }
            }

            array_push($_SESSION['currentOrder'], array(
                'p_id'  => $_POST['pizza'],
                'p_size'  => $_POST['size'],
                'toppings' => $toppings
            ));
        }
    }
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SSS Task 3 - Pizza Site</title>
    <link rel="stylesheet" href="css/pizza-site.css">
</head>
<body>
<form method="post">
    <fieldset>
        <legend>Login</legend>
        <?php if ($user->isLoggedIn()){?>
            <button type="submit" name="logoff" formnovalidate>Log Off</button>
        <?php } else { ?>
            <label for="username">Username: </label>
            <input type="text" maxlength="15" required name="username">
            <?php if (count($ErrorMessages) > 0) { echo "<p>".$name->getError()."</p>"; } ?>
            <label for="password">Password: </label>
            <input type="password" maxlength="15" required name="password">
            <?php if (count($ErrorMessages) > 0) { echo "<p>".$pass->getError()."</p>"; } ?>
            <button type="submit" name="login" formnovalidate>Log In</button>
        <?php } ?>
    </fieldset>
</form>

<form method="post">
    <fieldset>
        <legend>Pizzas</legend>
        <?php
        foreach ($allPizzaData as $pizza){
            $toppingString = "";
            $toppings = json_decode($pizza['p_toppings']);
            foreach ($toppings as $topping) {
                $toppingParameters = array(
                    'fields' => array("*"),
                    'table' => $cfg['db']['prefix'] . "toppings",
                    'conditions' => array('t_id' => $topping),
                    'operator' => "="
                );

                $toppingData = $db->select($toppingParameters);
                foreach($toppingData as $t){
                    $toppingString .= $t['t_name'] . ", ";
                }


            }

            if ($toppingString != ""){
                $toppingString = " - " . substr($toppingString, 0, strlen($toppingString)-2);
            }


            echo '<p><input required id="pizza-' . $pizza['p_id'] . '" type="radio" name="pizza" value="' . $pizza['p_id'] . '" class="pizza-type"><label for="pizza-' . $pizza['p_id'] . '">' . $pizza['p_name'] . $toppingString . '</label><br/>Small - &pound;' . $pizza['p_small'] . ' | Medium - &pound;' . $pizza['p_medium'] . ' | Large - &pound;' . $pizza['p_large'] . '</p>';
        }
        ?>

        <fieldset class="tabbed">
            <legend>Choose Toppings</legend>
            <p>Only available with the Create Your Own Pizza option.</p>
            <?php

            foreach($allToppingsData as $topping){
                echo '<p><input type="checkbox" name="toppings[]" value="' . $topping['t_id'] . '">' . $topping['t_name'] . '</p>';
            }
            ?>

        </fieldset>
        <fieldset class="tabbed">
            <legend>Add to Order</legend>
            <?php
            $sizeString = '<p>Choose Size: <select name="size">';

            $sizeString .= '<option value="small">Small</option>';
            $sizeString .= '<option value="medium">Medium</option>';
            $sizeString .= '<option value="large">Large</option>';

            $sizeString .= '</select></p>';
            echo $sizeString;

            echo '<p><button type="submit" name="add-order" formnovalidate>Add</button></p>'
            ?>
        </fieldset>
    </fieldset>
</form>
<form method="post">
    <fieldset>
        <legend>Your Order</legend>

        <?php
        if ((isset($_SESSION['currentOrder']))){
            foreach ($_SESSION['currentOrder'] as $pizza){
                echo "<p>" . $allPizzaData[$pizza['p_id'] - 1]['p_name'] . " | " . $pizza['p_size'];
                if (count($pizza['toppings']) > 0){
                    echo " | ";
                    $tString = "";
                    foreach ($pizza['toppings'] as $topping){
                        $tString .= $allToppingsData[$topping-1]['t_name'] . ", ";
                    }
                    $tString = substr($tString, 0, strlen($tString) - 2);
                    echo $tString;
                }
                echo  " | &pound;" . $allPizzaData[$pizza['p_id'] - 1]['p_'.$pizza['p_size']] . "</p>";
            }
        }

        ?>
        <br/>
        <button type="submit" name="checkout" formnovalidate>Check out</button>
    </fieldset>
</form>


</body>
</html>