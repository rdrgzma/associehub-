<?php

class Nominata extends Model {
    protected $defaultCargos = [
        'Presidente',
        'Vice-Presidente',
        '1º Secretário',
        '2º Secretário',
        '1º Tesoureiro',
        '2º Tesoureiro',
        'Diretor Social',
        'Diretor de Patrimônio'
    ];

    public function getByAssociacaoId($associacao_id) {
        // Ensure board is initialized
        $this->initBoard($associacao_id);

        $sql = "SELECT n.*, a.nome as associado_nome 
                FROM associacao_nominata n
                LEFT JOIN associados a ON n.associado_id = a.id
                WHERE n.associacao_id = :associacao_id
                ORDER BY n.ordem ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['associacao_id' => $associacao_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updatePosition($associacao_id, $id, $associado_id) {
        $sql = "UPDATE associacao_nominata 
                SET associado_id = :associado_id, updated_at = CURRENT_TIMESTAMP 
                WHERE id = :id AND associacao_id = :associacao_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'associado_id' => !empty($associado_id) ? $associado_id : null,
            'id' => $id,
            'associacao_id' => $associacao_id
        ]);
    }

    public function initBoard($associacao_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM associacao_nominata WHERE associacao_id = :associacao_id");
        $stmt->execute(['associacao_id' => $associacao_id]);
        $row = $stmt->fetch();

        if ($row['total'] == 0) {
            $sql = "INSERT INTO associacao_nominata (associacao_id, cargo, ordem) VALUES (:associacao_id, :cargo, :ordem)";
            $stmt = $this->db->prepare($sql);
            foreach ($this->defaultCargos as $index => $cargo) {
                $stmt->execute([
                    'associacao_id' => $associacao_id,
                    'cargo' => $cargo,
                    'ordem' => $index
                ]);
            }
        }
    }

    public function updateCargoName($associacao_id, $id, $novo_cargo) {
        $sql = "UPDATE associacao_nominata 
                SET cargo = :cargo, updated_at = CURRENT_TIMESTAMP 
                WHERE id = :id AND associacao_id = :associacao_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'cargo' => $novo_cargo,
            'id' => $id,
            'associacao_id' => $associacao_id
        ]);
    }

    public function addPosition($associacao_id, $cargo) {
        $stmt = $this->db->prepare("SELECT MAX(ordem) as max_ordem FROM associacao_nominata WHERE associacao_id = :associacao_id");
        $stmt->execute(['associacao_id' => $associacao_id]);
        $row = $stmt->fetch();
        $nextOrdem = ($row['max_ordem'] ?? -1) + 1;

        $sql = "INSERT INTO associacao_nominata (associacao_id, cargo, ordem) VALUES (:associacao_id, :cargo, :ordem)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'associacao_id' => $associacao_id,
            'cargo' => $cargo,
            'ordem' => $nextOrdem
        ]);
    }

    public function deletePosition($associacao_id, $id) {
        $sql = "DELETE FROM associacao_nominata WHERE id = :id AND associacao_id = :associacao_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'associacao_id' => $associacao_id
        ]);
    }
}
