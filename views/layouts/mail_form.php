<div class="login-form relative flex-center p-5">


  <form action="" method="post" class="w-100">
    <!-- E-mail -->
    <div class="form-control flex-col w-100 pb-5">
      <label class="form-label pb-3" for="email">Inserisci l'e-mail per il recupero</label>
      <input type="email" name="email" id="email" class="form-input" value="<?php echo @$_POST['email'];?>"
        placeholder="name@example.com" />
    </div>

    <p class="error">
      <?php echo @$res; ?>
    </p>
    <button type="submit" name="submit" class="btn w-100">Inoltra</button>

    <h3 class="cta">
      <a href="signup.php">Registrati</a> oppure <a href="index.php">Accedi</a>
    </h3>
  </form>

</div>