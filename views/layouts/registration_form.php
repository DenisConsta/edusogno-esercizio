<div class="login-form relative flex-center p-5">

  <form action="" method="post" class="w-100 ">

    <!-- first name -->
    <div class="form-control flex-col w-100 pb-5">
      <label class="form-label pb-3" for="name">Inserisci il tuo nome</label>
      <input type="text" id="name" name="name" class="form-input" value="<?php echo @$_POST['name']; ?>" placeholder="Mario" />
    </div>

    <!-- last name -->
    <div class="form-control flex-col w-100 pb-5">
      <label class="form-label pb-3" for="lastname">Inserisci il tuo cognome</label>
      <input type="text" id="lastname" name="lastname" class="form-input" value="<?php echo @$_POST['lastname']; ?>"
        placeholder="Rossi" />
    </div>

    <!-- E-mail -->
    <div class="form-control flex-col w-100 pb-5">
      <label class="form-label pb-3" for="email">Inserisci l'e-mail</label>
      <input type="email" id="email" name="email" class="form-input" value="<?php echo @$_POST['email']; ?>"
        placeholder="name@example.com" />
    </div>

    <!-- Password -->
    <div class="form-control flex-col w-100 pb-5">
      <label class="form-label pb-3" for="password">Inserisci la password</label>
      <input type="password" id="password" name="password" class="form-input" value="<?php echo @$_POST['password']; ?>"
        placeholder="Scrivila qui" />
    </div>

    <button type="submit" name="submit" class="btn w-100">Registrati</button>
    <p class="error">
      <?php echo @$res; ?>
    </p>

    <h3 class="cta">
      Hai gi&agrave; un account? <a href="./index.php">Accedi</a>
    </h3>

    <?php
    if (@$response == "success") {
      ?>
      <p class="success">Your registration was successful</p>
      <?php
    } else {
      ?>
      <p class="error">
        <?php echo @$response; ?>
      </p>
      <?php
    }
    ?>

  </form>
</div>