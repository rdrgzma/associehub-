<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel da Associação - AssocieHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <span class="text-xl">⚡</span>
                    <span class="text-lg font-bold text-gray-900 tracking-tight"><?= htmlspecialchars($associacao_nome) ?></span>
                    <span class="px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 text-[10px] font-semibold">Manager</span>
                </div>
                <nav class="flex items-center space-x-1 text-sm font-medium">
                    <a href="/manager/dashboard" class="text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition">Painel da Associação</a>
                    <a href="/manager/financeiro" class="text-gray-600 hover:text-gray-900 hover:bg-gray-100 px-3 py-1.5 rounded-lg transition">Financeiro</a>
                    <span class="text-gray-300">|</span>
                    <span class="text-gray-500 text-xs">Olá, <strong class="text-gray-700"><?= htmlspecialchars($_SESSION['manager_nome'] ?? '') ?></strong></span>
                    <a href="/manager/logout" class="text-red-600 hover:text-red-800 hover:bg-red-50 px-3 py-1.5 rounded-lg transition">Sair</a>
                </nav>
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
                    $whatsappMsg = urlencode("Olá! Clique no link para se cadastrar como membro da nossa associação: {$registrationLink}");
                ?>
                <div class="flex-grow max-w-md w-full bg-indigo-50 border border-indigo-100 rounded-xl p-4">
                    <label class="block text-xs font-bold text-indigo-900 uppercase tracking-widest mb-2">Link de Cadastro para Novos Membros</label>
                    <div class="flex items-center space-x-2">
                        <input type="text" readonly value="<?= $registrationLink ?>" 
                               id="regLink"
                               class="flex-grow bg-white border border-indigo-200 rounded-lg px-3 py-2 text-sm text-indigo-700 font-medium focus:outline-none truncate">
                        <!-- Copy button -->
                        <button onclick="copyLink()" id="copyBtn" class="bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-lg transition shadow-sm flex-shrink-0" title="Copiar Link">
                            <svg id="copyIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                        </button>
                        <!-- WhatsApp share button -->
                        <a href="https://wa.me/?text=<?= $whatsappMsg ?>" target="_blank" 
                           class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-lg transition shadow-sm flex-shrink-0" 
                           title="Compartilhar via WhatsApp">
                            <svg class="w-5 h-5" viewBox="0 0 32 32" fill="currentColor">
                                <path d="M16 2C8.268 2 2 8.268 2 16c0 2.478.657 4.802 1.803 6.818L2 30l7.379-1.779A13.946 13.946 0 0016 30c7.732 0 14-6.268 14-14S23.732 2 16 2zm0 25.5a11.44 11.44 0 01-5.832-1.601l-.418-.248-4.379 1.055 1.082-4.27-.271-.437A11.44 11.44 0 014.5 16C4.5 9.597 9.597 4.5 16 4.5S27.5 9.597 27.5 16 22.403 27.5 16 27.5zm6.27-8.567c-.344-.172-2.037-1.004-2.353-1.12-.316-.115-.547-.172-.778.172-.23.344-.893 1.12-1.095 1.35-.2.23-.402.258-.746.086-.344-.172-1.451-.535-2.764-1.705-1.021-.91-1.71-2.035-1.911-2.379-.2-.344-.021-.53.15-.702.154-.154.344-.402.516-.603.172-.2.23-.344.344-.574.115-.23.058-.43-.028-.603-.086-.172-.778-1.876-1.066-2.57-.28-.672-.566-.58-.778-.59l-.66-.012c-.23 0-.603.086-.92.43-.316.344-1.208 1.18-1.208 2.878s1.237 3.34 1.409 3.57c.172.23 2.435 3.717 5.898 5.211.824.355 1.467.567 1.969.727.827.264 1.58.226 2.174.137.663-.1 2.037-.832 2.325-1.635.287-.803.287-1.491.2-1.635-.085-.143-.316-.23-.66-.402z"/>
                            </svg>
                        </a>
                    </div>
                    <p class="text-[10px] text-indigo-500 mt-2 font-medium italic">Compartilhe este link com os membros para que eles preencham o formulário.</p>
                </div>
                <!-- Toast notification -->
                <div id="copyToast" class="hidden fixed bottom-6 right-6 bg-gray-900 text-white text-sm font-medium px-4 py-2.5 rounded-xl shadow-xl z-50 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Link copiado!</span>
                </div>
                <script>
                    function copyLink() {
                        const text = document.getElementById("regLink").value;
                        navigator.clipboard.writeText(text).then(() => {
                            const toast = document.getElementById("copyToast");
                            toast.classList.remove("hidden");
                            setTimeout(() => toast.classList.add("hidden"), 2500);
                        });
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

        <!-- Manager Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Account Settings -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
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

            <!-- Nominata Setting -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-800 flex items-center space-x-2">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>Nominata da Associação</span>
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">Atribua cargos e funções aos membros da diretoria.</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="/manager/nominata" class="bg-white hover:bg-indigo-50 text-indigo-600 border border-indigo-200 font-bold py-2 px-4 rounded-lg text-sm transition shadow-sm flex items-center justify-center">
                        Gerenciar Nominata
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                    <button onclick="openPrintOptions('/manager/membros/imprimir')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition shadow-sm flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Imprimir Lista
                    </button>
                </div>
            </div>

            <!-- Financeiro Shortcut -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-800 flex items-center space-x-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Módulo Financeiro</span>
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">Acompanhe pagamentos de membros e recorrências.</p>
                </div>
                <div>
                    <a href="/manager/financeiro" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition shadow-sm flex items-center justify-center">
                        Ver Financeiro
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </div>

        <?php $baseUrl = str_replace('/public/index.php', '', $_SERVER['SCRIPT_NAME']); ?>

        <!-- Print Options Modal -->
        <div id="printModal" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden border border-gray-100 animate-fadeIn">
                <div class="px-6 py-4 bg-indigo-600 text-white flex items-center space-x-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <h3 class="font-bold text-lg">Imprimir Lista</h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-5">Deseja incluir o resumo da <strong>Nominata (Diretoria)</strong> no topo da lista impressa?</p>
                    <div class="flex flex-col space-y-2">
                        <button onclick="doPrint(true)" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-xl transition text-sm">
                            ✅ Sim, incluir Nominata
                        </button>
                        <button onclick="doPrint(false)" class="w-full bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 font-bold py-2.5 px-4 rounded-xl transition text-sm">
                            Não, apenas a lista de membros
                        </button>
                        <button onclick="closePrintModal()" class="w-full text-gray-400 hover:text-gray-600 font-medium py-2 text-xs transition">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            let _printUrl = '';
            function openPrintOptions(relativeUrl) {
                const baseUrl = '<?= $baseUrl ?>';
                _printUrl = baseUrl + relativeUrl;
                document.getElementById('printModal').classList.remove('hidden');
            }
            function doPrint(withNominata) {
                const url = _printUrl + (withNominata ? '?nominata=1' : '?nominata=0');
                window.open(url, '_blank');
                closePrintModal();
            }
            function closePrintModal() {
                document.getElementById('printModal').classList.add('hidden');
            }
        </script>

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

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-gray-900">Lista de Associados</h2>
            <button onclick="openPrintOptions('/manager/membros/imprimir')" class="bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 font-bold text-xs px-3 py-1.5 rounded-lg shadow-sm transition flex items-center">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Imprimir Lista
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <form action="/manager/dashboard" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label for="search" class="block text-xs font-medium text-gray-500 mb-1">Pesquisar Membro</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" name="search" id="search" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" placeholder="Nome, CPF, Tel ou Email..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div class="w-full md:w-48">
                    <label for="situacao" class="block text-xs font-medium text-gray-500 mb-1">Situação (Presença)</label>
                    <select name="situacao" id="situacao" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Todas</option>
                        <option value="1" <?= (string)($filters['situacao'] ?? '') === '1' ? 'selected' : '' ?>>Ativo</option>
                        <option value="0" <?= (string)($filters['situacao'] ?? '') === '0' ? 'selected' : '' ?>>Inativo/Pendente</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg text-sm transition shadow-sm">
                        Filtrar
                    </button>
                    <?php if (!empty($filters['search']) || isset($filters['situacao'])): ?>
                        <a href="/manager/dashboard" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2 px-4 rounded-lg text-sm transition">
                            Limpar
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

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
                                <th class="py-3 px-6 font-medium text-center">Cargo/Função</th>
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
                                <td class="py-3 px-6 text-gray-500 font-medium">
                                    <div><?= date('d/m/Y', strtotime($membro['created_at'])) ?></div>
                                    <div class="mt-1">
                                        <?php if($membro['situacao']): ?>
                                            <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded text-[10px] font-bold">Ativo</span>
                                        <?php else: ?>
                                            <span class="bg-red-100 text-red-800 px-2 py-0.5 rounded text-[10px] font-bold">Inativo</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <?php if(!empty($membro['cargo_nominata'])): ?>
                                        <span class="bg-indigo-50 text-indigo-700 px-2 py-1 rounded text-[10px] font-bold uppercase border border-indigo-100 whitespace-nowrap">
                                            <?= htmlspecialchars($membro['cargo_nominata']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-300 italic text-[11px]">Membro</span>
                                    <?php endif; ?>
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
