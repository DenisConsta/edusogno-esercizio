<?php
require __DIR__ . "/functions.php";
if (!isset($_SESSION['name'])) {
  header("location: index.php");
  exit();
}

if (isset($_GET['logout']))
  logout();
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
  <title>Pagina personale</title>
</head>

<body>
  <?php include __DIR__ . './views/partials/header.php' ?>
  <?php include __DIR__ . './views/partials/background.php' ?>

  <main>

    <div class="container flex-center pt-5 flex-col relative">
      <h3 class="title pb-3">Ciao <span class="uppercase">
          <?php echo $_SESSION['name'] ?>
        </span> ecco i tuoi eventi
      </h3>

      <?php include __DIR__ . './views/layouts/events_list.php' ?>
      <a href="?logout" class="btn logout"> Logout </a>
    </div>

  </main>
</body>

</html>