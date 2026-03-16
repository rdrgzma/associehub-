<?php require_once '../app/views/layouts/header_form.php'; ?>

<div class="max-w-md mx-auto w-full bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mt-6">
    <div class="px-6 py-8">
        <h2 class="text-2xl font-bold text-gray-900 text-center mb-2">Cadastre sua Associação</h2>
        <p class="text-gray-500 text-center text-sm mb-6">Preencha os dados para solicitar o registro da sua associação e começar a gerenciar seus membros.</p>
        
        <?php if(isset($error)): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-4 border border-red-100">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="/nova-associacao" method="POST" enctype="multipart/form-data" class="space-y-4">
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

            <div class="border-t border-gray-100 pt-4 mt-6">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Financeiro (Opcional)</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Comprovante de Pagamento (Cadastro)</label>
                        <input type="file" name="pagamento_comprovante" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>

                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-4 rounded-lg transition shadow-md mt-4">
                Enviar Solicitação
            </button>
        </form>
    </div>
</div>

<?php require_once '../app/views/layouts/footer_form.php'; ?>
