<?php

class Financeiro extends Model {
    public function getPagamentos($filters = []) {
        $sql = "
            SELECT 
                p.*,
                CASE 
                    WHEN p.tipo = 'associacao' THEN a.nome
                    WHEN p.tipo = 'associado' THEN m.nome
                END as nome
            FROM pagamentos p
            LEFT JOIN associacoes a ON p.tipo = 'associacao' AND p.ref_id = a.id
            LEFT JOIN associados m ON p.tipo = 'associado' AND p.ref_id = m.id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($filters['data_inicio'])) {
            $sql .= " AND p.created_at >= :data_inicio";
            $params['data_inicio'] = $filters['data_inicio'] . " 00:00:00";
        }

        if (!empty($filters['data_fim'])) {
            $sql .= " AND p.created_at <= :data_fim";
            $params['data_fim'] = $filters['data_fim'] . " 23:59:59";
        }

        if (!empty($filters['valor_min'])) {
            $sql .= " AND p.valor >= :valor_min";
            $params['valor_min'] = str_replace(',', '.', $filters['valor_min']);
        }

        if (!empty($filters['valor_max'])) {
            $sql .= " AND p.valor <= :valor_max";
            $params['valor_max'] = str_replace(',', '.', $filters['valor_max']);
        }

        if (!empty($filters['status'])) {
            $sql .= " AND p.status = :status";
            $params['status'] = $filters['status'];
        }

        if (!empty($filters['ref_id']) && !empty($filters['tipo'])) {
            $sql .= " AND p.ref_id = :ref_id AND p.tipo = :tipo";
            $params['ref_id'] = $filters['ref_id'];
            $params['tipo'] = $filters['tipo'];
        }

        if (!empty($filters['associacao_id'])) {
            $sql .= " AND ( (p.tipo = 'associacao' AND p.ref_id = :assoc_id) OR (p.tipo = 'associado' AND m.associacao_id = :assoc_id_m) )";
            $params['assoc_id'] = $filters['associacao_id'];
            $params['assoc_id_m'] = $filters['associacao_id'];
        }

        $sql .= " ORDER BY p.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $sql = "
            SELECT 
                p.*,
                CASE 
                    WHEN p.tipo = 'associacao' THEN a.nome
                    WHEN p.tipo = 'associado' THEN m.nome
                END as nome,
                CASE 
                    WHEN p.tipo = 'associacao' THEN a.cnpj
                    WHEN p.tipo = 'associado' THEN m.cpf
                END as documento,
                m.email, m.telefone, m.endereco, m.numero, m.bairro, m.cidade, m.estado
            FROM pagamentos p
            LEFT JOIN associacoes a ON p.tipo = 'associacao' AND p.ref_id = a.id
            LEFT JOIN associados m ON p.tipo = 'associado' AND p.ref_id = m.id
            WHERE p.id = :id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getMetrics() {
        $stmt = $this->db->query("SELECT SUM(valor) as total FROM pagamentos WHERE status = 'pago'");
        $totalGeral = $stmt->fetch()['total'] ?? 0;

        $stmt = $this->db->query("SELECT COUNT(*) as total FROM pagamentos WHERE status = 'pago'");
        $totalTransacoes = $stmt->fetch()['total'] ?? 0;

        $stmt = $this->db->query("SELECT SUM(valor) as total FROM pagamentos WHERE status = 'pago' AND tipo = 'associacao'");
        $totalAssoc = $stmt->fetch()['total'] ?? 0;

        $stmt = $this->db->query("SELECT SUM(valor) as total FROM pagamentos WHERE status = 'pago' AND tipo = 'associado'");
        $totalMemb = $stmt->fetch()['total'] ?? 0;

        return [
            'total_geral' => $totalGeral,
            'total_transacoes' => $totalTransacoes,
            'total_associacoes' => $totalAssoc,
            'total_associados' => $totalMemb
        ];
    }

    public function cancelPayment($id) {
        $sql = "UPDATE pagamentos SET status = 'pendente', data_pagamento = NULL, comprovante = NULL, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function confirmPayment($id, $comprovante = null) {
        $sql = "UPDATE pagamentos SET status = 'pago', data_pagamento = CURRENT_TIMESTAMP";
        $params = ['id' => $id];
        
        if ($comprovante) {
            $sql .= ", comprovante = :comprovante";
            $params['comprovante'] = $comprovante;
        }
        
        $sql .= ", updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function createPayment($data) {
        $sql = "INSERT INTO pagamentos (tipo, ref_id, valor, data_vencimento, status, recorrencia, data_pagamento, comprovante)
                VALUES (:tipo, :ref_id, :valor, :data_vencimento, :status, :recorrencia, :data_pagamento, :comprovante)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'tipo' => $data['tipo'],
            'ref_id' => $data['ref_id'],
            'valor' => $data['valor'],
            'data_vencimento' => $data['data_vencimento'] ?? date('Y-m-d'),
            'status' => $data['status'] ?? 'pendente',
            'recorrencia' => $data['recorrencia'] ?? 'uma_vez',
            'data_pagamento' => $data['data_pagamento'] ?? null,
            'comprovante' => $data['comprovante'] ?? null
        ]);
    }

    public function generateNextCharge($pagamentoId) {
        $stmt = $this->db->prepare("SELECT * FROM pagamentos WHERE id = :id");
        $stmt->execute(['id' => $pagamentoId]);
        $prev = $stmt->fetch();

        if (!$prev || $prev['recorrencia'] == 'uma_vez') return false;

        $vencimento = new DateTime($prev['data_vencimento']);
        switch ($prev['recorrencia']) {
            case 'mensal': $vencimento->modify('+1 month'); break;
            case 'semestral': $vencimento->modify('+6 months'); break;
            case 'anual': $vencimento->modify('+1 year'); break;
        }

        return $this->createPayment([
            'tipo' => $prev['tipo'],
            'ref_id' => $prev['ref_id'],
            'valor' => $prev['valor'],
            'data_vencimento' => $vencimento->format('Y-m-d'),
            'status' => 'pendente',
            'recorrencia' => $prev['recorrencia']
        ]);
    }
}
