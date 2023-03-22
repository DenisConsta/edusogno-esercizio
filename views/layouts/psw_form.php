<div class="login-form relative flex-center p-5">

  <form action="" method="post" class="w-100" autocomplete="off">
    <!-- Password -->
    <div class="form-control flex-col w-100 pb-5 relative">
      <label class="form-label pb-3" for="password">Inserisci la nuova password</label>
      <input type="password" name="password" id="password"  class="form-input" value="<?php echo @$_POST['password'];?>"
        placeholder="Scrivila qui " />
        <i class="fa-solid fa-eye" id="eye"></i>
    </div>

    <p class="error">
      <?php echo @$res; ?>
    </p>
    <button type="submit" name="submit" class="btn w-100">Aggiorna</button>

    <h3 class="cta">
      <a href="signup.php">Registrati</a> oppure <a href="index.php">Accedi</a>
    </h3>
  </form>

</div>