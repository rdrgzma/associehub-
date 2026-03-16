<?php
/**
 * Gerador de Relatório Financeiro em HTML
 * Uso: php maintenance/generate_financial_html.php
 */

require_once __DIR__ . '/../config/database.php';

function formatMoeda($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

function formatData($data) {
    if (!$data) return '---';
    return date('d/m/Y', strtotime($data));
}

try {
    $db = Database::getConnection();

    // Buscar pagamentos de associações
    $stmtAssoc = $db->query("
        SELECT p.*, a.nome as pagador_nome
        FROM pagamentos p
        JOIN associacoes a ON p.ref_id = a.id
        WHERE p.tipo = 'associacao'
        ORDER BY p.data_vencimento DESC
    ");
    $pagamentosAssoc = $stmtAssoc->fetchAll();

    // Buscar pagamentos de associados
    $stmtMem = $db->query("
        SELECT p.*, m.nome as pagador_nome, a.nome as associacao_nome
        FROM pagamentos p
        JOIN associados m ON p.ref_id = m.id
        JOIN associacoes a ON m.associacao_id = a.id
        WHERE p.tipo = 'associado'
        ORDER BY p.data_vencimento DESC
    ");
    $pagamentosMembros = $stmtMem->fetchAll();

    $todosPagamentos = array_merge($pagamentosAssoc, $pagamentosMembros);
    
    // Calcular totais
    $totalPago = 0;
    $totalPendente = 0;
    foreach ($todosPagamentos as $p) {
        if ($p['status'] === 'pago') {
            $totalPago += $p['valor'];
        } else {
            $totalPendente += $p['valor'];
        }
    }

    $html = '<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Financeiro - AssocieHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: "Inter", sans-serif; }
        @media print {
            .no-print { display: none; }
            body { background: white; }
            .shadow-xl { shadow: none; }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 p-8">
    <div class="max-w-5xl mx-auto bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        <!-- Header -->
        <div class="bg-indigo-600 p-8 text-white flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">Relatório Financeiro</h1>
                <p class="text-indigo-100 mt-1">Status consolidado de pagamentos e pendências</p>
            </div>
            <div class="text-right">
                <div class="text-sm opacity-80">Gerado em</div>
                <div class="text-lg font-semibold">' . date('d/m/Y H:i') . '</div>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8 bg-gray-50 border-b border-gray-200">
            <div class="bg-white p-6 rounded-xl border border-green-100 shadow-sm">
                <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Recebido</div>
                <div class="text-3xl font-bold text-green-600 mt-1">' . formatMoeda($totalPago) . '</div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-amber-100 shadow-sm">
                <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total em Aberto</div>
                <div class="text-3xl font-bold text-amber-600 mt-1">' . formatMoeda($totalPendente) . '</div>
            </div>
        </div>

        <!-- Table -->
        <div class="p-8">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                        <th class="py-4 px-2">Data Venc.</th>
                        <th class="py-4 px-2">Pagador</th>
                        <th class="py-4 px-2">Tipo</th>
                        <th class="py-4 px-2">Valor</th>
                        <th class="py-4 px-2 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
';

    foreach ($todosPagamentos as $p) {
        $statusClass = $p['status'] === 'pago' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700';
        $tipoLabel = $p['tipo'] === 'associacao' ? 'Associação' : 'Membro';
        
        $html .= '
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-2 text-sm text-gray-600">' . formatData($p['data_vencimento']) . '</td>
                        <td class="py-4 px-2 font-medium text-gray-900">
                            ' . htmlspecialchars($p['pagador_nome']) . '
                            ' . (isset($p['associacao_nome']) ? '<div class="text-[10px] text-gray-400 font-normal">' . htmlspecialchars($p['associacao_nome']) . '</div>' : '') . '
                        </td>
                        <td class="py-4 px-2 text-sm text-gray-500">' . $tipoLabel . '</td>
                        <td class="py-4 px-2 font-bold text-gray-900">' . formatMoeda($p['valor']) . '</td>
                        <td class="py-4 px-2 text-center">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold uppercase ' . $statusClass . '">
                                ' . $p['status'] . '
                            </span>
                        </td>
                    </tr>';
    }

    $html .= '
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="p-8 bg-gray-50 border-t border-gray-100 flex justify-between items-center no-print">
            <div class="text-xs text-gray-400">AssocieHub &copy; ' . date('Y') . '</div>
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-bold text-sm shadow-md transition">
                Imprimir Relatório
            </button>
        </div>
    </div>
</body>
</html>';

    file_put_contents(__DIR__ . '/../financial_report.html', $html);
    echo "Relatório gerado com sucesso: financial_report.html\n";

} catch (Exception $e) {
    echo "Erro ao gerar relatório: " . $e->getMessage() . "\n";
}
