<div class="login-form relative flex-center p-5">

  <?php
    if (isset($_POST['submit'])) {
      $email = $_POST['email'];
      $password = $_POST['password'];
    }
  ?>

  <form action="" method="post" class="w-100 " >

    <!-- E-mail -->
    <div class="form-control flex-col w-100 pb-5">
      <label class="form-label pb-3" for="email">Inserisci l'e-mail</label>
      <input type="email" name="email" id="email" class="form-input" value="<?php echo @$_POST['email']; ?>"
        placeholder="name@example.com" />
    </div>

    <!-- Password -->
    <div class="form-control flex-col w-100 pb-5">
      <label class="form-label pb-3" for="password">Inserisci la password</label>
      <input type="password" name="password" id="password"  class="form-input" value="<?php echo @$_POST['password'];?> "
        placeholder="Scrivila qui " />
    </div>
    
    <p class="error">
      <?php echo @$res;?>
    </p>
    <button type="submit" name="submit" class="btn w-100">Accedi</button>

    <h3 class="cta">
      Non hai ancora un profilo? <a href="signup.php">Registrati</a>
    </h3>

    <h3 class="cta">Password dimenticata ? <a href="#">Reset password</a> </h3>
  </form>
</div>