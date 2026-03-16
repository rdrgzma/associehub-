<?php
/**
 * Script para limpar todos os dados do banco de dados, exceto a tabela admin.
 * Use com cautela!
 */

require_once __DIR__ . '/config/database.php';

try {
    $db = Database::getConnection();
    
    // Lista todas as tabelas
    $stmt = $db->query("SELECT name FROM sqlite_master WHERE type='table'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $excludedTables = ['admins', 'sqlite_sequence'];
    $clearedTables = [];
    
    echo "Iniciando limpeza do banco de dados...\n\n";
    
    // Desabilitar chaves estrangeiras temporariamente para evitar conflitos ao apagar
    $db->exec('PRAGMA foreign_keys = OFF');
    
    foreach ($tables as $table) {
        if (!in_array($table, $excludedTables)) {
            // Limpa a tabela
            $db->exec("DELETE FROM \"$table\"");
            // Reseta o auto-incremento
            $db->exec("DELETE FROM sqlite_sequence WHERE name='$table'");
            $clearedTables[] = $table;
            echo "[OK] Tabela '$table' limpa.\n";
        } else {
            echo "[SKIP] Tabela '$table' preservada.\n";
        }
    }
    
    // Reabilitar chaves estrangeiras
    $db->exec('PRAGMA foreign_keys = ON');
    
    echo "\nLimpeza concluída com sucesso!\n";
    echo "Tabelas limpas: " . implode(', ', $clearedTables) . "\n";

} catch (Exception $e) {
    echo "\n[ERRO] Ocorreu um problema ao limpar o banco: " . $e->getMessage() . "\n";
}
