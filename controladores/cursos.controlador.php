<?php

class ControladorCursos
{

    public function index()
    {
        $cursos = ModeloCursos::index("cursos", null, null);
        if (isset($_GET["page"]) && isset($_GET["cantidad"])) {
            $desde = ($_GET["page"] - 1) * $_GET["cantidad"];
            $cursos = ModeloCursos::index("cursos", $_GET["cantidad"], $desde);
        } else {
            $cursos = ModeloCursos::index("cursos", null, null);
        }
        $json = array(
            "status" => 200,
            "total_registros" => count($cursos),
            "detalle" => $cursos,
        );

        echo json_encode($json, true);

        return;
    }


    public function create($datos)
    {
        /* Validar datos */
        foreach ($datos as $key => $valueDatos) {
            if (isset($valueDatos) && !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $valueDatos)) {
                $json = array(
                    "status" => 404,
                    "detalle" => "Error en el campo " . $key
                );
                echo json_encode($json, true);
                return;
            }
        }

        /* Validar que el titulo o la descripcion no estén repetidos */
        $cursos = ModeloCursos::index("cursos", "clientes", null, null);
        foreach ($cursos as $key => $value) {
            if ($value->titulo == $datos["titulo"]) {
                $json = array(
                    "status" => 404,
                    "detalle" => "El título ya existe en la base de datos"
                );
                echo json_encode($json, true);
                return;
            }
            if ($value->descripcion == $datos["descripcion"]) {
                $json = array(
                    "status" => 404,
                    "detalle" => "La descripción ya existe en la base de datos"
                );
                echo json_encode($json, true);
                return;
            }
        }

        /* Llevar datos al modelo */
        $datos = array(
            "titulo" => $datos["titulo"],
            "descripcion" => $datos["descripcion"],
            "instructor" => $datos["instructor"],
            "imagen" => $datos["imagen"],
            "precio" => $datos["precio"],
            "created_at" => date('Y-m-d h:i:s'),
            "updated_at" => date('Y-m-d h:i:s')
        );

        $create = ModeloCursos::create("cursos", $datos);

        /* Respuesta del modelo */
        if ($create == "ok") {
            $json = array(
                "status" => 200,
                "detalle" => "Registro exitoso, su curso ha sido guardado"
            );
            echo json_encode($json, true);
            return;
        } else {
            $json = array(
                "status" => 404,
                "detalle" => $create
            );
            echo json_encode($json, true);
            return;
        }
    }

    public function show($id)
    {

        $curso = ModeloCursos::show("cursos", $id);

        if (!empty($curso)) {

            $json = array(

                "status" => 200,
                "detalle" => $curso

            );

            echo json_encode($json, true);

            return;
        } else {

            $json = array(

                "status" => 200,
                "total_registros" => 0,
                "detalles" => "No hay ningún curso registrado"

            );

            echo json_encode($json, true);

            return;
        }
    }

    public function update($id, $datos)
    {
        /* Validar datos */
        foreach ($datos as $key => $valueDatos) {
            if (isset($valueDatos) && !preg_match('/^[(\\)\\=\\&\\$\\;\\-\\_\\*\\"\\<\\>\\?\\¿\\!\\¡\\:\\,\\.\\0-9a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $valueDatos)) {
                $json = array(
                    "status" => 404,
                    "detalle" => "Error en el campo " . $key
                );
                echo json_encode($json, true);
                return;
            }
        }

        /* Llevar datos al modelo */
        $datos = array(
            "id" => $id,
            "titulo" => $datos["titulo"],
            "descripcion" => $datos["descripcion"],
            "instructor" => $datos["instructor"],
            "imagen" => $datos["imagen"],
            "precio" => $datos["precio"],
            "updated_at" => date('Y-m-d h:i:s')
        );

        $update = ModeloCursos::update("cursos", $datos);

        if ($update == "ok") {
            $json = array(
                "status" => 200,
                "detalle" => "Registro exitoso, su curso ha sido actualizado"
            );
            echo json_encode($json, true);
            return;
        }
    }

    public function delete($id)
    {
        $delete = ModeloCursos::delete("cursos", $id);

        if ($delete == "ok") {
            $json = array(
                "status" => 200,
                "detalle" => "El curso ha sido eliminado"
            );
            echo json_encode($json, true);
            return;
        } else {
            $json = array(
                "status" => 404,
                "detalle" => "No se pudo eliminar el curso"
            );
            echo json_encode($json, true);
            return;
        }
    }
}
