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
        $sql = "UPDATE associacoes SET status = 'approved', token = :token WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['token' => $token, 'id' => $id]);
    }

    public function reject($id) {
        $sql = "UPDATE associacoes SET status = 'rejected' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
