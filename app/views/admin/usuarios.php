<?php require_once '../app/views/layouts/header.php'; ?>

<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 flex items-center">
            <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Administradores do Sistema
        </h1>
        <p class="text-sm text-gray-500 mt-1">Gerencie quem tem acesso total à plataforma AssocieHub.</p>
    </div>
    <div class="flex items-center space-x-3 w-full sm:w-auto">
        <a href="/admin/dashboard" class="flex-1 sm:flex-none text-center bg-white border border-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg text-sm hover:bg-gray-50 transition shadow-sm">
            Voltar
        </a>
        <a href="/admin/usuarios/criar" class="flex-1 sm:flex-none text-center bg-indigo-600 text-white font-medium py-2 px-4 rounded-lg text-sm hover:bg-indigo-700 transition shadow-sm flex items-center justify-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Novo Admin
        </a>
    </div>
</div>

<?php if (isset($_SESSION['success_msg'])): ?>
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center shadow-sm">
        <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <div class="flex-grow text-sm font-medium"><?= htmlspecialchars($_SESSION['success_msg']) ?></div>
    </div>
    <?php unset($_SESSION['success_msg']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_msg'])): ?>
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex items-center shadow-sm">
        <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        <div class="flex-grow text-sm font-medium"><?= htmlspecialchars($_SESSION['error_msg']) ?></div>
    </div>
    <?php unset($_SESSION['error_msg']); ?>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">#ID</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nome do Admin</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">E-mail Corporativo</th>
                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($admins)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <p class="text-sm font-medium text-gray-600">Nenhum outro administrador cadastrado.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($admins as $admin): ?>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                #<?= str_pad($admin['id'], 3, '0', STR_PAD_LEFT) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs uppercase flex-shrink-0">
                                        <?= substr($admin['nome'], 0, 2) ?>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-bold text-gray-900">
                                            <?= htmlspecialchars($admin['nome']) ?>
                                            <?php if ($admin['id'] == $_SESSION['admin_id']): ?>
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Você</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <a href="mailto:<?= htmlspecialchars($admin['email']) ?>" class="hover:text-indigo-600 hover:underline">
                                    <?= htmlspecialchars($admin['email']) ?>
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center space-x-3">
                                    <a href="/admin/usuarios/<?= $admin['id'] ?>/editar" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition" title="Editar Usuário">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    
                                    <?php if ($admin['id'] != $_SESSION['admin_id']): ?>
                                    <form action="/admin/usuarios/<?= $admin['id'] ?>/deletar" method="POST" class="inline-block" onsubmit="return confirm('ATENÇÃO! Remover este usuário deletará permanentemente suas credenciais administrativas de plataforma. Deseja continuar?');">
                                        <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition" title="Excluir Usuário">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
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

<?php require_once '../app/views/layouts/footer.php'; ?>
