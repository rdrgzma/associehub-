<?php require_once '../app/views/layouts/header.php'; ?>

<?php
    $isEdit = isset($admin);
    $pageTitle = $isEdit ? 'Editar Administrador' : 'Adicionar Administrador';
    $postUrl = $isEdit ? "/admin/usuarios/{$admin['id']}/atualizar" : "/admin/usuarios";
?>

<div class="mb-8">
    <div class="flex items-center space-x-2 text-sm text-gray-500 mb-4">
        <a href="/admin/dashboard" class="hover:text-indigo-600 transition">Dashboard</a>
        <span>/</span>
        <a href="/admin/usuarios" class="hover:text-indigo-600 transition">Gestão da Plataforma</a>
        <span>/</span>
        <span class="text-gray-900 font-medium"><?= $pageTitle ?></span>
    </div>

    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                <?= $pageTitle ?>
            </h1>
            <p class="text-sm text-gray-500 mt-1">Preencha os dados de credenciamento e acesso para gerir os recursos.</p>
        </div>
        <a href="/admin/usuarios" class="bg-white border border-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg text-sm hover:bg-gray-50 transition shadow-sm">
            Cancelar e Voltar
        </a>
    </div>
</div>

<?php if (isset($_SESSION['error_msg'])): ?>
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex items-center shadow-sm">
        <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div class="flex-grow text-sm font-medium"><?= htmlspecialchars($_SESSION['error_msg']) ?></div>
    </div>
    <?php unset($_SESSION['error_msg']); ?>
<?php endif; ?>

<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center">
            <h3 class="font-bold text-gray-900">Configurações de Acesso</h3>
        </div>
        
        <form action="<?= $postUrl ?>" method="POST" class="p-6">
            <div class="space-y-6">
                <!-- Nome Completo -->
                <div>
                    <label for="nome" class="block text-sm font-bold text-gray-700 mb-2">Nome do Administrador *</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($admin['nome'] ?? '') ?>" required 
                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm"
                        placeholder="Ex: João Silva">
                </div>

                <!-- E-mail / Usuário -->
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-2">E-mail Institucional *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </div>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($admin['email'] ?? '') ?>" required 
                            class="w-full bg-white border border-gray-300 rounded-lg pl-10 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm"
                            placeholder="gestor@sindicato.com.br">
                    </div>
                </div>

                <!-- Definição de Senha -->
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <label for="senha" class="block text-sm font-bold text-gray-700">Senha de Acesso <?= !$isEdit ? '*' : '' ?></label>
                        <?php if ($isEdit): ?>
                            <span class="text-xs text-amber-600 font-medium bg-amber-50 px-2 py-1 rounded border border-amber-200">
                                Deixe em branco se não quiser alterar.
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input type="password" id="senha" name="senha" <?= !$isEdit ? 'required' : '' ?> minlength="5"
                            class="w-full bg-white border border-gray-300 rounded-lg pl-10 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm"
                            placeholder="Mínimo de 5 caracteres fortes">
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg shadow-md transition transform hover:-translate-y-0.5 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <?= $isEdit ? 'Atualizar Administrador' : 'Cadastrar na Plataforma' ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
