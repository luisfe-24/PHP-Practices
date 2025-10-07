<?php

class ControladorClientes
{

    public function create($datos)
    {
        // Validate required fields
        if (!isset($datos["nombre"], $datos["apellido"], $datos["email"])) {
            $json = array(
                "status" => 400,
                "detalle" => "Faltan campos obligatorios"
            );
            echo json_encode($json, true);
            return;
        }

        //Validar nombre
        if (isset($datos["nombre"]) && !preg_match("/^[a-zA-ZáéíóúÁÉÍÚñÑ ]+$/", $datos["nombre"])) {
            $json = array(
                "status" => 404,
                "detalle" => "error en el nombre, permitido solo letras",
            );

            echo json_encode($json, true);

            return;
        }

        //Validar apellido
        if (isset($datos["apellido"]) && !preg_match("/^[a-zA-ZáéíóúÁÉÍÚñÑ ]+$/", $datos["apellido"])) {
            $json = array(
                "status" => 404,
                "detalle" => "error en el apellido, permitido solo letras",
            );

            echo json_encode($json, true);

            return;
        }

        //Validar email
        if (
            isset($datos["email"])
            && !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $datos["email"])
        ) {
            $json = array(
                "status" => 404,
                "detalle" => "error en el email",
            );

            echo json_encode($json, true);

            return;
        }

        //Validar email repetido
        $clientes = ModeloClientes::index("clientes");

        foreach ($clientes as $key => $value) {
            if ($value["email"] == $datos["email"]) {
                $json = array(
                    "status" => 404,
                    "detalle" => "error, el email ya existe",
                );

                echo json_encode($json, true);

                return;
            }
        }

        /*Generar credenciales de cliente*/

        $id_cliente = str_replace("$", "c", crypt(
            $datos["nombre"] . $datos["apellido"] . $datos["email"],
            '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$'
        ));

        $llave_secreta = str_replace("$", "a", crypt(
            $datos["email"] . $datos["apellido"] . $datos["nombre"],
            '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$'
        ));

        $datos["id_cliente"] = $id_cliente;
        $datos["llave_secreta"] = $llave_secreta;
        $datos["created_at"] = date("Y-m-d H:i:s");
        $datos["updated_at"] = date("Y-m-d H:i:s");

        $create = ModeloClientes::create("clientes", $datos);

        if ($create == "ok") {
            $json = array(
                "status" => 201,
                "detalle" => "Cliente creado exitosamente",
                "id_cliente" => $id_cliente
            );
        } else {
            $json = array(
                "status" => 500,
                "detalle" => "Error al intentar crear el cliente"
            );
        }

        echo json_encode($json, true);
    }

    public function show($id)
    {
        $cliente = ModeloClientes::show("clientes", $id);

        if (!$cliente) {
            $json = array(
                "status" => 404,
                "detalle" => "Cliente no encontrado"
            );
        } else {
            $json = array(
                "status" => 200,
                "detalle" => $cliente
            );
        }

        echo json_encode($json, true);
    }

    public function update($id, $datos)
    {
        // Validate required fields
        if (!isset($datos["nombre"], $datos["apellido"], $datos["email"])) {
            $json = array(
                "status" => 400,
                "detalle" => "Faltan campos obligatorios"
            );
            echo json_encode($json, true);
            return;
        }

        // Validate name
        if (isset($datos["nombre"]) && !preg_match("/^[a-zA-ZáéíóúÁÉÍÚñÑ ]+$/", $datos["nombre"])) {
            $json = array(
                "status" => 404,
                "detalle" => "Error en el nombre, permitido solo letras"
            );
            echo json_encode($json, true);
            return;
        }

        // Validate last name
        if (isset($datos["apellido"]) && !preg_match("/^[a-zA-ZáéíóúÁÉÍÚñÑ ]+$/", $datos["apellido"])) {
            $json = array(
                "status" => 404,
                "detalle" => "Error en el apellido, permitido solo letras"
            );
            echo json_encode($json, true);
            return;
        }

        // Validate email
        if (
            isset($datos["email"]) &&
            !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $datos["email"])
        ) {
            $json = array(
                "status" => 404,
                "detalle" => "Error en el email"
            );
            echo json_encode($json, true);
            return;
        }

        // Generate llave_secreta if not provided
        if (!isset($datos["llave_secreta"]) || empty($datos["llave_secreta"])) {
            $datos["llave_secreta"] = str_replace("$", "a", crypt(
                $datos["email"] . $datos["apellido"] . $datos["nombre"],
                '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$'
            ));
        }

        // Update client in the database
        $datos["updated_at"] = date("Y-m-d H:i:s");
        $update = ModeloClientes::update("clientes", $datos, $id);

        if ($update == "ok") {
            $json = array(
                "status" => 200,
                "detalle" => "Cliente actualizado correctamente"
            );
        } else {
            $json = array(
                "status" => 500,
                "detalle" => "Error al intentar actualizar el cliente"
            );
        }

        echo json_encode($json, true);
    }

    public function delete($id)
    {
        $delete = ModeloClientes::delete("clientes", $id);

        if ($delete == "ok") {
            $json = array(
                "status" => 200,
                "detalle" => "El cliente ha sido eliminado correctamente"
            );
        } else {
            $json = array(
                "status" => 404,
                "detalle" => "Error al intentar eliminar el cliente"
            );
        }

        echo json_encode($json, true);
    }
    public function index()
    {
        $clientes = ModeloClientes::index("clientes");

        if (empty($clientes)) {
            $json = array(
                "status" => 404,
                "detalle" => "No hay clientes registrados",
            );

            echo json_encode($json, true);

            return;
        }

        $json = array(
            "status" => 200,
            "total_registros" => count($clientes),
            "detalle" => $clientes,
        );

        echo json_encode($json, true);
    }
}
