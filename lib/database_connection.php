<?php
$servername = "127.0.0.1:3307";
$database = "u479355679_exchange";
$username = "root";
$password = "";

// Create connection
global $conn;
$conn = new mysqli($servername, $username, $password,$database);
$conn->query("SET NAMES 'utf8'");
//Checa se a conexão obteve sucesso
if ($conn->connect_error){
  die("Connection Error:" .$conn->connect_error);
  } else {
  //echo "Conectado";
  }

?>
