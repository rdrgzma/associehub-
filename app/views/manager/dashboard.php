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
            
            <?php if(!empty($associacao['token']) && $associacao['status'] !== 'rejected'): ?>
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

        <!-- System Alerts -->
        <?php if(isset($_SESSION['success_msg'])): ?>
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <div class="flex-grow text-sm font-medium"><?= htmlspecialchars($_SESSION['success_msg']) ?></div>
            </div>
            <?php unset($_SESSION['success_msg']); ?>
        <?php endif; ?>

        <?php if(isset($_SESSION['error_msg'])): ?>
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                <div class="flex-grow text-sm font-medium"><?= htmlspecialchars($_SESSION['error_msg']) ?></div>
            </div>
            <?php unset($_SESSION['error_msg']); ?>
        <?php endif; ?>

        <!-- Manager Settings -->
        <div class="mb-8 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center cursor-pointer" onclick="document.getElementById('password-form-container').classList.toggle('hidden')">
                <h3 class="text-sm font-bold text-gray-800 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <span>Configurações de Conta</span>
                </h3>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
            
            <div id="password-form-container" class="hidden p-6">
                <form action="/manager/alterar-senha" method="POST" class="max-w-md">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Alterar Senha de Acesso</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nova Senha</label>
                            <input type="password" name="nova_senha" required class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirme a Nova Senha</label>
                            <input type="password" name="confirma_senha" required class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg text-sm transition shadow-sm">
                                Salvar Nova Senha
                            </button>
                        </div>
                    </div>
                </form>
            </div>
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
                                    <?php 
                                        $wp_num = preg_replace('/\D/', '', $membro['telefone']);
                                        $fname = explode(' ', trim($membro['nome']))[0];
                                        $wp_msg = "Olá {$fname}!\n\nSegue os dados para pagamento da sua contribuição de Associado:\n\n*Valor:* R$ " . ($config['pix_valor_cadastro'] ?? '0,00') . "\n*Chave PIX:* " . ($config['pix_chave'] ?? '') . "\n\n" . ($config['pix_instrucoes'] ?? '') . "\n\nPor favor, envie o comprovante por aqui assim que concluir.";
                                        $wp_url = "https://wa.me/55{$wp_num}?text=" . urlencode($wp_msg);
                                    ?>
                                    <div class="flex flex-col items-end space-y-2">
                                        <?php if(!empty($config['pix_chave'])): ?>
                                        <a href="<?= $wp_url ?>" target="_blank" class="text-emerald-600 hover:text-emerald-800 font-medium text-xs flex items-center bg-emerald-50 px-2 py-1 rounded transition whitespace-nowrap shadow-sm">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                            Cobrar
                                        </a>
                                        <?php endif; ?>
                                        <a href="/manager/membros/<?= $membro['id'] ?>" class="text-indigo-600 hover:text-indigo-900 font-medium text-xs flex items-center bg-indigo-50 px-2 py-1 rounded shadow-sm transition whitespace-nowrap">Ver Detalhes</a>
                                    </div>
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
