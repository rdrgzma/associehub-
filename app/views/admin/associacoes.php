<?php require_once '../app/views/layouts/header.php'; ?>

<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Todas as Associações</h1>
        <p class="text-sm text-gray-500 mt-1">Gerenciamento completo das associações da plataforma</p>
    </div>
    <a href="/admin/dashboard" class="text-gray-600 hover:text-gray-900 font-medium text-sm border border-gray-300 px-3 py-1.5 rounded-lg">&larr; Voltar ao Dashboard</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <?php if(empty($associacoes)): ?>
        <div class="p-6 text-center text-gray-500">Nenhuma associação cadastrada no momento.</div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                        <th class="py-3 px-6 font-medium">ID</th>
                        <th class="py-3 px-6 font-medium">Associação</th>
                        <th class="py-3 px-6 font-medium">Responsável</th>
                        <th class="py-3 px-6 font-medium">Status</th>
                        <th class="py-3 px-6 font-medium">Link de Cadastro</th>
                        <th class="py-3 px-6 font-medium text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    <?php foreach($associacoes as $assoc): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-6 text-gray-500">#<?= $assoc['id'] ?></td>
                        <td class="py-3 px-6 text-gray-900 font-medium">
                            <?= htmlspecialchars($assoc['nome']) ?>
                            <div class="text-xs text-gray-500 font-normal">CNPJ: <?= htmlspecialchars($assoc['cnpj']) ?></div>
                        </td>
                        <td class="py-3 px-6 text-gray-600">
                            <?= htmlspecialchars($assoc['responsavel']) ?>
                            <div class="text-xs text-gray-500">Tel: <?= htmlspecialchars($assoc['telefone']) ?></div>
                            <div class="text-xs text-gray-500">Email: <?= htmlspecialchars($assoc['email']) ?></div>
                        </td>
                        <td class="py-3 px-6">
                            <?php if($assoc['status'] == 'approved'): ?>
                                <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded-full text-xs font-medium border border-green-200">Aprovada</span>
                            <?php elseif($assoc['status'] == 'rejected'): ?>
                                <span class="bg-red-100 text-red-800 px-2 py-0.5 rounded-full text-xs font-medium border border-red-200">Rejeitada</span>
                            <?php else: ?>
                                <span class="bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full text-xs font-medium border border-amber-200">Pendente</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-6">
                            <?php if($assoc['status'] == 'approved' && $assoc['token']): ?>
                                <?php 
                                    $link = "http://" . $_SERVER['HTTP_HOST'] . "/cadastro/" . $assoc['token']; 
                                ?>
                                <div class="flex items-center space-x-2">
                                    <input type="text" readonly value="<?= $link ?>" class="text-xs bg-gray-50 border border-gray-200 rounded px-2 py-1 w-48 text-gray-500 outline-none">
                                    <button type="button" onclick="navigator.clipboard.writeText('<?= $link ?>'); alert('Link copiado!')" class="text-indigo-600 hover:text-indigo-800" title="Copiar Link">
                                        Copiar
                                    </button>
                                </div>
                            <?php else: ?>
                                <span class="text-gray-400 text-xs italic">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-6 text-right">
                            <?php if($assoc['status'] == 'approved'): ?>
                                <a href="/admin/associacoes/<?= $assoc['id'] ?>/membros" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">Ver Membros</a>
                            <?php elseif($assoc['status'] == 'pending'): ?>
                                <a href="/admin/associacoes/<?= $assoc['id'] ?>/aprovar" class="text-green-600 hover:text-green-800 font-medium text-sm mr-2 block mb-1">Aprovar</a>
                                <form action="/admin/associacoes/<?= $assoc['id'] ?>/rejeitar" method="POST" class="inline-block">
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm" onclick="return confirm('Tem certeza?')">Rejeitar</button>
                                </form>
                            <?php else: ?>
                                <span class="text-gray-400 text-sm">Rejeitada</span>
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
