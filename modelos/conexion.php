<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Ensure Composer's autoload is included

use Dotenv\Dotenv;

class Conexion
{
    public static function conectar()
    {
        // Load environment variables
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        try {
            $host = $_ENV['DB_HOST'];
            $dbname = $_ENV['DB_NAME'];
            $user = $_ENV['DB_USER'];
            $password = $_ENV['DB_PASSWORD'];

            $link = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $link;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}
