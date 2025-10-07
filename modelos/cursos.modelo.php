<?php

require_once __DIR__ . '/conexion.php';

class ModeloCursos
{
    public static function index($tabla, $cantidad = null, $desde = null)
    {
        try {
            $stmt = Conexion::conectar()->prepare(
                "SELECT * FROM $tabla" . 
                ($cantidad !== null && $desde !== null ? " LIMIT :desde, :cantidad" : "")
            );

            if ($cantidad !== null && $desde !== null) {
                $stmt->bindParam(":desde", $desde, PDO::PARAM_INT);
                $stmt->bindParam(":cantidad", $cantidad, PDO::PARAM_INT);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return "error: " . $e->getMessage();
        }
    }

    static public function create($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO cursos (titulo, descripcion, instructor, imagen, precio, id_creador, created_at, updated_at) 
VALUES (:titulo, :descripcion, :instructor, :imagen, :precio, :id_creador, :created_at, :updated_at)");

        $stmt->bindParam(":titulo", $datos["titulo"], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
        $stmt->bindParam(":instructor", $datos["instructor"], PDO::PARAM_STR);
        $stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
        $stmt->bindParam(":precio", $datos["precio"], PDO::PARAM_STR);
        $stmt->bindParam(":id_creador", $datos["id_creador"], PDO::PARAM_INT);
        $stmt->bindParam(":created_at", $datos["created_at"], PDO::PARAM_STR);
        $stmt->bindParam(":updated_at", $datos["updated_at"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return Conexion::conectar()->errorInfo();
        }

        $stmt = null;
    }

    static public function show($tabla1, $id)
    {

        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla1 WHERE $tabla1.id=:id");

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function update($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("UPDATE cursos SET titulo=:titulo, descripcion=:descripcion, instructor=:instructor, imagen=:imagen, 
        precio=:precio, updated_at=:updated_at WHERE id = :id");

        $stmt->bindParam(":id", $datos["id"], PDO::PARAM_STR);
        $stmt->bindParam(":titulo", $datos["titulo"], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
        $stmt->bindParam(":instructor", $datos["instructor"], PDO::PARAM_STR);
        $stmt->bindParam(":imagen", $datos["imagen"], PDO::PARAM_STR);
        $stmt->bindParam(":precio", $datos["precio"], PDO::PARAM_STR);
        $stmt->bindParam(":updated_at", $datos["updated_at"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            print_r(Conexion::conectar()->errorInfo());
        }
        $stmt = null;
    }


    static public function delete($tabla, $id)
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id = :id");

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            print_r(Conexion::conectar()->errorInfo());
        }
        $stmt = null;
    }
}
