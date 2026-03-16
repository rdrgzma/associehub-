<?php
/**
 * Consolidated Database Synchronization Script
 * 
 * This script ensures the database structure is up to date with all migrations.
 * It is idempotent and can be run multiple times safely.
 */

require_once __DIR__ . '/../../config/database.php';

try {
    $db = Database::getConnection();
    echo "Starting database synchronization...\n\n";

    // 1. Core Tables (Base Schema)
    // Tables: admins, associacoes, associados
    
    // Associacoes structure updates
    $assocColumns = [
        'pagamento_comprovante' => 'TEXT',
        'pagamento_valor' => 'DECIMAL(10,2)',
        'pagamento_data' => 'DATETIME',
        'recorrencia_tipo' => "TEXT DEFAULT 'uma_vez'",
        'recorrencia_valor' => 'DECIMAL(10,2)'
    ];

    foreach ($assocColumns as $col => $type) {
        try {
            $db->exec("ALTER TABLE associacoes ADD COLUMN $col $type");
            echo "[MOD] Added column '$col' to 'associacoes'.\n";
        } catch (PDOException $e) {
            // Already exists or other error
        }
    }

    // Associados structure updates (including documents and spouse fields)
    $memColumns = [
        'pagamento_comprovante' => 'TEXT',
        'pagamento_valor' => 'DECIMAL(10,2)',
        'pagamento_data' => 'DATETIME',
        'doc_ficha_assinada' => 'TEXT',
        'doc_ficha_assinada_status' => 'INTEGER DEFAULT 0',
        'doc_ficha_assinada_validade' => 'DATE',
        'doc_nascimento_casamento' => 'TEXT',
        'doc_nascimento_casamento_status' => 'INTEGER DEFAULT 0',
        'doc_nascimento_casamento_validade' => 'DATE',
        'doc_conjuge_nascimento_casamento' => 'TEXT',
        'doc_conjuge_nascimento_casamento_status' => 'INTEGER DEFAULT 0',
        'doc_conjuge_nascimento_casamento_validade' => 'DATE',
        'doc_conjuge_identidade' => 'TEXT',
        'doc_conjuge_identidade_status' => 'INTEGER DEFAULT 0',
        'doc_conjuge_identidade_validade' => 'DATE',
        'doc_conjuge_quitacao_eleitoral' => 'TEXT',
        'doc_conjuge_quitacao_eleitoral_status' => 'INTEGER DEFAULT 0',
        'doc_conjuge_quitacao_eleitoral_validade' => 'DATE',
        'doc_conjuge_fiscal_federal' => 'TEXT',
        'doc_conjuge_fiscal_federal_status' => 'INTEGER DEFAULT 0',
        'doc_conjuge_fiscal_federal_validade' => 'DATE',
        'doc_conjuge_fiscal_estadual' => 'TEXT',
        'doc_conjuge_fiscal_estadual_status' => 'INTEGER DEFAULT 0',
        'doc_conjuge_fiscal_estadual_validade' => 'DATE',
        'doc_conjuge_fiscal_municipal' => 'TEXT',
        'doc_conjuge_fiscal_municipal_status' => 'INTEGER DEFAULT 0',
        'doc_conjuge_fiscal_municipal_validade' => 'DATE',
        'doc_conjuge_situacao_cpf' => 'TEXT',
        'doc_conjuge_situacao_cpf_status' => 'INTEGER DEFAULT 0',
        'doc_conjuge_situacao_cpf_validade' => 'DATE'
    ];

    foreach ($memColumns as $col => $type) {
        try {
            $db->exec("ALTER TABLE associados ADD COLUMN $col $type");
            echo "[MOD] Added column '$col' to 'associados'.\n";
        } catch (PDOException $e) {
            // Already exists
        }
    }

    // 2. New Tables (Modules)

    // Tabela: configuracoes
    $db->exec("CREATE TABLE IF NOT EXISTS configuracoes (
        chave TEXT PRIMARY KEY,
        valor TEXT
    )");
    echo "[NEW] Table 'configuracoes' ensured.\n";

    // Seed default configurations
    $defaultConfigs = [
        'pix_chave' => '',
        'pix_valor_cadastro' => '',
        'pix_instrucoes' => ''
    ];
    $stmt = $db->prepare("INSERT OR IGNORE INTO configuracoes (chave, valor) VALUES (:chave, :valor)");
    foreach ($defaultConfigs as $k => $v) {
        $stmt->execute(['chave' => $k, 'valor' => $v]);
    }

    // Tabela: associacao_nominata
    $db->exec("CREATE TABLE IF NOT EXISTS associacao_nominata (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        associacao_id INTEGER NOT NULL,
        associado_id INTEGER,
        cargo TEXT NOT NULL,
        ordem INTEGER DEFAULT 0,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (associacao_id) REFERENCES associacoes(id) ON DELETE CASCADE,
        FOREIGN KEY (associado_id) REFERENCES associados(id) ON DELETE SET NULL
    )");
    echo "[NEW] Table 'associacao_nominata' ensured.\n";

    // Tabela: pagamentos
    $db->exec("CREATE TABLE IF NOT EXISTS pagamentos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        tipo TEXT NOT NULL,
        ref_id INTEGER NOT NULL,
        valor DECIMAL(10,2) NOT NULL,
        data_vencimento DATE NOT NULL,
        data_pagamento DATETIME,
        comprovante TEXT,
        status TEXT DEFAULT 'pendente',
        recorrencia TEXT DEFAULT 'uma_vez',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
    echo "[NEW] Table 'pagamentos' ensured.\n";

    echo "\nDatabase synchronization completed successfully!\n";

} catch (Exception $e) {
    echo "\n[ERROR] Sync failed: " . $e->getMessage() . "\n";
}
