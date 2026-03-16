<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financeiro da Associação - AssocieHub</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --font-inter: "Inter", sans-serif;
        }
        body { font-family: var(--font-inter); }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <!-- Manager-specific header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <span class="text-xl">⚡</span>
                    <span class="text-lg font-bold text-gray-900 tracking-tight"><?= htmlspecialchars($_SESSION['manager_nome'] ?? 'Associação') ?></span>
                    <span class="px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 text-[10px] font-semibold">Manager</span>
                </div>
                <nav class="flex items-center space-x-1 text-sm font-medium">
                    <a href="/manager/dashboard" class="text-gray-600 hover:text-gray-900 hover:bg-gray-100 px-3 py-1.5 rounded-lg transition">Painel da Associação</a>
                    <a href="/manager/financeiro" class="text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition">Financeiro</a>
                    <span class="text-gray-300">|</span>
                    <span class="text-gray-500 text-xs">Olá, <strong class="text-gray-700"><?= htmlspecialchars($_SESSION['manager_nome'] ?? '') ?></strong></span>
                    <a href="/manager/logout" class="text-red-600 hover:text-red-800 hover:bg-red-50 px-3 py-1.5 rounded-lg transition">Sair</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8 flex justify-between items-end">
            <div>
                <nav class="flex mb-2 text-sm text-gray-500" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2">
                        <li><a href="/manager/dashboard" class="hover:text-indigo-600">Home</a></li>
                        <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                        <li class="font-medium text-gray-900">Financeiro</li>
                    </ol>
                </nav>
                <h1 class="text-2xl font-bold text-gray-900">Financeiro da Associação</h1>
                <p class="text-sm text-gray-500 mt-1">Acompanhe todos os pagamentos dos membros em um só lugar.</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <form action="/manager/financeiro" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Data Início</label>
                    <input type="date" name="data_inicio" value="<?= htmlspecialchars($filters['data_inicio'] ?? '') ?>" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Data Fim</label>
                    <input type="date" name="data_fim" value="<?= htmlspecialchars($filters['data_fim'] ?? '') ?>" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Status</label>
                    <select name="status" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                        <option value="">Todos</option>
                        <option value="pendente" <?= ($filters['status'] ?? '') === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                        <option value="pago" <?= ($filters['status'] ?? '') === 'pago' ? 'selected' : '' ?>>Pago</option>
                        <option value="cancelado" <?= ($filters['status'] ?? '') === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-grow bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition shadow-sm">
                        Filtrar
                    </button>
                    <a href="/manager/financeiro" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2 px-4 rounded-lg text-sm transition">
                        Limpar
                    </a>
                </div>
            </form>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                            <th class="py-4 px-6 font-semibold">Data Gerado</th>
                            <th class="py-4 px-6 font-semibold">Membro</th>
                            <th class="py-4 px-6 font-semibold">Valor</th>
                            <th class="py-4 px-6 font-semibold">Recorrência</th>
                            <th class="py-4 px-6 font-semibold text-center">Status</th>
                            <th class="py-4 px-6 font-semibold text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        <?php if(empty($pagamentos)): ?>
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-400 italic">Nenhum pagamento encontrado para os filtros selecionados.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($pagamentos as $pay): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-4 px-6 text-gray-500">
                                    <?= date('d/m/Y H:i', strtotime($pay['created_at'])) ?>
                                </td>
                                <td class="py-4 px-6 font-medium text-gray-900">
                                    <?= htmlspecialchars($pay['nome']) ?>
                                    <div class="text-[10px] text-gray-400 font-normal uppercase tracking-tighter">
                                        <?= $pay['tipo'] === 'associacao' ? 'Taxa da Associação' : 'Contribuição de Membro' ?>
                                    </div>
                                </td>
                                <td class="py-4 px-6 font-bold text-gray-900">
                                    R$ <?= number_format($pay['valor'], 2, ',', '.') ?>
                                </td>
                                <td class="py-4 px-6 text-gray-500">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-800">
                                    <?php 
                                        $recLabels = ['uma_vez' => 'Pagamento Único', 'mensal' => 'Mensal', 'semestral' => 'Semestral', 'anual' => 'Anual'];
                                        echo $recLabels[$pay['recorrencia'] ?? 'uma_vez'] ?? 'Pagamento Único';
                                    ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <?php if($pay['status'] === 'pago'): ?>
                                        <span class="bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">Pago</span>
                                    <?php elseif($pay['status'] === 'cancelado'): ?>
                                        <span class="bg-red-100 text-red-800 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">Cancelado</span>
                                    <?php else: ?>
                                        <span class="bg-amber-100 text-amber-800 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">Pendente</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <?php if($pay['tipo'] === 'associado'): ?>
                                            <a href="/manager/membros/<?= $pay['ref_id'] ?>" class="text-indigo-600 hover:text-indigo-900 font-medium text-xs bg-indigo-50 px-2 py-1 rounded transition">
                                                Gerenciar
                                            </a>
                                        <?php endif; ?>
                                        <?php if($pay['comprovante']): ?>
                                            <a href="/manager/pagamentos/<?= $pay['id'] ?>/download" class="text-emerald-600 hover:text-emerald-900 font-medium text-xs bg-emerald-50 px-2 py-1 rounded transition" title="Baixar Comprovante">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex items-center justify-center">
            <p class="text-gray-400 text-xs">&copy; <?= date('Y') ?> AssocieHub</p>
        </div>
    </footer>
</body>
</html>
