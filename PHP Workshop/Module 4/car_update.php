<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=AUTOS', 'Vishwas', 'vp@123');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$valid_request = false;
$car_details = array();

if (isset($_GET["car_id"])) {
    $valid_request = true;

    $sql = "SELECT * FROM CARS where car_id = :car_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(":car_id" => $_GET["car_id"]));
    $car_details = $stmt->fetch(PDO::FETCH_ASSOC);

    //
    $flag = false;
    $message = "";
    if (isset($_POST["submit"])) {
        if (isset($_POST["car_name"]) && 
            isset($_POST["car_company"]) &&
            isset($_POST["car_year"]) && 
            isset($_POST["car_price"])) {
 
            if (strlen($_POST["car_name"]) == 0) {
                $message = "Enter car name";
            } elseif (strlen($_POST["car_company"]) == 0) {
                $message = "Enter car company name";
            } elseif (strlen($_POST["car_year"]) == 0) {
                $message = "Enter car make year";
            } elseif (!is_numeric($_POST["car_year"])) {
                $message = "Enter valid car make year";
            } elseif (strlen($_POST["car_price"]) == 0) {
                $message = "Enter car price";
            } elseif (!is_numeric($_POST["car_price"])) {
                $message = "Enter valid car price";
            } else {
                $message = "Valid Data";
                $flag = true;
            }
        } else {
            $flag = false;
            $message = "Data not set";
        }
    }
}

if($valid_request === true && $flag === true) {
    //Data Updation
    $sql = "UPDATE CARS SET car_name = :car_name, car_company = :car_company, car_year = :car_year, car_price = :car_price WHERE car_id = :car_id;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ":car_name" => $_POST["car_name"],
        ":car_company" => $_POST["car_company"],
        ":car_year" => $_POST["car_year"],
        ":car_price" => $_POST["car_price"],
        ":car_id" => $_GET["car_id"],
    ));

    header("location: index1.php");
    return;
}
?>

<html>

    <head></head>
    <body>

        <?php if ($valid_request) : ?>
            <?php if ($car_details) : ?>
                <form method="post">
                    <label for="car_name">Enter Car Name: </label>
                    <input type="text" name="car_name" id="car_name" value=<?= $car_details["car_name"] ?>>
                    <br>

                    <label for="car_company">Enter Company Name: </label>
                    <input type="text" name="car_company" id="car_company" value=<?= $car_details["car_company"] ?>>
                    <br>

                    <label for="car_year">Enter Make Year: </label>
                    <input type="number" name="car_year" id="car_year" value=<?= $car_details["car_year"] ?>>
                    <br>

                    <label for="car_price">Enter Car Price: </label>
                    <input type="number" name="car_price" id="car_price" value=<?= $car_details["car_price"] ?>>
                    <br>

                    <input type="submit" name="submit" value="Update">
                </form>
            
            <?php if($flag === true): ?>
                <h5 style="color: green;"><?= $message ?></h5>
            <?php else : ?>
                <h5 style="color: red;"><?= $message ?></h5>
            <?php endif ?>
            <?php else : ?>
                <h1 style="color: red;">Car details not found for <?= htmlentities($_GET["car_id"]) ?></h1>
            <?php endif ?>
        <?php else : ?>
            <h1 style="color: red;">Invalid Request URL - ID not found.</h1>
        <?php endif ?>

    </body>
</html>