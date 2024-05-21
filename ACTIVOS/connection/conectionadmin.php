<?php 

require_once('/home1/mizzaspa/public_html/administrador/Connection/global/ServerConfiguration.php');

try{
    $connection= new PDO('mysql:host='.SERVER.';dbname='.DB,USER, PASSWORD);
}
catch(Exception $e)
{
    die($e->getMessage());
}
?>