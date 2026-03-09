<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Associado - AssocieHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <header class="bg-white shadow relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <!-- This nav handles both admin and manager contexts smoothly using HTTP_REFERER if needed, but we'll use a dynamic back link for safety -->
            <div class="flex items-center space-x-4">
                <a href="javascript:history.back()" class="text-gray-600 hover:text-gray-900 font-medium text-sm border border-gray-300 px-3 py-1.5 rounded-lg">&larr; Voltar</a>
                <h1 class="text-xl font-bold text-gray-900 tracking-tight">Detalhes do Associado</h1>
                
                <?php 
                    $membro_id = $membro['id'];
                    if (isset($_SESSION['admin_id'])) {
                        $editUrl = "/admin/associados/{$membro_id}/editar";
                        $postUrl = "/admin/associados/{$membro_id}/atualizar-status-global";
                        $postDocUrl = "/admin/associados/{$membro_id}/atualizar";
                        $deleteUrl = "/admin/associados/{$membro_id}/deletar";
                    } else {
                        $editUrl = "/manager/membros/{$membro_id}/editar";
                        $postUrl = "/manager/membros/{$membro_id}/atualizar-status-global";
                        $postDocUrl = "/manager/membros/{$membro_id}/atualizar";
                        $deleteUrl = "/manager/membros/{$membro_id}/deletar";
                    }
                ?>
                <div class="ml-auto flex space-x-3">
                    <a href="<?= $editUrl ?>" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-1.5 px-4 rounded-lg text-sm flex items-center shadow-sm transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Editar Cadastro
                    </a>
                    <a href="/manager/membros/<?= $membro['id'] ?>/ficha" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-1.5 px-4 rounded-lg text-sm flex items-center shadow-sm transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Imprimir Ficha
                    </a>
                    <button onclick="toggleDeleteModal(true)" class="bg-white border border-red-200 hover:bg-red-50 text-red-600 font-medium py-1.5 px-4 rounded-lg text-sm flex items-center shadow-sm transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Excluir
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow max-w-5xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Resumo Rápido -->
        <div class="bg-indigo-900 rounded-xl shadow-lg p-6 mb-8 text-white flex flex-col md:flex-row justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="h-16 w-16 bg-white/20 rounded-full flex items-center justify-center text-2xl font-bold border-2 border-white/30">
                    <?= strtoupper(substr($membro['nome'], 0, 1)) ?>
                </div>
                <div>
                    <h2 class="text-2xl font-bold"><?= htmlspecialchars($membro['nome']) ?></h2>
                    <p class="text-indigo-200 text-sm">CPF: <?= htmlspecialchars($membro['cpf']) ?> • ID #<?= $membro['id'] ?></p>
                </div>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-4">
                <div class="text-right">
                    <p class="text-indigo-300 text-xs uppercase tracking-wider font-semibold">Situação</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold <?= $membro['situacao'] ? 'bg-green-500 text-white' : 'bg-red-500 text-white' ?>">
                        <?= $membro['situacao'] ? 'ATIVO' : 'INATIVO' ?>
                    </span>
                </div>
                <div class="text-right">
                    <p class="text-indigo-300 text-xs uppercase tracking-wider font-semibold">Validade</p>
                    <p class="font-bold"><?= $membro['validade'] ? date('d/m/Y', strtotime($membro['validade'])) : 'N/A' ?></p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <!-- Dados Pessoais -->
            <div class="md:col-span-2 space-y-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center">
                        <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <h3 class="font-bold text-gray-900">Dados Pessoais e Identificação</h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <div>
                                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Nacionalidade</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($membro['nacionalidade'] ?? '---') ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Naturalidade</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($membro['naturalidade'] ?? '---') ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Data Nasc.</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?= $membro['data_nascimento'] ? date('d/m/Y', strtotime($membro['data_nascimento'])) : '---' ?> (<?= $membro['idade'] ?? '--' ?> anos)</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">RG</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($membro['rg'] ?? '---') ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Órgão Emissor</dt>
                                <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($membro['rg_orgao_emissor'] ?? '---') ?></dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">CPF</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-semibold text-indigo-700"><?= htmlspecialchars($membro['cpf']) ?></dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Filiação e Família -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center">
                        <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <h3 class="font-bold text-gray-900">Estrutura Familiar e Filiação</h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 gap-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Filiação (1)</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($membro['filiacao_1_nome'] ?? '---') ?></dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">CPF Filiação (1)</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($membro['filiacao_1_cpf'] ?? '---') ?></dd>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Filiação (2)</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($membro['filiacao_2_nome'] ?? '---') ?></dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">CPF Filiação (2)</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($membro['filiacao_2_cpf'] ?? '---') ?></dd>
                                </div>
                            </div>
                            <div class="border-t border-gray-100 pt-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Estado Civil</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($membro['estado_civil'] ?? '---') ?></dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Regime de Bens</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($membro['forma_comunhao'] ?? '---') ?></dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Cônjuge</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($membro['conjuge_nome'] ?? '---') ?></dd>
                                </div>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Endereço e Profissional -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center">
                        <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <h3 class="font-bold text-gray-900">Localização e Carreira</h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 gap-6">
                            <div>
                                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Endereço Residencial</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <?= htmlspecialchars($membro['endereco'] ?? '') ?>, <?= htmlspecialchars($membro['numero'] ?? 'S/N') ?> 
                                    <?= $membro['complemento'] ? ' - ' . htmlspecialchars($membro['complemento']) : '' ?><br>
                                    <?= htmlspecialchars($membro['bairro'] ?? '') ?> • <?= htmlspecialchars($membro['cidade'] ?? '') ?> - <?= htmlspecialchars($membro['estado'] ?? '') ?> • CEP: <?= htmlspecialchars($membro['cep'] ?? '') ?>
                                </dd>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-gray-100 pt-4">
                                <div>
                                    <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Profissão (1)</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($membro['profissao_1'] ?? '---') ?> (<?= htmlspecialchars($membro['profissao_1_orgao'] ?? '') ?>: <?= htmlspecialchars($membro['profissao_1_registro'] ?? '') ?>)</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Profissão (2)</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($membro['profissao_2'] ?? '---') ?> <?= $membro['profissao_2_registro'] ? '(' . htmlspecialchars($membro['profissao_2_orgao'] ?? '') . ': ' . htmlspecialchars($membro['profissao_2_registro'] ?? '') . ')' : '' ?></dd>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-gray-100 pt-4">
                                <div>
                                    <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Telefone</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-bold"><?= htmlspecialchars($membro['telefone']) ?></dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">E-mail</dt>
                                    <dd class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($membro['email']) ?></dd>
                                </div>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Coluna Sidebar -->
            <div class="space-y-8">
                <!-- Controle de Situação -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-bold text-gray-900">Controle Interno</h3>
                    </div>
                    <div class="p-6">
                        <form action="<?= $postUrl ?>" method="POST" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Situação Cadastral</label>
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <input type="checkbox" name="situacao" value="1" <?= $membro['situacao'] ? 'checked' : '' ?> class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <span class="text-sm font-bold text-gray-700">Membro Ativo</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Validade Geral</label>
                                <input type="date" name="validade" value="<?= htmlspecialchars($membro['validade'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-2.5 rounded-lg text-sm hover:bg-indigo-700 transition shadow-sm">
                                Atualizar Status
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Metadata -->
                <div class="bg-indigo-50 rounded-xl p-6 border border-indigo-100">
                    <h4 class="text-xs font-bold text-indigo-900 uppercase tracking-widest mb-4">Metadados do Registro</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between text-xs">
                            <span class="text-indigo-600">ID Único</span>
                            <span class="font-mono font-bold text-indigo-900">#<?= str_pad($membro['id'], 6, '0', STR_PAD_LEFT) ?></span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-indigo-600">Cadastrado em</span>
                            <span class="font-bold text-indigo-900"><?= date('d/m/Y', strtotime($membro['created_at'])) ?></span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-indigo-600">Horário</span>
                            <span class="font-bold text-indigo-900"><?= date('H:i:s', strtotime($membro['created_at'])) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documentos em Grade -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-12">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-bold text-gray-900">Validação Documental</h3>
                    <p class="text-xs text-gray-500">Análise técnica dos arquivos anexados</p>
                </div>
            </div>
            
            <?php
            $documentos = [
                'doc_identidade' => 'Cópia do Documento de Identificação',
                'doc_quitacao_eleitoral' => 'Comprovante de Quitação Eleitoral',
                'doc_fiscal_federal' => 'Certidão de Situação Fiscal Federal',
                'doc_fiscal_estadual' => 'Certidão de Situação Fiscal Estadual',
                'doc_fiscal_municipal' => 'Certidão de Situação Fiscal Municipal',
                'doc_situacao_cpf' => 'Comprovante de Situação Cadastral no CPF'
            ];
            ?>
            <form action="<?= $postDocUrl ?>" method="POST">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach($documentos as $key => $label): ?>
                            <div class="border border-gray-200 rounded-xl p-4 bg-gray-50/30 flex flex-col h-full">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 mr-3">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <span class="text-sm font-bold text-gray-800"><?= $label ?></span>
                                    </div>
                                    <?php if(!empty($membro[$key])): ?>
                                        <a href="<?= htmlspecialchars($membro[$key]) ?>" target="_blank" class="text-indigo-600 hover:text-indigo-800 p-1 rounded-md hover:bg-indigo-50 transition" title="Ver Documento">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </a>
                                    <?php endif; ?>
                                </div>

                                <div class="mt-auto space-y-3">
                                    <div class="flex items-center justify-between">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="<?= $key ?>_status" value="1" <?= ($membro[$key.'_status'] ?? 0) ? 'checked' : '' ?> class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                            <span class="ml-2 text-xs font-bold text-gray-700">Documento Válido</span>
                                        </label>
                                        <?php if(empty($membro[$key])): ?>
                                            <span class="text-[10px] font-bold text-red-500 bg-red-50 px-2 py-0.5 rounded border border-red-100">PENDENTE</span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Data de Validade</label>
                                        <input type="date" name="<?= $key ?>_validade" value="<?= htmlspecialchars($membro[$key.'_validade'] ?? '') ?>" class="w-full px-2 py-1.5 text-xs border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm">
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="px-6 py-5 bg-gray-50 border-t border-gray-200 text-right">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-2.5 rounded-lg text-sm font-bold shadow-md transition-all active:scale-95">
                        Salvar Conferência Documental
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Modal de Exclusão -->
    <div id="deleteModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="toggleDeleteModal(false)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-red-100">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">Excluir Associado</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Esta ação é <span class="font-bold text-red-600">irreversível</span>. Ao confirmar, todos os dados de <span class="font-bold"><?= htmlspecialchars($membro['nome']) ?></span> e seus documentos anexados serão removidos permanentemente do servidor.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <form action="<?= $deleteUrl ?>" method="POST">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-bold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                            Confirmar Exclusão
                        </button>
                    </form>
                    <button type="button" onclick="toggleDeleteModal(false)" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDeleteModal(show) {
            const modal = document.getElementById('deleteModal');
            if (show) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }
    </script>
</body>
</html>
