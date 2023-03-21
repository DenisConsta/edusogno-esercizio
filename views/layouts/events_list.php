<?php
$events = get_events($_SESSION['email']);

?>

<div class="container">
  <div class="row p-5">

    <?php
    foreach ($events as $event) {
      ?>

      <div class="card flex-col">
        <h3 class="event_title">
          <?php echo $event['nome_evento'] ?>
        </h3>
        <h4 class="event_date">
          <?php echo substr_replace($event['data_evento'], "", -3)?>
        </h4>
        <button class="btn w-100">Join</button>
      </div>

      <?php
    }
    ?>

  </div>
</div>

<style>
  .container {
    z-index: 10;
  }
</style>