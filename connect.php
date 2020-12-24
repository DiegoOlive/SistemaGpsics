<?php //Realiza a Conexão com o banco

$host 			= "localhost";
$usr			= "id13981340_adminstrador";
$pass			= "Senha100-senha";
$bd				= "id13981340_estoquegpsics";

$connect 		= new mysqli($host,$usr,$pass,$bd) or die(mysqli_error());
?>