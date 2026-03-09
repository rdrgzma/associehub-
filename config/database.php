<?php

class Database {
    private static $instance = null;

    public static function getConnection() {
        if (self::$instance === null) {
            try {
                $dbPath = __DIR__ . '/../database/database.sqlite';
                $isNew = !file_exists($dbPath) || filesize($dbPath) === 0;

                self::$instance = new PDO('sqlite:' . $dbPath);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                if ($isNew) {
                    $schemaPath = __DIR__ . '/../database/schema.sql';
                    if (file_exists($schemaPath)) {
                        $schema = file_get_contents($schemaPath);
                        self::$instance->exec($schema);
                        
                        $hashed = password_hash('admin', PASSWORD_DEFAULT);
                        $sql = "INSERT INTO admins (nome, email, senha) VALUES ('Administrador', 'admin@admin.com', :senha)";
                        $stmt = self::$instance->prepare($sql);
                        $stmt->execute(['senha' => $hashed]);
                    }
                }
            } catch (PDOException $e) {
                die("Database Connection error: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
