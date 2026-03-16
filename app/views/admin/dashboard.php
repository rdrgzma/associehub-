<?php require_once '../app/views/layouts/header.php'; ?>

<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Geral</h1>
        <p class="text-sm text-gray-500 mt-1">Bem-vindo, <?= htmlspecialchars($_SESSION['admin_nome'] ?? 'Administrador') ?></p>
    </div>
    <!-- Link de Cadastro de Nova Associação -->
    <?php
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $novaAssocLink = "{$protocol}://{$host}/nova-associacao";
        $waMsg = urlencode("Olá! Acesse o link para cadastrar sua associação na plataforma AssocieHub: {$novaAssocLink}");
    ?>
    <div class="flex-grow max-w-md w-full bg-indigo-50 border border-indigo-100 rounded-xl p-4">
        <label class="block text-xs font-bold text-indigo-900 uppercase tracking-widest mb-2">Link de Cadastro de Nova Associação</label>
        <div class="flex items-center space-x-2">
            <input type="text" readonly value="<?= $novaAssocLink ?>" id="adminAssocLink"
                   class="flex-grow bg-white border border-indigo-200 rounded-lg px-3 py-2 text-sm text-indigo-700 font-medium focus:outline-none truncate">
            <button onclick="copyAdminLink()" class="bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-lg transition shadow-sm flex-shrink-0" title="Copiar Link">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
            </button>
            <a href="https://wa.me/?text=<?= $waMsg ?>" target="_blank"
               class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-lg transition shadow-sm flex-shrink-0" title="Compartilhar via WhatsApp">
                <svg class="w-4 h-4" viewBox="0 0 32 32" fill="currentColor"><path d="M16 2C8.268 2 2 8.268 2 16c0 2.478.657 4.802 1.803 6.818L2 30l7.379-1.779A13.946 13.946 0 0016 30c7.732 0 14-6.268 14-14S23.732 2 16 2zm0 25.5a11.44 11.44 0 01-5.832-1.601l-.418-.248-4.379 1.055 1.082-4.27-.271-.437A11.44 11.44 0 014.5 16C4.5 9.597 9.597 4.5 16 4.5S27.5 9.597 27.5 16 22.403 27.5 16 27.5zm6.27-8.567c-.344-.172-2.037-1.004-2.353-1.12-.316-.115-.547-.172-.778.172-.23.344-.893 1.12-1.095 1.35-.2.23-.402.258-.746.086-.344-.172-1.451-.535-2.764-1.705-1.021-.91-1.71-2.035-1.911-2.379-.2-.344-.021-.53.15-.702.154-.154.344-.402.516-.603.172-.2.23-.344.344-.574.115-.23.058-.43-.028-.603-.086-.172-.778-1.876-1.066-2.57-.28-.672-.566-.58-.778-.59l-.66-.012c-.23 0-.603.086-.92.43-.316.344-1.208 1.18-1.208 2.878s1.237 3.34 1.409 3.57c.172.23 2.435 3.717 5.898 5.211.824.355 1.467.567 1.969.727.827.264 1.58.226 2.174.137.663-.1 2.037-.832 2.325-1.635.287-.803.287-1.491.2-1.635-.085-.143-.316-.23-.66-.402z"/></svg>
            </a>
        </div>
        <p class="text-[10px] text-indigo-500 mt-2 font-medium italic">Compartilhe com gestores para cadastrar novas associações na plataforma.</p>
    </div>
</div>
<!-- Toast for admin link copy -->
<div id="adminCopyToast" class="hidden fixed bottom-6 right-6 bg-gray-900 text-white text-sm font-medium px-4 py-2.5 rounded-xl shadow-xl z-50 flex items-center space-x-2">
    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
    <span>Link copiado!</span>
</div>
<script>
    function copyAdminLink() {
        navigator.clipboard.writeText(document.getElementById('adminAssocLink').value).then(() => {
            const t = document.getElementById('adminCopyToast');
            t.classList.remove('hidden');
            setTimeout(() => t.classList.add('hidden'), 2500);
        });
    }
</script>

<!-- KPI Metrics -->
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

