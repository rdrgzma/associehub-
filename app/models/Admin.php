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

    public function updatePassword($id, $newPassword) {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE admins SET senha = :senha WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['senha' => $hashed, 'id' => $id]);
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM admins WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getAll() {
        $stmt = $this->db->query("SELECT id, nome, email FROM admins ORDER BY nome ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $hashed = password_hash($data['senha'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO admins (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nome' => $data['nome'],
            'email' => $data['email'],
            'senha' => $hashed
        ]);
    }

    public function update($id, $data) {
        if (!empty($data['senha'])) {
            $hashed = password_hash($data['senha'], PASSWORD_DEFAULT);
            $sql = "UPDATE admins SET nome = :nome, email = :email, senha = :senha WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'nome' => $data['nome'],
                'email' => $data['email'],
                'senha' => $hashed,
                'id' => $id
            ]);
        } else {
            $sql = "UPDATE admins SET nome = :nome, email = :email WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'nome' => $data['nome'],
                'email' => $data['email'],
                'id' => $id
            ]);
        }
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM admins WHERE id = :id");
        return $stmt->execute(['id' => $id]);
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
