<?php
require __DIR__ . "/config.php";

/**
 * DB connection
 */
function connect()
{
  $connection = new mysqli(SERVER, USERNAME, PASSWORD, DATABASE);

  /* check if there are errors in the connection and print errors in "db_log.txt" file  */

  /* error */
  if ($connection->connect_errno != 0) {
    $error = $connection->connect_errno;
    $error_data = date('F j, Y, g:i a');
    $message = "{$error} [{$error_data}] \r\n";
    file_put_contents("db_log.txt", $message, FILE_APPEND);
    return false;
  /* success  */
  } else {
    $connection->set_charset("utf8mb4");
    return $connection;
  }
}

/**
 * login user
 * @param string $email
 * @param string $password
 */
function login($email, $password)
{
  $connection = connect();

  $email = trim($email);
  $password = trim($password);

  if ($email == "" || $password == "")
    return "Campi obbligatori";

  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  $password = filter_var($password, FILTER_SANITIZE_STRING);

  $query = "SELECT email, password, nome, cognome FROM utenti WHERE email = ?";
  $stmt = $connection->prepare($query);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $res = $stmt->get_result();
  $data = $res->fetch_assoc();

  if ($data == NULL)
    return "Errore nelle credenziali inserite";

    /* check if psw match with hashed_psw */
  if (password_verify($password, $data["password"]) == FALSE)
    return "Errore nelle credenziali inserite";
  else {
    $_SESSION['email'] = $email;
    $_SESSION['name'] = $data["nome"];    
    header("location: account.php");
    exit();
  }
}

/**
 * @param string $email
 * @param string $name
 * @param string $lastname
 * @param string $password
 */
function register($email, $name, $lastname, $password)
{
  $connection = connect();
  /* user's input values */
  $args = func_get_args();

  /* array trimmed values */
  $args = array_map(function ($x) {
    return trim($x);
  }, $args);

  /* check empty fields */
  foreach ($args as $x) {
    if (empty($x)) {
      return "Compliare tutti i campi obbligatori";
    }
  }

  /* check if are this chars  */
  foreach ($args as $x) {
    if (preg_match("/([<|>])/", $x)) {
      return "Caratteri non consentiti";
    }
  }

  /* check email structure */
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "E-mail non valida";
  }

  /* check if email is already used */
  $stmt = $connection->prepare("SELECT email FROM utenti WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $res = $stmt->get_result();
  $data = $res->fetch_assoc();

  if ($data != NULL)
    return "E-mail inserita è già presente nel nostro sistema";

  if (strlen($name) > 45)
    return "Il campo nome è troppo lungo";

  if (strlen($lastname) > 45)
    return "Il campo cognome è troppo lungo";

  /* hashed password */
  $hashed_psw = password_hash($password, PASSWORD_DEFAULT);

  $stmt = $connection->prepare("INSERT INTO utenti (nome, cognome, email, password) VALUES (?,?,?,?)");
  $stmt->bind_param("ssss", $name, $lastname, $email, $hashed_psw);
  $stmt->execute();
  if ($stmt->affected_rows != 1)
    return "Si è verificato un errore, si prega di riprovare";
  else
    return "Registrazione avvenuta con successo";
}

/**
 * user logout
 */
function logout(){
  session_destroy();
  header("location: index.php");
  exit();
}

function get_events($email){
  $connection = connect();

  $res = mysqli_query($connection,"SELECT attendees, data_evento, nome_evento FROM eventi WHERE attendees LIKE '%$email%'" );

  $output = array();
  if (mysqli_num_rows($res) > 0) {
    while($row = mysqli_fetch_assoc($res)) {
      array_push($output,$row);
    }
  }
  return $output;
}