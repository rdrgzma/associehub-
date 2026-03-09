<?php

class Associado extends Model {
    public function create($data) {
        $sql = "INSERT INTO associados (
                    associacao_id, nome, cpf, email, telefone, endereco, numero, complemento, bairro, cep, cidade, estado,
                    nacionalidade, naturalidade, data_nascimento, idade, rg, rg_orgao_emissor,
                    filiacao_1_nome, filiacao_1_cpf, filiacao_2_nome, filiacao_2_cpf,
                    estado_civil, forma_comunhao, conjuge_nome, conjuge_cpf,
                    profissao_1, profissao_1_registro, profissao_1_orgao,
                    profissao_2, profissao_2_registro, profissao_2_orgao,
                    doc_identidade, doc_quitacao_eleitoral, doc_fiscal_federal, 
                    doc_fiscal_estadual, doc_fiscal_municipal, doc_situacao_cpf
                ) 
                VALUES (
                    :associacao_id, :nome, :cpf, :email, :telefone, :endereco, :numero, :complemento, :bairro, :cep, :cidade, :estado,
                    :nacionalidade, :naturalidade, :data_nascimento, :idade, :rg, :rg_orgao_emissor,
                    :filiacao_1_nome, :filiacao_1_cpf, :filiacao_2_nome, :filiacao_2_cpf,
                    :estado_civil, :forma_comunhao, :conjuge_nome, :conjuge_cpf,
                    :profissao_1, :profissao_1_registro, :profissao_1_orgao,
                    :profissao_2, :profissao_2_registro, :profissao_2_orgao,
                    :doc_identidade, :doc_quitacao_eleitoral, :doc_fiscal_federal,
                    :doc_fiscal_estadual, :doc_fiscal_municipal, :doc_situacao_cpf
                )";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'associacao_id' => $data['associacao_id'],
            'nome' => $data['nome'],
            'cpf' => $data['cpf'],
            'email' => $data['email'],
            'telefone' => $data['telefone'],
            'endereco' => $data['endereco'],
            'numero' => $data['numero'] ?? null,
            'complemento' => $data['complemento'] ?? null,
            'bairro' => $data['bairro'] ?? null,
            'cep' => $data['cep'] ?? null,
            'cidade' => $data['cidade'],
            'estado' => $data['estado'],
            'nacionalidade' => $data['nacionalidade'] ?? null,
            'naturalidade' => $data['naturalidade'] ?? null,
            'data_nascimento' => $data['data_nascimento'] ?? null,
            'idade' => $data['idade'] ?? null,
            'rg' => $data['rg'] ?? null,
            'rg_orgao_emissor' => $data['rg_orgao_emissor'] ?? null,
            'filiacao_1_nome' => $data['filiacao_1_nome'] ?? null,
            'filiacao_1_cpf' => $data['filiacao_1_cpf'] ?? null,
            'filiacao_2_nome' => $data['filiacao_2_nome'] ?? null,
            'filiacao_2_cpf' => $data['filiacao_2_cpf'] ?? null,
            'estado_civil' => $data['estado_civil'] ?? null,
            'forma_comunhao' => $data['forma_comunhao'] ?? null,
            'conjuge_nome' => $data['conjuge_nome'] ?? null,
            'conjuge_cpf' => $data['conjuge_cpf'] ?? null,
            'profissao_1' => $data['profissao_1'] ?? null,
            'profissao_1_registro' => $data['profissao_1_registro'] ?? null,
            'profissao_1_orgao' => $data['profissao_1_orgao'] ?? null,
            'profissao_2' => $data['profissao_2'] ?? null,
            'profissao_2_registro' => $data['profissao_2_registro'] ?? null,
            'profissao_2_orgao' => $data['profissao_2_orgao'] ?? null,
            'doc_identidade' => $data['doc_identidade'],
            'doc_quitacao_eleitoral' => $data['doc_quitacao_eleitoral'],
            'doc_fiscal_federal' => $data['doc_fiscal_federal'],
            'doc_fiscal_estadual' => $data['doc_fiscal_estadual'],
            'doc_fiscal_municipal' => $data['doc_fiscal_municipal'],
            'doc_situacao_cpf' => $data['doc_situacao_cpf']
        ]);
    }

    public function getByAssociacaoId($associacao_id) {
        $stmt = $this->db->prepare("SELECT * FROM associados WHERE associacao_id = :associacao_id ORDER BY created_at DESC");
        $stmt->execute(['associacao_id' => $associacao_id]);
        return $stmt->fetchAll();
    }

    public function getTotalByAssociacao($associacao_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM associados WHERE associacao_id = :associacao_id");
        $stmt->execute(['associacao_id' => $associacao_id]);
        $row = $stmt->fetch();
        return $row ? $row['total'] : 0;
    }

    public function getTotalCount() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM associados");
        $row = $stmt->fetch();
        return $row ? $row['total'] : 0;
    }

    public function getTotalActiveCount() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM associados WHERE situacao = 1");
        $row = $stmt->fetch();
        return $row ? $row['total'] : 0;
    }

    public function getTotalActiveByAssociacao($associacao_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM associados WHERE associacao_id = :associacao_id AND situacao = 1");
        $stmt->execute(['associacao_id' => $associacao_id]);
        $row = $stmt->fetch();
        return $row ? $row['total'] : 0;
    }

    public function getTotalInactiveByAssociacao($associacao_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM associados WHERE associacao_id = :associacao_id AND (situacao = 0 OR situacao IS NULL)");
        $stmt->execute(['associacao_id' => $associacao_id]);
        $row = $stmt->fetch();
        return $row ? $row['total'] : 0;
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM associados WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function updateDocumentStatus($id, $updates) {
        $allowedFields = [
            'doc_identidade_status', 'doc_identidade_validade',
            'doc_quitacao_eleitoral_status', 'doc_quitacao_eleitoral_validade',
            'doc_fiscal_federal_status', 'doc_fiscal_federal_validade',
            'doc_fiscal_estadual_status', 'doc_fiscal_estadual_validade',
            'doc_fiscal_municipal_status', 'doc_fiscal_municipal_validade',
            'doc_situacao_cpf_status', 'doc_situacao_cpf_validade'
        ];
        
        $setClauses = [];
        $params = ['id' => $id];
        
        foreach ($updates as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $setClauses[] = "$key = :$key";
                $params[$key] = $value;
            }
        }
        
        if (empty($setClauses)) return false;
        
        $sql = "UPDATE associados SET " . implode(', ', $setClauses) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function updateGlobalStatus($id, $situacao, $validade) {
        $sql = "UPDATE associados SET situacao = :situacao, validade = :validade WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'situacao' => $situacao,
            'validade' => $validade,
            'id' => $id
        ]);
    }

    public function updateDataAndFiles($id, $data) {
        $allowedFields = [
            'nome', 'cpf', 'email', 'telefone', 'endereco', 'numero', 'complemento', 'bairro', 'cep', 'cidade', 'estado',
            'nacionalidade', 'naturalidade', 'data_nascimento', 'idade', 'rg', 'rg_orgao_emissor',
            'filiacao_1_nome', 'filiacao_1_cpf', 'filiacao_2_nome', 'filiacao_2_cpf',
            'estado_civil', 'forma_comunhao', 'conjuge_nome', 'conjuge_cpf',
            'profissao_1', 'profissao_1_registro', 'profissao_1_orgao',
            'profissao_2', 'profissao_2_registro', 'profissao_2_orgao',
            'doc_identidade', 'doc_quitacao_eleitoral', 'doc_fiscal_federal',
            'doc_fiscal_estadual', 'doc_fiscal_municipal', 'doc_situacao_cpf'
        ];
        
        $setClauses = [];
        $params = ['id' => $id];
        
        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields) && $value !== null) {
                $setClauses[] = "$key = :$key";
                $params[$key] = $value;
            }
        }
        
        if (empty($setClauses)) return false;
        
        $sql = "UPDATE associados SET " . implode(', ', $setClauses) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id) {
        // 1. Get member data to find file paths
        $membro = $this->findById($id);
        if (!$membro) return false;

        // 2. Unlink files if they exist
        $docFields = [
            'doc_identidade', 'doc_quitacao_eleitoral', 'doc_fiscal_federal',
            'doc_fiscal_estadual', 'doc_fiscal_municipal', 'doc_situacao_cpf'
        ];

        $uploadBasePath = __DIR__ . '/../../public';
        foreach ($docFields as $field) {
            if (!empty($membro[$field])) {
                $filePath = $uploadBasePath . $membro[$field];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }

        // 3. Delete from database
        $stmt = $this->db->prepare("DELETE FROM associados WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
