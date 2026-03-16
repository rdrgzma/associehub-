<?php
require_once __DIR__ . '/config/Database.php';

try {
    $db = Database::getConnection();
    echo "Migrando dados existentes para a tabela pagamentos...\n";

    // Migrate Associacoes
    $sql = "SELECT id, pagamento_valor, pagamento_comprovante, pagamento_data FROM associacoes WHERE pagamento_comprovante IS NOT NULL";
    $stmt = $db->query($sql);
    $countAssoc = 0;
    while ($row = $stmt->fetch()) {
        $insert = "INSERT INTO pagamentos (tipo, ref_id, valor, data_vencimento, data_pagamento, comprovante, status, recorrencia)
                   VALUES ('associacao', :id, :valor, :vencimento, :pagamento, :comprovante, 'pago', 'uma_vez')";
        $insStmt = $db->prepare($insert);
        $insStmt->execute([
            'id' => $row['id'],
            'valor' => $row['pagamento_valor'],
            'vencimento' => $row['pagamento_data'],
            'pagamento' => $row['pagamento_data'],
            'comprovante' => $row['pagamento_comprovante']
        ]);
        $countAssoc++;
    }

    // Migrate Associados
    $sql = "SELECT id, pagamento_valor, pagamento_comprovante, pagamento_data FROM associados WHERE pagamento_comprovante IS NOT NULL";
    $stmt = $db->query($sql);
    $countMemb = 0;
    while ($row = $stmt->fetch()) {
        $insert = "INSERT INTO pagamentos (tipo, ref_id, valor, data_vencimento, data_pagamento, comprovante, status, recorrencia)
                   VALUES ('associado', :id, :valor, :vencimento, :pagamento, :comprovante, 'pago', 'uma_vez')";
        $insStmt = $db->prepare($insert);
        $insStmt->execute([
            'id' => $row['id'],
            'valor' => $row['pagamento_valor'],
            'vencimento' => $row['pagamento_data'],
            'pagamento' => $row['pagamento_data'],
            'comprovante' => $row['pagamento_comprovante']
        ]);
        $countMemb++;
    }

    echo "Sucesso! $countAssoc associações e $countMemb membros migrados.\n";

} catch (Exception $e) {
    die("Erro na migração de dados: " . $e->getMessage());
}
