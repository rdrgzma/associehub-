<?php require_once '../app/views/layouts/header.php'; ?>

<div class="max-w-sm mx-auto w-full bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mt-10">
    <div class="px-6 py-8">
        <h2 class="text-2xl font-bold text-gray-900 text-center mb-6">Painel Administrativo</h2>
        
        <?php if(isset($error)): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-4 border border-red-100">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="/admin/login" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="admin@admin.com">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
                <input type="password" name="senha" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="admin">
            </div>

            <button type="submit" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-semibold py-2.5 px-4 rounded-lg transition shadow-md mt-2">
                Entrar
            </button>
        </form>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
