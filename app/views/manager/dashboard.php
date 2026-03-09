<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel da Associação - AssocieHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <header class="bg-white shadow relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <span class="text-2xl">⚡</span>
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight">AssocieHub</h1>
                    <span class="ml-2 px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-800 text-xs font-semibold">Portal Manager</span>
                </div>
                <div class="flex items-center space-x-4 text-sm font-medium">
                    <span class="text-gray-500">Olá, <span class="text-gray-900"><?= htmlspecialchars($associacao_nome) ?></span></span>
                    <a href="/manager/logout" class="text-red-600 hover:text-red-800 transition">Sair</a>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Gestão de Associação</h1>
                <p class="text-sm text-gray-500 mt-1">Gerencie os membros e acompanhe as métricas da sua entidade.</p>
            </div>
            
            <?php if(!empty($associacao['token'])): ?>
                <?php 
                    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
                    $host = $_SERVER['HTTP_HOST'];
                    $registrationLink = "{$protocol}://{$host}/cadastro/{$associacao['token']}";
                ?>
                <div class="flex-grow max-w-md w-full bg-indigo-50 border border-indigo-100 rounded-xl p-4">
                    <label class="block text-xs font-bold text-indigo-900 uppercase tracking-widest mb-2">Link de Cadastro para Novos Membros</label>
                    <div class="flex items-center space-x-2">
                        <input type="text" readonly value="<?= $registrationLink ?>" 
                               id="regLink"
                               class="flex-grow bg-white border border-indigo-200 rounded-lg px-3 py-2 text-sm text-indigo-700 font-medium focus:outline-none">
                        <button onclick="copyLink()" class="bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-lg transition shadow-sm" title="Copiar Link">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                        </button>
                    </div>
                    <p class="text-[10px] text-indigo-500 mt-2 font-medium italic">Compartilhe este link com os membros para que eles preencham o formulário.</p>
                </div>
                <script>
                    function copyLink() {
                        const copyText = document.getElementById("regLink");
                        copyText.select();
                        copyText.setSelectionRange(0, 99999);
                        navigator.clipboard.writeText(copyText.value);
                        alert("Link copiado: " + copyText.value);
                    }
                </script>
            <?php endif; ?>
        </div>

        <!-- KPI Metrics -->
        <dl class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center relative overflow-hidden">
                <div class="absolute right-0 top-0 bottom-0 w-2 bg-indigo-500"></div>
                <dt class="text-sm font-medium text-gray-500 mb-1">Total de Membros Cadastrados</dt>
                <dd class="text-3xl font-bold text-gray-900"><?= $metrics['total_membros'] ?></dd>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center relative overflow-hidden">
                <div class="absolute right-0 top-0 bottom-0 w-2 bg-emerald-500"></div>
                <dt class="text-sm font-medium text-gray-500 mb-1">Membros Ativos</dt>
                <dd class="text-3xl font-bold text-gray-900"><?= $metrics['membros_ativos'] ?></dd>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center relative overflow-hidden">
                <div class="absolute right-0 top-0 bottom-0 w-2 bg-red-500"></div>
                <dt class="text-sm font-medium text-gray-500 mb-1">Membros Inativos / Pendentes</dt>
                <dd class="text-3xl font-bold text-gray-900"><?= $metrics['membros_inativos'] ?></dd>
            </div>
        </dl>

        <h2 class="text-lg font-bold text-gray-900 mb-4">Lista de Associados</h2>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <?php if(empty($membros)): ?>
                <div class="p-6 text-center text-gray-500">Nenhum associado cadastrado ainda.</div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                                <th class="py-3 px-6 font-medium">Nome</th>
                                <th class="py-3 px-6 font-medium">CPF</th>
                                <th class="py-3 px-6 font-medium">Contato</th>
                                <th class="py-3 px-6 font-medium">Data Cadastro</th>
                                <th class="py-3 px-6 font-medium text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100">
                            <?php foreach($membros as $membro): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3 px-6 text-gray-900 font-medium">
                                    <?= htmlspecialchars($membro['nome']) ?>
                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($membro['cidade'] . ' - ' . $membro['estado']) ?></div>
                                </td>
                                <td class="py-3 px-6 text-gray-600"><?= htmlspecialchars($membro['cpf']) ?></td>
                                <td class="py-3 px-6 text-gray-600">
                                    <?= htmlspecialchars($membro['email']) ?>
                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($membro['telefone']) ?></div>
                                </td>
                                <td class="py-3 px-6 text-gray-500">
                                    <div><?= date('d/m/Y H:i', strtotime($membro['created_at'])) ?></div>
                                    <div class="mt-1">
                                        <?php if($membro['situacao']): ?>
                                            <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded text-xs font-semibold">Ativo</span>
                                        <?php else: ?>
                                            <span class="bg-red-100 text-red-800 px-2 py-0.5 rounded text-xs font-semibold">Inativo</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-right">
                                    <a href="/manager/membros/<?= $membro['id'] ?>" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">Ver Detalhes e Documentos</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 py-6">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-500 text-sm">
            &copy; <?= date('Y') ?> AssocieHub. Todos os direitos reservados.
        </div>
    </footer>
</body>
</html>
