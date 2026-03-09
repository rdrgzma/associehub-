<?php

class Admin extends Model {
    public function authenticate($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM admins WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['senha'])) {
            return $admin;
        }
        return false;
    }

    public function createDefaultAdmin() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) as count FROM admins");
            $row = $stmt->fetch();
            if ($row['count'] == 0) {
                $hashed = password_hash('admin', PASSWORD_DEFAULT);
                $sql = "INSERT INTO admins (nome, email, senha) VALUES ('Administrador', 'admin@admin.com', :senha)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(['senha' => $hashed]);
            }
        } catch(Exception $e) {
            // Table might not exist yet if schema wasn't loaded
        }
    }
}
