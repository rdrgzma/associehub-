<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nominata da Associação - AssocieHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <a href="/manager/dashboard" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <h1 class="text-lg font-bold text-gray-900 tracking-tight">Gerenciar Nominata</h1>
                    <span class="text-gray-400 text-sm">&mdash; <?= htmlspecialchars($associacao_nome) ?></span>
                </div>
                <nav class="flex items-center space-x-1 text-sm font-medium">
                    <a href="/manager/dashboard" class="text-gray-600 hover:text-gray-900 hover:bg-gray-100 px-3 py-1.5 rounded-lg transition">Painel da Associação</a>
                    <a href="/manager/financeiro" class="text-gray-600 hover:text-gray-900 hover:bg-gray-100 px-3 py-1.5 rounded-lg transition">Financeiro</a>
                    <span class="text-gray-300">|</span>
                    <span class="text-gray-500 text-xs">Olá, <strong class="text-gray-700"><?= htmlspecialchars($_SESSION['manager_nome'] ?? '') ?></strong></span>
                    <a href="/manager/logout" class="text-red-600 hover:text-red-800 hover:bg-red-50 px-3 py-1.5 rounded-lg transition">Sair</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="flex-grow max-w-4xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if(isset($_SESSION['success_msg'])): ?>
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <div class="text-sm font-medium"><?= htmlspecialchars($_SESSION['success_msg']) ?></div>
            </div>
            <?php unset($_SESSION['success_msg']); ?>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden mb-8">
            <div class="p-6 border-b border-gray-100 bg-indigo-600">
                <h2 class="text-lg font-bold text-white">Composição da Diretoria</h2>
                <p class="text-indigo-100 text-sm mt-1">Defina os membros que ocupam cada cargo na associação.</p>
            </div>

            <form action="/manager/nominata/salvar" method="POST" class="p-6">
                <div class="space-y-6">
                    <?php foreach($nominata as $pos): ?>
                        <div class="flex flex-col md:flex-row md:items-center gap-4 p-4 rounded-xl border border-gray-100 hover:border-indigo-200 hover:bg-indigo-50/30 transition shadow-sm">
                            <input type="hidden" name="nominata_id[]" value="<?= $pos['id'] ?>">
                            
                            <div class="w-full md:w-1/3">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Cargo / Função</label>
                                <input type="text" name="cargo[]" value="<?= htmlspecialchars($pos['cargo']) ?>" 
                                       class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm font-bold text-gray-800 focus:ring-2 focus:ring-indigo-500 transition">
                            </div>

                            <div class="w-full md:w-2/3">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Membro Associado</label>
                                <select name="associado_id[]" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:ring-2 focus:ring-indigo-500 transition">
                                    <option value="">-- Selecione um membro --</option>
                                    <?php foreach($membros as $membro): ?>
                                        <option value="<?= $membro['id'] ?>" <?= ($pos['associado_id'] == $membro['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($membro['nome']) ?> (<?= htmlspecialchars($membro['cpf']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition-all active:scale-95 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Salvar Nominata
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex items-start">
            <svg class="w-5 h-5 text-indigo-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div class="text-sm text-indigo-800 leading-relaxed">
                <p class="font-bold mb-1">Dica de Gestão</p>
                A nominata ajuda na organização administrativa e jurídica da sua associação. Mantenha os cargos sempre atualizados para facilitar a emissão de documentos e a visualização pelo SuperAdmin.
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-500 text-sm">
            &copy; <?= date('Y') ?> AssocieHub. Nominata v1.0
        </div>
    </footer>
</body>
</html>
