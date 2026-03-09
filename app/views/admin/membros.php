<?php require_once '../app/views/layouts/header.php'; ?>

<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Membros Associados</h1>
        <p class="text-sm text-gray-500 mt-1">
            Associação: <span class="font-semibold text-indigo-700"><?= htmlspecialchars($associacao['nome']) ?></span>
            &bull; CNPJ: <?= htmlspecialchars($associacao['cnpj']) ?>
        </p>
    </div>
    <div class="flex space-x-4 items-center">
        <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-semibold border border-indigo-200">
            Total: <?= count($membros) ?>
        </span>
        <a href="/admin/associacoes" class="text-gray-600 hover:text-gray-900 font-medium text-sm border border-gray-300 px-3 py-1.5 rounded-lg">&larr; Voltar</a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <?php if(empty($membros)): ?>
        <div class="p-8 text-center text-gray-500">
            <p class="mb-2 text-lg">Nenhum membro registrado ainda.</p>
            <p class="text-sm">A associação precisa compartilhar o link de cadastro com seus integrantes.</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                        <th class="py-3 px-6 font-medium">Nome</th>
                        <th class="py-3 px-6 font-medium">Contato</th>
                        <th class="py-3 px-6 font-medium">Endereço</th>
                        <th class="py-3 px-6 font-medium">Registro</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    <?php foreach($membros as $membro): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-6 text-gray-900 font-medium">
                            <?= htmlspecialchars($membro['nome']) ?>
                            <div class="text-xs text-gray-500 font-normal">CPF: <?= htmlspecialchars($membro['cpf']) ?></div>
                        </td>
                        <td class="py-3 px-6 text-gray-600">
                            <?= htmlspecialchars($membro['email']) ?>
                            <div class="text-xs text-gray-500">Tel: <?= htmlspecialchars($membro['telefone']) ?></div>
                        </td>
                        <td class="py-3 px-6 text-gray-600">
                            <div class="truncate w-48" title="<?= htmlspecialchars($membro['endereco']) ?>">
                                <?= htmlspecialchars($membro['endereco']) ?>
                            </div>
                            <div class="text-xs text-gray-500">
                                <?= htmlspecialchars($membro['cidade']) ?> - <?= htmlspecialchars($membro['estado']) ?>
                            </div>
                        </td>
                        <td class="py-3 px-6 text-gray-500 font-medium">
                            <?= date('d/m/Y', strtotime($membro['created_at'])) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
