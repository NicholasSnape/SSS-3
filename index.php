<!DOCTYPE html>
<html lang="en">
<?php
    require_once __DIR__ . "../cfg/cfg.php";
    require_once __DIR__ . '/src/Database/Database.php';
    global $cfg;

    try{
        $db = new Database($cfg);
    } catch(Exception $e){

    }

?>
<head>
    <meta charset="UTF-8">
    <title>Server Side Scripting | Pizza Website</title>
    <link rel="stylesheet" href="css/pizza-site.css">
</head>
<body>

<fieldset>
    <legend>Choose Pizza</legend>
    <select>
        <?php

        ?>
    </select>
    <p style="display: none">Option 2</p>
</fieldset>

<fieldset>
    <legend>Toppings</legend>
    <select>

    </select>
</fieldset>

<fieldset id="checkout">
    <legend>Checkout</legend>
    <select>

    </select>
</fieldset>



</body>
</html>