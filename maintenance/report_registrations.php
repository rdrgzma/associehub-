<?php
/**
 * Relatório de Cadastros e Pagamentos (CLI)
 * Uso: php maintenance/report_registrations.php
 */

require_once __DIR__ . '/../config/database.php';

// Helper para formatar moeda
function formatMoeda($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

// Helper para formatar data
function formatData($data) {
    if (!$data) return '---';
    return date('d/m/Y H:i', strtotime($data));
}

try {
    $db = Database::getConnection();

    echo "\n\033[1;34m=== RELATÓRIO DE CADASTROS DO SISTEMA ===\033[0m\n\n";

    // --- SEÇÃO 1: ASSOCIAÇÕES ---
    echo "\033[1;33m[1] ASSOCIAÇÕES REGISTRADAS\033[0m\n";
    echo str_repeat("-", 100) . "\n";
    printf("%-5s | %-30s | %-16s | %-10s | %-10s\n", "ID", "Nome", "Data Reg.", "Status", "Pagamento");
    echo str_repeat("-", 100) . "\n";

    $stmtAssoc = $db->query("
        SELECT a.id, a.nome, a.created_at, a.status,
               p.valor, p.status as p_status, p.data_pagamento
        FROM associacoes a
        LEFT JOIN pagamentos p ON p.tipo = 'associacao' AND p.ref_id = a.id
        ORDER BY a.created_at DESC
    ");
    $assocs = $stmtAssoc->fetchAll();

    foreach ($assocs as $a) {
        $pagInfo = ($a['p_status'] === 'pago') ? "\033[0;32mPAGO\033[0m" : ($a['p_status'] ?: 'Pendente');
        printf("%-5d | %-30s | %-16s | %-10s | %-10s\n", 
            $a['id'], 
            mb_strimwidth($a['nome'], 0, 30, "..."), 
            formatData($a['created_at']), 
            strtoupper($a['status']),
            $pagInfo
        );
    }
    echo str_repeat("-", 100) . "\n\n";

    // --- SEÇÃO 2: MEMBROS ---
    echo "\033[1;33m[2] MEMBROS ASSOCIADOS\033[0m\n";
    echo str_repeat("-", 100) . "\n";
    printf("%-5s | %-30s | %-20s | %-16s\n", "ID", "Nome", "Associação", "Data Assoc.");
    echo str_repeat("-", 100) . "\n";

    $stmtMem = $db->query("
        SELECT m.id, m.nome, m.created_at, a.nome as associacao_nome
        FROM associados m
        JOIN associacoes a ON m.associacao_id = a.id
        ORDER BY m.created_at DESC
    ");
    $membros = $stmtMem->fetchAll();

    foreach ($membros as $m) {
        printf("%-5d | %-30s | %-20s | %-16s\n", 
            $m['id'], 
            mb_strimwidth($m['nome'], 0, 30, "..."), 
            mb_strimwidth($m['associacao_nome'], 0, 20, "..."), 
            formatData($m['created_at'])
        );
    }
    echo str_repeat("-", 100) . "\n\n";

    // --- RESUMO ---
    echo "\033[1;36mRESUMO GERAL:\033[0m\n";
    echo "Total de Associações: " . count($assocs) . "\n";
    echo "Total de Membros:     " . count($membros) . "\n";
    echo "\n\033[0;90mRelatório gerado em: " . date('d/m/Y H:i:s') . "\033[0m\n\n";

} catch (Exception $e) {
    echo "\n\033[0;31m[ERRO] Não foi possível gerar o relatório: " . $e->getMessage() . "\033[0m\n\n";
}
