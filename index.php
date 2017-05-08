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
$user = new UserSession($db);
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SSS Task 3 - Pizza Site</title>
    <link rel="stylesheet" href="css/pizza-site.css">
</head>
<body>
<form method="post" name="add-order">
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


            echo '<p><input id="pizza-' . $pizza['p_id'] . '" type="radio" name="pizza" value="' . $pizza['p_id'] . '" class="pizza-type"><label for="pizza-' . $pizza['p_id'] . '">' . $pizza['p_name'] . $toppingString . '</label><br/>Small - &pound;' . $pizza['p_small'] . ' | Medium - &pound;' . $pizza['p_medium'] . ' | Large - &pound;' . $pizza['p_large'] . '</p>';
        }
        ?>

        <fieldset class="tabbed">
            <legend>Choose Toppings</legend>
            <?php

            foreach($allToppingsData as $topping){
                echo '<p><input type="checkbox" name="toppings" value="' . $topping['t_id'] . '">' . $topping['t_name'] . '</p>';
            }
            ?>
        </fieldset>
        <fieldset class="tabbed">
            <legend>Add to Order</legend>
            <?php
            $sizeString = '<p>Choose Size: <select id="pizza-small-' . $pizza['p_id'] . '">';

            $sizeString .= '<option value="small">Small</option>';
            $sizeString .= '<option value="medium">Medium</option>';
            $sizeString .= '<option value="large">Large</option>';

            $sizeString .= '</select></p>';
            echo $sizeString;

            echo '<p><button>Add</button></p>'
            ?>
        </fieldset>
    </fieldset>
</form>
</body>
</html>