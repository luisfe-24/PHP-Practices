<?php

$arrayRutas = explode("/", $_SERVER['REQUEST_URI']);

$routesLength = count($arrayRutas);

//Evaluando si el último elemento de la ruta es un número para las peticiones GET, PUT y DELETE
if (gettype((int)$arrayRutas[$routesLength - 1]) == "integer") {

    if ($arrayRutas[$routesLength - 2] == "cursos") {
        $id = $arrayRutas[$routesLength - 1];

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $cursos = new ControladorCursos();
                $cursos->show($id);
                break;
            case 'PUT':
                $datos = array();
                parse_str(file_get_contents('php://input'), $datos);
                $cursos = new ControladorCursos();
                $cursos->update($id, $datos);
                break;
            case 'DELETE':
                $cursos = new ControladorCursos();
                $cursos->delete($id);
                break;
        }
    }

    if ($arrayRutas[$routesLength - 2] == "clientes") {
        $id = $arrayRutas[$routesLength - 1];

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $clientes = new ControladorClientes();
                $clientes->show($id); // Retrieve a specific client by ID
                break;
            case 'PUT':
                // Parse form-encoded data
                parse_str(file_get_contents('php://input'), $datos);
                $clientes = new ControladorClientes();
                $clientes->update($id, $datos);
                break;
            case 'DELETE':
                $clientes = new ControladorClientes();
                $clientes->delete($id);
                break;
        }
    }
}

//Evaluando si el último elemento de la ruta es un string para las peticiones POST y GET
if ($arrayRutas[$routesLength - 1] == "cursos") {
    $id = $arrayRutas[$routesLength - 1];

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $cursos = new ControladorCursos();
            $cursos->index($id);
            break;
        case 'POST':
            $datos = array();
            parse_str(file_get_contents('php://input'), $datos);
            $cursos = new ControladorCursos();
            $cursos->create($datos);
            break;
    }
}

if ($arrayRutas[$routesLength - 1] == "clientes") {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $clientes = new ControladorClientes();
            $clientes->index(); // Retrieve all clients
            break;
        case 'POST':
            // Parse form-encoded data
            $datos = $_POST;
            $clientes = new ControladorClientes();
            $clientes->create($datos);
            break;
    }
}
