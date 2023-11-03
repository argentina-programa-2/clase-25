<?php

class API
{
    private $bd;
    private $data = array();

    public function __construct($bd)
    {
        $this->bd = $bd;
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'GET':
                $this->getRequest();
                break;
            case 'POST':
                $this->postRequest();
                break;
            case 'PUT':
                $this->putRequest();
                break;
            case 'DELETE':
                $this->deleteRequest();
                break;
            default:
                $this->respond(405, 'Método no permitido');
                break;
        }
    }

    private function getRequest()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM productos WHERE id_producto = $id";
        } else {
            $sql = "SELECT * FROM productos";
        }
        $result = $this->bd->ejecutarConsulta($sql);
        if (count($result) > 0) {
            $this->respond(200, "Consulta realizada con exito!", $result);
        } else {
            $this->respond(200, "", $result);
        }
    }

    private function postRequest()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $nombre = $data['nombre_producto'];
        $descripcion = $data['descripcion_producto'];
        $precio = $data['precio_producto'];
        $sql = "INSERT INTO productos (nombre_producto, descripcion_producto, precio_producto) VALUES ('$nombre', '$descripcion', '$precio')";

        if ($this->bd->ejecutarConsulta($sql)) {
            $this->respond(200, 'Producto creado correctamente!');
        } else {
            $this->respond(200, '');
        }
    }

    private function putRequest()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id_producto'];
        $nombre = $data['nombre_producto'];
        $descripcion = $data['descripcion_producto'];
        $precio = $data['precio_producto'];
        $sql = "UPDATE productos SET nombre_producto='$nombre', descripcion_producto='$descripcion', precio_producto='$precio' WHERE id_producto = $id";
        if ($this->bd->ejecutarConsulta($sql) === TRUE) {
            $this->respond(200, 'Producto modificado correctamente!');
        } else {
            $this->respond(200, 'Error.');
        }
    }

    private function deleteRequest()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id_producto'];
        $sql = "DELETE FROM productos WHERE id_producto = '$id'";
        if ($this->bd->ejecutarConsulta($sql) === TRUE) {
            $this->respond(200, 'Producto borrado correctamente!');
        } else {
            $this->respond(200, 'Solicitud DELETE manejada.');
        }
    }

    private function respond($status, $message, $array = null)
    {
        header("HTTP/1.1 $status");
        header('Content-Type: application/json');
        if ($array == null) {
            $response = array('status' => $status, 'message' => $message);
        } else {
            $response = array('status' => $status, 'message' => $message, 'personas' => $array);
        }
        echo json_encode($response);
    }
}
