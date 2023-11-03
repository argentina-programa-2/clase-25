<?php
include("./api.php");
header("Content-Type: application/json");

$ep = $_SERVER['REQUEST_URI'];
require_once('db.php');
$bd = new BaseDeDatos("localhost", "root", "", "api");
$bd->conectar();
switch ($ep) {
    case '/clase25/api/':
        $api = new API($bd);

        $api->handleRequest();
        break;

    default:
        echo json_encode(array("msg" => 'Ruta no encontrada'));
        break;
}

$bd->desconectar();
