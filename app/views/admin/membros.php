<?php require_once '../app/views/layouts/header.php'; ?>

<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Membros Associados</h1>
        <p class="text-sm text-gray-500 mt-1">
            Associação: <span class="font-semibold text-indigo-700"><?= htmlspecialchars($associacao['nome']) ?></span>
            &bull; CNPJ: <?= htmlspecialchars($associacao['cnpj']) ?>
        </p>
    </div>
    <div class="flex space-x-4 items-center">
        <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-semibold border border-indigo-200">
            Total na Lista: <?= count($membros) ?>
        </span>
        <a href="/admin/associacoes/<?= $associacao['id'] ?>/nominata" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm px-4 py-1.5 rounded-lg shadow-sm transition flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Gerenciar Nominata
        </a>
        <button onclick="openPrintOptions('/admin/associacoes/<?= $associacao['id'] ?>/imprimir')" class="bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 font-bold text-sm px-4 py-1.5 rounded-lg shadow-sm transition flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Imprimir Lista
        </button>
        <?php $baseUrl = str_replace('/public/index.php', '', $_SERVER['SCRIPT_NAME']); ?>
        <a href="/admin/associacoes" class="text-gray-600 hover:text-gray-900 font-medium text-sm border border-gray-300 px-3 py-1.5 rounded-lg">&larr; Voltar</a>
    </div>
</div>

<!-- Print Options Modal -->
<div id="printModal" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden border border-gray-100">
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

<!-- Nominata Section for SuperAdmin -->
<div class="mb-8 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
        <h3 class="text-sm font-bold text-gray-800 flex items-center space-x-2">
            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <span>Nominata da Associação (Diretoria)</span>
        </h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <?php foreach($nominata as $pos): ?>
                <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 shadow-sm">
                    <div class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-1"><?= htmlspecialchars($pos['cargo']) ?></div>
                    <div class="text-sm font-bold text-gray-800">
                        <?= $pos['associado_nome'] ? htmlspecialchars($pos['associado_nome']) : '<span class="text-gray-300 font-normal italic">Não atribuído</span>' ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- KPI Metrics for this Association -->
<dl class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center relative overflow-hidden">
        <div class="absolute right-0 top-0 bottom-0 w-2 bg-indigo-500"></div>
        <dt class="text-sm font-medium text-gray-500 mb-1">Membros Cadastrados</dt>
        <dd class="text-3xl font-bold text-gray-900"><?= $metrics['total_membros'] ?></dd>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center relative overflow-hidden">
        <div class="absolute right-0 top-0 bottom-0 w-2 bg-emerald-500"></div>
        <dt class="text-sm font-medium text-gray-500 mb-1">Membros Ativos</dt>
        <dd class="text-3xl font-bold text-gray-900"><?= $metrics['membros_ativos'] ?></dd>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center relative overflow-hidden">
        <div class="absolute right-0 top-0 bottom-0 w-2 bg-red-500"></div>
        <dt class="text-sm font-medium text-gray-500 mb-1">Membros Inativos/Pendentes</dt>
        <dd class="text-3xl font-bold text-gray-900"><?= $metrics['membros_inativos'] ?></dd>
    </div>
</dl>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-8">
    <form action="/admin/associacoes/<?= $associacao['id'] ?>/membros" method="GET" class="flex flex-col md:flex-row gap-4">
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
                <a href="/admin/associacoes/<?= $associacao['id'] ?>/membros" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold py-2 px-4 rounded-lg text-sm transition">
                    Limpar
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <?php if(empty($membros)): ?>
        <div class="p-8 text-center text-gray-500">
            <p class="mb-2 text-lg">Nenhum membro registrado ainda.</p>
            <p class="text-sm">A associação precisa compartilhar o link de cadastro com seus integrantes.</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                        <th class="py-3 px-6 font-medium">Nome</th>
                        <th class="py-3 px-6 font-medium">Contato</th>
                        <th class="py-3 px-6 font-medium">Endereço</th>
                        <th class="py-3 px-6 font-medium">Cargo/Função</th>
                        <th class="py-3 px-6 font-medium">Registro</th>
                        <th class="py-3 px-6 font-medium text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    <?php foreach($membros as $membro): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-6 text-gray-900 font-medium">
                            <?= htmlspecialchars($membro['nome']) ?>
                            <div class="text-xs text-gray-500 font-normal">CPF: <?= htmlspecialchars($membro['cpf']) ?></div>
                        </td>
                        <td class="py-3 px-6 text-gray-600">
                            <?= htmlspecialchars($membro['email']) ?>
                            <div class="text-xs text-gray-500">Tel: <?= htmlspecialchars($membro['telefone']) ?></div>
                        </td>
                        <td class="py-3 px-6 text-gray-600">
                            <div class="truncate w-48" title="<?= htmlspecialchars($membro['endereco']) ?>">
                                <?= htmlspecialchars($membro['endereco']) ?>
                            </div>
                            <div class="text-xs text-gray-500">
                                <?= htmlspecialchars($membro['cidade']) ?> - <?= htmlspecialchars($membro['estado']) ?>
                            </div>
                        </td>
                        <td class="py-3 px-6 text-gray-600">
                            <?php if(!empty($membro['cargo_nominata'])): ?>
                                <span class="bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-md text-[10px] font-bold border border-indigo-100 uppercase tracking-tighter">
                                    <?= htmlspecialchars($membro['cargo_nominata']) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-gray-300 italic text-[11px]">Membro</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-6 text-gray-500 font-medium">
                            <div><?= date('d/m/Y', strtotime($membro['created_at'])) ?></div>
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

<?php require_once '../app/views/layouts/footer.php'; ?>
