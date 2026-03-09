<?php require_once '../app/views/layouts/header.php'; ?>

<div class="max-w-md mx-auto w-full bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mt-6">
    <div class="px-6 py-8">
        <h2 class="text-2xl font-bold text-gray-900 text-center mb-2">Cadastre sua Associação</h2>
        <p class="text-gray-500 text-center text-sm mb-6">Preencha os dados para solicitar o registro da sua associação e começar a gerenciar seus membros.</p>
        
        <?php if(isset($error)): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-4 border border-red-100">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="/cadastro" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome da Associação</label>
                <input type="text" name="nome" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($data['nome'] ?? '') ?>">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">CNPJ</label>
                <input type="text" name="cnpj" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($data['cnpj'] ?? '') ?>" placeholder="00.000.000/0000-00">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Responsável</label>
                <input type="text" name="responsavel" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($data['responsavel'] ?? '') ?>">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($data['email'] ?? '') ?>">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                <input type="text" name="telefone" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($data['telefone'] ?? '') ?>">
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-4 rounded-lg transition shadow-md mt-4">
                Enviar Solicitação
            </button>
        </form>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
