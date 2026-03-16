<?php

class Associacao extends Model
{
    public function create($data)
    {
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

    public function update($id, $data)
    {
        $sql = "UPDATE associacoes SET 
                    nome = :nome, 
                    cnpj = :cnpj, 
                    responsavel = :responsavel, 
                    email = :email, 
                    telefone = :telefone,
                    status = :status
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nome' => $data['nome'],
            'cnpj' => $data['cnpj'],
            'responsavel' => $data['responsavel'],
            'email' => $data['email'],
            'telefone' => $data['telefone'],
            'status' => $data['status'],
            'id' => $id
        ]);
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM associacoes ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function search($query = null, $status = null)
    {
        $sql = "SELECT * FROM associacoes WHERE 1=1";
        $params = [];

        if ($query) {
            $sql .= " AND (nome LIKE :query OR cnpj LIKE :query OR responsavel LIKE :query OR email LIKE :query)";
            $params['query'] = "%$query%";
        }

        if ($status) {
            $sql .= " AND status = :status";
            $params['status'] = $status;
        }

        $sql .= " ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getPending()
    {
        $stmt = $this->db->query("SELECT * FROM associacoes WHERE status = 'pending' ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getTotalCount()
    {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM associacoes");
        $row = $stmt->fetch();
        return $row ? $row['total'] : 0;
    }

    public function getPendingCount()
    {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM associacoes WHERE status = 'pending'");
        $row = $stmt->fetch();
        return $row ? $row['total'] : 0;
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM associacoes WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function findByToken($token)
    {
        $stmt = $this->db->prepare("SELECT * FROM associacoes WHERE token = :token AND status = 'approved'");
        $stmt->execute(['token' => $token]);
        return $stmt->fetch();
    }

    public function approve($id)
    {
        $assoc = $this->findById($id);
        if (!$assoc) {
            return false;
        }

        // Se já estiver aprovada E já tiver token/senha, não faz nada
        if ($assoc['status'] === 'approved' && !empty($assoc['token']) && !empty($assoc['acesso_senha'])) {
            return true;
        }

        $token = $assoc['token'] ?: bin2hex(random_bytes(16));
        $senha = $assoc['acesso_senha'] ?: substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
        
        $sql = "UPDATE associacoes SET status = 'approved', token = :token, acesso_senha = :senha WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['token' => $token, 'senha' => $senha, 'id' => $id]);
    }

    public function gerarNovaSenha($id)
    {
        $assoc = $this->findById($id);
        if (!$assoc || $assoc['status'] !== 'approved') {
            return false;
        }
        $senha = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
        $sql = "UPDATE associacoes SET acesso_senha = :senha WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['senha' => $senha, 'id' => $id]);
    }

    public function alterarSenha($id, $novaSenha)
    {
        $assoc = $this->findById($id);
        if (!$assoc || $assoc['status'] !== 'approved') {
            return false;
        }
        $sql = "UPDATE associacoes SET acesso_senha = :senha WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['senha' => $novaSenha, 'id' => $id]);
    }

    public function login($identificador, $senha)
    {
        $sql = "SELECT * FROM associacoes WHERE (cnpj = :identificador OR email = :identificador) AND acesso_senha = :senha AND status = 'approved'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['identificador' => $identificador, 'senha' => $senha]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function reject($id)
    {
        $sql = "UPDATE associacoes SET status = 'rejected' WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM associacoes WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
