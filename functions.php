<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

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
    return "l'e-mail inserita è già presente nel nostro sistema";

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
function logout()
{
  session_destroy();
  header("location: index.php");
  exit();
}

/* return user's events from DB */
function get_events($email)
{
  $connection = connect();

  $res = mysqli_query($connection, "SELECT DISTINCT data_evento, nome_evento FROM eventi WHERE attendees LIKE '%$email%'");

  $output = array();
  if (mysqli_num_rows($res) > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
      array_push($output, $row);
    }
  }  
  return $output;
}

/**
 * send email reset password with PHPMailer
 * @param string $email
 */
function sendResetMail($email)
{
  $connection = connect();
  $exist = mysqli_query($connection,"SELECT email FROM utenti WHERE email = '$email'");
  $exist = mysqli_fetch_assoc($exist);
  if($exist == NULL){
    header("location: index.php");
    $_SESSION['message'] = "L'e-mail inserita non &egrave; presente nel nostro sistema";
    exit();
  }

  $code = uniqid(true);
  $query = mysqli_query($connection, "INSERT INTO pswreset (code, email) VALUES ('$code', '$email')");
  if (!$query) {
    header("location: index.php");
    $_SESSION['message'] = "Errore nella generazione della richiesta, riprovare";
    exit();
  }

  $mail = new PHPMailer;

  $mail->isSMTP();
  $mail->SMTPAuth = true;
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPSecure = 'ssl';
  $mail->Port = 465;
  //$mail->SMTPDebug = SMTP::DEBUG_SERVER;

  $mail->Username = "tt4973684@gmail.com";
  $mail->Password = "klgzfgheazlsvzxg";

  $mail->setFrom('tt4973684@gmail.com', 'Edusogno');
  $mail->addAddress($email);
  $mail->isHTML();
  $mail->addReplyTo('no-reply.tt4973684@gmail.com', 'No reply');

  $url = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]) . "/reset_psw.php?code=$code";
  $mail->Subject = "Recupero password Edusogno";
  $mail->Body = "Per il reset della password clicca qui <a href='$url'>link</a>";

  if ($mail->send())
    return "L'email di recupero è stata inoltrata, controlla la casella di posta";
  else
    return "Si sono verificati degli errori, riprova.";
}

/* check the code  */
function verifyCode()
{
  $connection = connect();
  if (!isset($_GET['code']))
    exit("Page not found :(");

  $code = $_GET['code'];
  $get_email = mysqli_query($connection, "SELECT email FROM pswreset WHERE code = '$code'");
  if (mysqli_num_rows($get_email) == 0)
    exit("Page not found :(");
}

/* update user's password */
function updatePassword()
{
  $connection = connect();
  if (isset($_POST['password'])) {
    $psw = $_POST['password'];
    $hashed_psw = password_hash($psw, PASSWORD_DEFAULT);

    $code = $_GET['code'];
    $get_email = mysqli_query($connection, "SELECT email FROM pswreset WHERE code = '$code'");
    $get_email = mysqli_fetch_assoc($get_email)['email'];

    $query = mysqli_query($connection, "UPDATE utenti SET password = '$hashed_psw' WHERE email = '$get_email'");

    if ($query) {
      $query = mysqli_query($connection, "DELETE FROM pswreset WHERE code = '$code'");
      header("location: index.php");
      $_SESSION['message'] = "Password aggiornata correttamente";
      exit();

    }
  }
}