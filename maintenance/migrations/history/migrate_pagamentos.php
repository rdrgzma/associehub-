<?php
require_once __DIR__ . '/config/Database.php';

try {
    $db = Database::getConnection();
    
    echo "Iniciando migração para a tabela de pagamentos...\n";

    // 1. Create pagamentos table
    $db->exec("CREATE TABLE IF NOT EXISTS pagamentos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        tipo TEXT NOT NULL, -- 'associacao' ou 'associado'
        ref_id INTEGER NOT NULL,
        valor DECIMAL(10,2) NOT NULL,
        data_vencimento DATE NOT NULL,
        data_pagamento DATETIME,
        comprovante TEXT,
        status TEXT DEFAULT 'pendente', -- 'pendente', 'pago', 'cancelado'
        recorrencia TEXT DEFAULT 'uma_vez', -- 'uma_vez', 'mensal', 'semestral', 'anual'
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    
    // 2. Add recurrence columns to associacoes (for global setting or template)
    try {
        $db->exec("ALTER TABLE associacoes ADD COLUMN recorrencia_tipo TEXT DEFAULT 'uma_vez'");
        $db->exec("ALTER TABLE associacoes ADD COLUMN recorrencia_valor DECIMAL(10,2)");
    } catch (Exception $e) {
        // Column might already exist
        echo "Colunas de recorrência em associacoes podem já existir: " . $e->getMessage() . "\n";
    }

    echo "Migração concluída com sucesso!\n";

} catch (Exception $e) {
    die("Erro na migração: " . $e->getMessage());
}
