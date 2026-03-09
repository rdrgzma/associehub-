<?php

class Associado extends Model {
    public function create($data) {
        $sql = "INSERT INTO associados (
                    associacao_id, nome, cpf, email, telefone, endereco, cidade, estado,
                    doc_identidade, doc_quitacao_eleitoral, doc_fiscal_federal, 
                    doc_fiscal_estadual, doc_fiscal_municipal, doc_situacao_cpf
                ) 
                VALUES (
                    :associacao_id, :nome, :cpf, :email, :telefone, :endereco, :cidade, :estado,
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
            'cidade' => $data['cidade'],
            'estado' => $data['estado'],
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
}
