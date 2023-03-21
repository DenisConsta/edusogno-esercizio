<?php
require __DIR__ . "/functions.php";
if (isset($_POST['submit'])) {
    $res = register($_POST['email'], $_POST['name'], $_POST['lastname'], $_POST['password']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/styles/style.css">
    <!-- FontAwesome cdn -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.css'
        integrity='sha512-+ouAqATs1y4kpPMCHfKHVJwf308zo+tC9dlEYK9rKe7kiP35NiP+Oi35rCFnc16zdvk9aBkDUtEO3tIPl0xN5w=='
        crossorigin='anonymous' />
    <title>Sign Up</title>
</head>

<body>
    <?php include __DIR__ . '../views/partials/header.php' ?>
    <?php include __DIR__ . '../views/partials/background.php' ?>

    <main>
        <div class="container flex-center">
            <div class="form-container pt-5">
                <h2 class="title pb-3">Crea il tuo account</h2>
                <?php include __DIR__ . './views/layouts/registration_form.php' ?>
            </div>
        </div>
    </main>

    <script src="./assets/js/script.js"></script>
</body>

</body>

</html>