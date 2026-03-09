<?php

class Associacao extends Model {
    public function create($data) {
        $sql = "INSERT INTO associacoes (nome, cnpj, responsavel, email, telefone, status) 
                VALUES (:nome, :cnpj, :responsavel, :email, :telefone, 'pending')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nome' => $data['nome'],
            'cnpj' => $data['cnpj'],
            'responsavel' => $data['responsavel'],
            'email' => $data['email'],
            'telefone' => $data['telefone']
        ]);
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM associacoes ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getPending() {
        $stmt = $this->db->query("SELECT * FROM associacoes WHERE status = 'pending' ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getTotalCount() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM associacoes");
        $row = $stmt->fetch();
        return $row ? $row['total'] : 0;
    }

    public function getPendingCount() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM associacoes WHERE status = 'pending'");
        $row = $stmt->fetch();
        return $row ? $row['total'] : 0;
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM associacoes WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function findByToken($token) {
        $stmt = $this->db->prepare("SELECT * FROM associacoes WHERE token = :token AND status = 'approved'");
        $stmt->execute(['token' => $token]);
        return $stmt->fetch();
    }

    public function approve($id) {
        $assoc = $this->findById($id);
        if ($assoc && $assoc['status'] === 'approved') {
            return true;
        }
        $token = bin2hex(random_bytes(16));
        $senha = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
        $sql = "UPDATE associacoes SET status = 'approved', token = :token, acesso_senha = :senha WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['token' => $token, 'senha' => $senha, 'id' => $id]);
    }

    public function login($cnpj, $senha) {
        $sql = "SELECT * FROM associacoes WHERE cnpj = :cnpj AND acesso_senha = :senha AND status = 'approved'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['cnpj' => $cnpj, 'senha' => $senha]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function reject($id) {
        $sql = "UPDATE associacoes SET status = 'rejected' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
