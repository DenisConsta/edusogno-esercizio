<?php
require __DIR__ . "/config.php";

/**
 * connessione al DB
 */
function connect()
{
  $connection = new mysqli(SERVER, USERNAME, PASSWORD, DATABASE);

  /* controlla se vi sono errori nella connessione e in caso stampa nel file "db_log.txt" l'errore  */

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

  /* uncomment after registration */

 /*  
  if ($data == NULL)
    return "Errore nelle credenziali inserite";

  if (password_verify($password, $data["password"]) == FALSE)
    return "Errore nelle credenziali inserite";
  else {
    $_SESSION['email'] = $email;
    $_SESSION['name'] = $data["nome"];
    
    header("location: account.php");

    exit();
  } */

  /* TEMP */

  if($password == $data["password"]){
    $_SESSION['email'] = $email;
    header("location: pages/account.php");

    exit();
  }else return "Errore nelle credenziali inserite";

}