<!-- Admin Settings -->
<div class="mb-8 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center cursor-pointer" onclick="document.getElementById('password-form-container').classList.toggle('hidden')">
        <h3 class="text-sm font-bold text-gray-800 flex items-center space-x-2">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <span>Configurações de Conta (Admin)</span>
        </h3>
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </div>
    
    <div id="password-form-container" class="hidden p-6">
        <form action="/admin/alterar-senha" method="POST" class="max-w-md">
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

        <!-- Divider -->
        <div class="border-t border-gray-100 my-6"></div>

        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Gerenciamento do Sistema</h4>
        <div class="flex items-center space-x-4">
            <a href="/admin/usuarios" class="inline-flex items-center justify-center bg-gray-900 hover:bg-black text-white px-5 py-2.5 rounded-lg text-sm font-bold shadow-sm transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Contas Administrativas
            </a>
            <p class="text-xs text-gray-500">Cadastre e remova privilégios de outros administradores.</p>
        </div>
    </div>
</div>

<!-- Payment (PIX) Settings -->
<div class="mb-8 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-4 border-b border-gray-100 bg-emerald-50 flex justify-between items-center cursor-pointer" onclick="document.getElementById('pix-form-container').classList.toggle('hidden')">
        <h3 class="text-sm font-bold text-emerald-800 flex items-center space-x-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>Configurações de Pagamento Global (PIX)</span>
        </h3>
        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </div>
    
    <div id="pix-form-container" class="hidden p-6 bg-white">
        <form action="/admin/salvar-pix" method="POST" class="max-w-2xl">
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Dados Financeiros para Cobrança Automática</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Chave PIX Recebedora *</label>
                    <input type="text" name="pix_chave" value="<?= htmlspecialchars($config['pix_chave'] ?? '') ?>" required placeholder="CNPJ, E-mail ou Telefone" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Valor Unitário do Cadastro (R$) *</label>
                    <input type="text" name="pix_valor_cadastro" value="<?= htmlspecialchars($config['pix_valor_cadastro'] ?? '') ?>" required placeholder="Ex: 50,00" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1">Instruções Extras para o Fim da Ficha Impressa</label>
                <textarea name="pix_instrucoes" rows="3" placeholder="Insira o nome do Recebedor ou Banco para conferência..." class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition resize-none"><?= htmlspecialchars($config['pix_instrucoes'] ?? '') ?></textarea>
            </div>
            <div class="pt-2 flex items-center justify-between">
                <p class="text-xs text-gray-500 w-3/4">Eles serão injetados automaticamente no Botão do WhatsApp dos Gestores e no verso do Relatório de Impressão.</p>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-6 rounded-lg text-sm transition shadow-sm w-1/4">
                    Salvar PIX
                </button>
            </div>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center">
        <dt class="text-sm font-medium text-gray-500 mb-1">Total de Associações</dt>
        <dd class="text-3xl font-bold text-indigo-700"><?= $metrics['total_associacoes'] ?></dd>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center">
        <dt class="text-sm font-medium text-gray-500 mb-1">Associações Pendentes</dt>
        <dd class="text-3xl font-bold text-amber-600"><?= $metrics['total_pendentes'] ?></dd>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center">
        <dt class="text-sm font-medium text-gray-500 mb-1">Total de Associados</dt>
        <dd class="text-3xl font-bold text-blue-600"><?= $metrics['total_membros'] ?></dd>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center">
        <dt class="text-sm font-medium text-gray-500 mb-1">Associados Ativos</dt>
        <dd class="text-3xl font-bold text-emerald-600"><?= $metrics['membros_ativos'] ?></dd>
    </div>
</div>

