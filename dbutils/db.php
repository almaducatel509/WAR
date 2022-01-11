<?php

define("DB_SERVER", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "chcl");

/*** mysql hostname ***/

function db_connect() {
    $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if(mysqli_connect_errno()) {
      $msg = "Connection échoué: ";
      $msg .= mysqli_connect_error();
      $msg .= " (" . mysqli_connect_errno() . ")";
      exit($msg);
    }
    return $connection;
  }

  function db_query($connection, $sql) {
    $result_set = mysqli_query($connection, $sql);
    echo $result_set. "jj";

    return $result_set;
  }

  function confirm_query($result_set) {
    if(!$result_set) {
      exit("Database query failed.");
    }
  }

  function db_fetch_assoc($result_set) {
    return mysqli_fetch_assoc($result_set);
  }

  function db_free_result($result_set) {
    return mysqli_free_result($result_set);
  }

  function db_num_rows($result_set) {
    return mysqli_num_rows($result_set);
  }

  function db_insert_id($connection) {
    return mysqli_insert_id($connection);
  }

  function db_error($connection) {
    return mysqli_error($connection);
  }

  function db_close($connection) {
    return mysqli_close($connection);
  }

?>