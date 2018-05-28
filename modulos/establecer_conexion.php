<?php

function establecer_conexion(){
    $servername = 'db683664347.db.1and1.com';
    $username = 'dbo683664347';
    $password = 'Felicidad=Amor2410';
    $dbname = 'db683664347';
    $prt = '3306';	
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    return $conn;
}
?>