<!-- Financial Module Shortcut -->
<div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-2 bg-gradient-to-r from-indigo-600 to-emerald-600 rounded-xl shadow-lg p-6 text-white flex justify-between items-center transition hover:scale-[1.01]">
        <div>
            <h3 class="text-sm font-bold uppercase tracking-widest opacity-80 mb-1">Gestão Financeira Global</h3>
            <p class="text-2xl font-black leading-none">Módulo de Pagamentos e Cobranças</p>
            <p class="mt-2 text-xs opacity-90">Gerencie todos os pagamentos de associações e seus respectivos membros em um único lugar.</p>
        </div>
        <a href="/admin/financeiro" class="bg-white text-indigo-700 hover:bg-indigo-50 font-black py-4 px-8 rounded-2xl shadow-xl transition-all transform active:scale-95">
            ACESSAR PAINEL
        </a>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-indigo-100 p-6 flex flex-col justify-center border-l-4 border-l-emerald-500">
        <dt class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Receita Confirmada</dt>
        <dd class="text-2xl font-black text-emerald-600">
            R$ <?= number_format($financeiroMetrics['total_geral'] ?? 0, 2, ',', '.') ?>
        </dd>
    </div>
</div>


<!-- Pending Associations -->

<!-- Pending Associations -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-amber-50">
        <h2 class="text-lg font-semibold text-amber-800">Associações Pendentes de Aprovação</h2>
    </div>
    
    <?php if(empty($pending)): ?>
        <div class="p-6 text-center text-gray-500">Nenhuma associação pendente no momento.</div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm">
                        <th class="py-3 px-6 font-medium">Nome</th>
                        <th class="py-3 px-6 font-medium">CNPJ</th>
                        <th class="py-3 px-6 font-medium">Responsável</th>
                        <th class="py-3 px-6 font-medium">Data</th>
                        <th class="py-3 px-6 font-medium text-right">Ação</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    <?php foreach($pending as $assoc): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-6 text-gray-900 font-medium"><?= htmlspecialchars($assoc['nome']) ?></td>
                        <td class="py-3 px-6 text-gray-600"><?= htmlspecialchars($assoc['cnpj']) ?></td>
                        <td class="py-3 px-6 text-gray-600"><?= htmlspecialchars($assoc['responsavel']) ?></td>
                        <td class="py-3 px-6 text-gray-500"><?= date('d/m/Y', strtotime($assoc['created_at'])) ?></td>
                        <td class="py-3 px-6 text-right space-x-2">
                            <a href="/admin/associacoes/<?= $assoc['id'] ?>/aprovar" class="inline-block bg-green-50 text-green-700 hover:bg-green-100 px-3 py-1 rounded-md text-xs font-semibold transition border border-green-200">Aprovar</a>
                            
                            <form action="/admin/associacoes/<?= $assoc['id'] ?>/rejeitar" method="POST" class="inline-block">
                                <button type="submit" class="bg-red-50 text-red-700 hover:bg-red-100 px-3 py-1 rounded-md text-xs font-semibold transition border border-red-200" onclick="return confirm('Tem certeza que deseja rejeitar?')">Rejeitar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- All Associations summary -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">Resumo das Associações</h2>
        <a href="/admin/associacoes" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Ver todas &rarr;</a>
    </div>
    
    <?php if(empty($associacoes)): ?>
        <div class="p-6 text-center text-gray-500">Nenhuma associação cadastrada no momento.</div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                        <th class="py-3 px-6 font-medium">Associação</th>
                        <th class="py-3 px-6 font-medium">Status</th>
                        <th class="py-3 px-6 font-medium">Membros</th>
                        <th class="py-3 px-6 font-medium text-right">Detalhes</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    <?php 
                    $count = 0;
                    foreach($associacoes as $assoc): 
                        if ($count++ >= 5) break; // show only 5 latest
                    ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-6 text-gray-900 font-medium"><?= htmlspecialchars($assoc['nome']) ?></td>
                        <td class="py-3 px-6">
                            <?php if($assoc['status'] == 'approved'): ?>
                                <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded-full text-xs font-medium border border-green-200">Aprovada</span>
                            <?php elseif($assoc['status'] == 'rejected'): ?>
                                <span class="bg-red-100 text-red-800 px-2 py-0.5 rounded-full text-xs font-medium border border-red-200">Rejeitada</span>
                            <?php else: ?>
                                <span class="bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full text-xs font-medium border border-amber-200">Pendente</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-6 text-gray-600 font-medium"><?= $assoc['total_membros'] ?></td>
                        <td class="py-3 px-6 text-right">
                            <?php if($assoc['status'] == 'approved'): ?>
                                <a href="/admin/associacoes/<?= $assoc['id'] ?>/membros" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">Ver Membros</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
