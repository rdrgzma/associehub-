<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Nominata - SuperAdmin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <a href="/admin/associacoes/<?= $associacao['id'] ?>/membros" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight">Gerenciar Nominata - SuperAdmin</h1>
                </div>
                <div class="text-sm font-medium text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                    <?= htmlspecialchars($associacao['nome']) ?>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow max-w-5xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?php if(isset($_SESSION['success_msg'])): ?>
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <div class="text-sm font-medium"><?= htmlspecialchars($_SESSION['success_msg']) ?></div>
            </div>
            <?php unset($_SESSION['success_msg']); ?>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Board Management -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden mb-8">
                    <div class="p-6 border-b border-gray-100 bg-indigo-600 text-white flex justify-between items-center">
                        <div>
                            <h2 class="text-lg font-bold">Composição da Diretoria</h2>
                            <p class="text-indigo-100 text-xs mt-1">Edite cargos e atribua membros como SuperAdmin.</p>
                        </div>
                    </div>

                    <form action="/admin/associacoes/<?= $associacao['id'] ?>/nominata/salvar" method="POST" class="p-6">
                        <div class="space-y-4">
                            <?php foreach($nominata as $pos): ?>
                                <div class="flex flex-col md:flex-row gap-4 p-4 rounded-xl border border-gray-100 hover:border-indigo-200 hover:bg-indigo-50/30 transition shadow-sm group">
                                    <input type="hidden" name="nominata_id[]" value="<?= $pos['id'] ?>">
                                    
                                    <div class="flex-grow">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Cargo / Função</label>
                                                <input type="text" name="cargo[]" value="<?= htmlspecialchars($pos['cargo']) ?>" 
                                                       class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm font-bold text-gray-800 focus:ring-2 focus:ring-indigo-500 transition">
                                            </div>

                                            <div>
                                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Membro Associado</label>
                                                <select name="associado_id[]" class="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:ring-2 focus:ring-indigo-500 transition">
                                                    <option value="">-- Selecione um membro --</option>
                                                    <?php foreach($membros as $membro): ?>
                                                        <option value="<?= $membro['id'] ?>" <?= ($pos['associado_id'] == $membro['id']) ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($membro['nome']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-end justify-end md:pb-1">
                                        <button type="button" 
                                                onclick="if(confirm('Tem certeza que deseja remover este cargo da nominata?')) { document.getElementById('remove-form-<?= $pos['id'] ?>').submit(); }"
                                                class="text-red-300 hover:text-red-600 transition p-2" title="Remover Cargo">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition-all active:scale-95 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar Actions -->
            <div class="space-y-6">
                <!-- Add New Role -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-bold text-gray-800 mb-4 flex items-center">
                        <svg class="w-4 h-4 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Adicionar Novo Cargo
                    </h3>
                    <form action="/admin/associacoes/<?= $associacao['id'] ?>/nominata/adicionar" method="POST" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Nome do Cargo</label>
                            <input type="text" name="novo_cargo" placeholder="Ex: Diretor Técnico" required 
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:bg-white focus:ring-2 focus:ring-indigo-500 transition">
                        </div>
                        <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-bold py-2 px-4 rounded-lg text-sm transition">
                            Adicionar Cargo
                        </button>
                    </form>
                </div>

                <!-- Hidden removal forms -->
                <?php foreach($nominata as $pos): ?>
                    <form id="remove-form-<?= $pos['id'] ?>" action="/admin/associacoes/<?= $associacao['id'] ?>/nominata/remover/<?= $pos['id'] ?>" method="POST" class="hidden"></form>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-500 text-sm">
            &copy; <?= date('Y') ?> AssocieHub SuperAdmin
        </div>
    </footer>
</body>
</html>
