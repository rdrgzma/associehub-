<?php require_once '../app/views/layouts/header.php'; ?>

<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Módulo Financeiro</h1>
        <p class="text-sm text-gray-500 mt-1">Gestão de pagamentos de cadastros e registros.</p>
    </div>
    <div class="flex space-x-3">
        <div class="bg-indigo-50 border border-indigo-100 rounded-lg px-4 py-2">
            <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest block">Total Recebido</span>
            <span class="text-xl font-bold text-indigo-700">R$ <?= number_format($metrics['total_geral'] ?? 0, 2, ',', '.') ?></span>
        </div>
    </div>
</div>

<!-- Metrics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex flex-col justify-center">
        <dt class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Associações</dt>
        <dd class="text-2xl font-bold text-gray-900">R$ <?= number_format($metrics['total_associacoes'] ?? 0, 2, ',', '.') ?></dd>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex flex-col justify-center">
        <dt class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Membros (Associados)</dt>
        <dd class="text-2xl font-bold text-gray-900">R$ <?= number_format($metrics['total_associados'] ?? 0, 2, ',', '.') ?></dd>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex flex-col justify-center">
        <dt class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Qtd. Transações</dt>
        <dd class="text-2xl font-bold text-gray-900"><?= $metrics['total_transacoes'] ?? 0 ?></dd>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
    <form action="/admin/financeiro" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Data Início</label>
            <input type="date" name="data_inicio" value="<?= htmlspecialchars($filters['data_inicio'] ?? '') ?>" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Data Fim</label>
            <input type="date" name="data_fim" value="<?= htmlspecialchars($filters['data_fim'] ?? '') ?>" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Valor Mínimo</label>
            <input type="text" name="valor_min" value="<?= htmlspecialchars($filters['valor_min'] ?? '') ?>" placeholder="0,00" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Valor Máximo</label>
            <input type="text" name="valor_max" value="<?= htmlspecialchars($filters['valor_max'] ?? '') ?>" placeholder="1000,00" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-indigo-500">
        </div>
        <div class="flex space-x-2">
            <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-1.5 px-4 rounded-lg text-sm transition shadow-sm">
                Filtrar
            </button>
            <a href="/admin/financeiro" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-1.5 px-4 rounded-lg text-sm transition text-center">
                Limpar
            </a>
        </div>
    </form>
</div>

<!-- Transactions Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <?php if(empty($pagamentos)): ?>
        <div class="p-12 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path></svg>
            <p class="text-gray-500 font-medium">Nenhum pagamento encontrado com os filtros aplicados.</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-[10px] uppercase tracking-widest font-bold">
                        <th class="py-3 px-6">Data</th>
                        <th class="py-3 px-6">Tipo</th>
                        <th class="py-3 px-6">Nome / Cadastro</th>
                        <th class="py-3 px-6">Valor</th>
                        <th class="py-3 px-6">Status</th>
                        <th class="py-3 px-6">Recorrência</th>
                        <th class="py-3 px-6 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    <?php foreach($pagamentos as $pay): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-6 text-gray-500 whitespace-nowrap">
                            <?= date('d/m/Y H:i', strtotime($pay['created_at'])) ?>
                        </td>
                        <td class="py-4 px-6">
                            <?php if($pay['tipo'] == 'associacao'): ?>
                                <span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase">Associação</span>
                            <?php else: ?>
                                <span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase">Membro</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-6 font-medium text-gray-900">
                            <?= htmlspecialchars($pay['nome']) ?>
                        </td>
                        <td class="py-4 px-6 text-gray-900 font-bold">
                            R$ <?= number_format($pay['valor'], 2, ',', '.') ?>
                        </td>
                        <td class="py-4 px-6">
                            <?php 
                            $statusClass = [
                                'pendente' => 'bg-amber-100 text-amber-700',
                                'pago' => 'bg-green-100 text-green-700',
                                'cancelado' => 'bg-red-100 text-red-700'
                            ];
                            ?>
                            <span class="<?= $statusClass[$pay['status'] ?? 'pendente'] ?> px-2 py-0.5 rounded text-[10px] font-bold uppercase">
                                <?= $pay['status'] ?? 'pendente' ?>
                            </span>
                        </td>
                        <td class="py-4 px-6 text-xs text-gray-500 italic">
                            <?php $recLabels = ['uma_vez' => 'Pagamento Único', 'mensal' => 'Mensal', 'semestral' => 'Semestral', 'anual' => 'Anual']; echo $recLabels[$pay['recorrencia'] ?? 'uma_vez'] ?? 'Pagamento Único'; ?>
                        </td>
                        <td class="py-4 px-6 text-right flex justify-end space-x-3">
                            <?php if($pay['comprovante']): ?>
                                <a href="<?= $pay['comprovante'] ?>" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-bold" title="Baixar Comprovante">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </a>
                            <?php endif; ?>
                            
                            <?php if($pay['status'] !== 'cancelado'): ?>
                                <form action="/admin/financeiro/<?= $pay['id'] ?>/cancelar" method="POST" onsubmit="return confirm('Deseja realmente cancelar este pagamento? O status voltará para pendente.')" class="inline">
                                    <button type="submit" class="text-red-500 hover:text-red-700" title="Cancelar Pagamento">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
