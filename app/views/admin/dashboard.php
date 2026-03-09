<?php require_once '../app/views/layouts/header.php'; ?>

<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Geral</h1>
        <p class="text-sm text-gray-500 mt-1">Bem-vindo, <?= htmlspecialchars($_SESSION['admin_nome'] ?? 'Administrador') ?></p>
    </div>
</div>

<!-- KPI Metrics -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center">
        <dt class="text-sm font-medium text-gray-500 mb-1">Total de Associações</dt>
        <dd class="text-3xl font-bold text-indigo-700"><?= $metrics['total_associacoes'] ?></dd>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center">
        <dt class="text-sm font-medium text-gray-500 mb-1">Associações Pendentes</dt>
        <dd class="text-3xl font-bold text-amber-600"><?= $metrics['total_pendentes'] ?></dd>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center">
        <dt class="text-sm font-medium text-gray-500 mb-1">Total de Associados</dt>
        <dd class="text-3xl font-bold text-blue-600"><?= $metrics['total_membros'] ?></dd>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center">
        <dt class="text-sm font-medium text-gray-500 mb-1">Associados Ativos</dt>
        <dd class="text-3xl font-bold text-emerald-600"><?= $metrics['membros_ativos'] ?></dd>
    </div>
</div>

<!-- Pending Associations -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-amber-50">
        <h2 class="text-lg font-semibold text-amber-800">Associações Pendentes de Aprovação</h2>
    </div>
    
    <?php if(empty($pending)): ?>
        <div class="p-6 text-center text-gray-500">Nenhuma associação pendente no momento.</div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm">
                        <th class="py-3 px-6 font-medium">Nome</th>
                        <th class="py-3 px-6 font-medium">CNPJ</th>
                        <th class="py-3 px-6 font-medium">Responsável</th>
                        <th class="py-3 px-6 font-medium">Data</th>
                        <th class="py-3 px-6 font-medium text-right">Ação</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    <?php foreach($pending as $assoc): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-6 text-gray-900 font-medium"><?= htmlspecialchars($assoc['nome']) ?></td>
                        <td class="py-3 px-6 text-gray-600"><?= htmlspecialchars($assoc['cnpj']) ?></td>
                        <td class="py-3 px-6 text-gray-600"><?= htmlspecialchars($assoc['responsavel']) ?></td>
                        <td class="py-3 px-6 text-gray-500"><?= date('d/m/Y', strtotime($assoc['created_at'])) ?></td>
                        <td class="py-3 px-6 text-right space-x-2">
                            <a href="/admin/associacoes/<?= $assoc['id'] ?>/aprovar" class="inline-block bg-green-50 text-green-700 hover:bg-green-100 px-3 py-1 rounded-md text-xs font-semibold transition border border-green-200">Aprovar</a>
                            
                            <form action="/admin/associacoes/<?= $assoc['id'] ?>/rejeitar" method="POST" class="inline-block">
                                <button type="submit" class="bg-red-50 text-red-700 hover:bg-red-100 px-3 py-1 rounded-md text-xs font-semibold transition border border-red-200" onclick="return confirm('Tem certeza que deseja rejeitar?')">Rejeitar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- All Associations summary -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">Resumo das Associações</h2>
        <a href="/admin/associacoes" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Ver todas &rarr;</a>
    </div>
    
    <?php if(empty($associacoes)): ?>
        <div class="p-6 text-center text-gray-500">Nenhuma associação cadastrada no momento.</div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                        <th class="py-3 px-6 font-medium">Associação</th>
                        <th class="py-3 px-6 font-medium">Status</th>
                        <th class="py-3 px-6 font-medium">Membros</th>
                        <th class="py-3 px-6 font-medium text-right">Detalhes</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    <?php 
                    $count = 0;
                    foreach($associacoes as $assoc): 
                        if ($count++ >= 5) break; // show only 5 latest
                    ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-6 text-gray-900 font-medium"><?= htmlspecialchars($assoc['nome']) ?></td>
                        <td class="py-3 px-6">
                            <?php if($assoc['status'] == 'approved'): ?>
                                <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded-full text-xs font-medium border border-green-200">Aprovada</span>
                            <?php elseif($assoc['status'] == 'rejected'): ?>
                                <span class="bg-red-100 text-red-800 px-2 py-0.5 rounded-full text-xs font-medium border border-red-200">Rejeitada</span>
                            <?php else: ?>
                                <span class="bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full text-xs font-medium border border-amber-200">Pendente</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-6 text-gray-600 font-medium"><?= $assoc['total_membros'] ?></td>
                        <td class="py-3 px-6 text-right">
                            <?php if($assoc['status'] == 'approved'): ?>
                                <a href="/admin/associacoes/<?= $assoc['id'] ?>/membros" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">Ver Membros</a>
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
