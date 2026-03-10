<?php require_once '../app/views/layouts/header.php'; ?>

<div class="max-w-4xl mx-auto w-full bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mt-6 mb-12">
    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Editar Cadastro</h2>
            <p class="text-sm text-gray-500 mt-1">Atualize os dados e substitua os documentos de <span class="font-semibold text-indigo-700"><?= htmlspecialchars($membro['nome']) ?></span></p>
        </div>
        <a href="javascript:history.back()" class="text-gray-600 hover:text-gray-900 font-medium text-sm border border-gray-300 px-3 py-1.5 rounded-lg bg-white">&larr; Cancelar</a>
    </div>

    <div class="px-6 py-6">
        <?php if(isset($error)): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-6 border border-red-100">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php 
            // Determine the correct post URL based on user role
            $postUrl = isset($_SESSION['admin_id']) 
                ? "/admin/associados/{$membro['id']}/editar" 
                : "/manager/membros/{$membro['id']}/editar";
        ?>

        <form action="<?= $postUrl ?>" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-6 gap-5">
            <div class="md:col-span-6 mb-2">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider border-b border-gray-200 pb-2 mb-4">Informações Pessoais</h3>
            </div>

            <div class="md:col-span-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome Completo</label>
                <input type="text" name="nome" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['nome']) ?>">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nacionalidade</label>
                <input type="text" name="nacionalidade" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['nacionalidade'] ?? '') ?>">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Naturalidade</label>
                <input type="text" name="naturalidade" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['naturalidade'] ?? '') ?>">
            </div>

            <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Data Nasc.</label>
                <input type="date" name="data_nascimento" id="data_nascimento" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['data_nascimento'] ?? '') ?>">
            </div>

            <div class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Idade</label>
                <input type="number" name="idade" id="idade" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 bg-gray-50 text-gray-600 outline-none transition cursor-not-allowed" value="<?= htmlspecialchars($membro['idade'] ?? '') ?>" readonly>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">CPF</label>
                <input type="text" name="cpf" id="cpf" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['cpf']) ?>">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">RG</label>
                <input type="text" name="rg" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['rg'] ?? '') ?>">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Órgão Emissor</label>
                <input type="text" name="rg_orgao_emissor" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['rg_orgao_emissor'] ?? '') ?>">
            </div>

            <!-- Filiação e Estado Civil -->
            <div class="md:col-span-6 mt-4 mb-2">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider border-b border-gray-200 pb-2 mb-4">Filiação e Estado Civil</h3>
            </div>

            <div class="md:col-span-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Filiação (1)</label>
                <input type="text" name="filiacao_1_nome" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['filiacao_1_nome'] ?? '') ?>">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">CPF Filiação (1)</label>
                <input type="text" name="filiacao_1_cpf" id="filiacao_1_cpf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['filiacao_1_cpf'] ?? '') ?>">
            </div>

            <div class="md:col-span-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Filiação (2)</label>
                <input type="text" name="filiacao_2_nome" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['filiacao_2_nome'] ?? '') ?>">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">CPF Filiação (2)</label>
                <input type="text" name="filiacao_2_cpf" id="filiacao_2_cpf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['filiacao_2_cpf'] ?? '') ?>">
            </div>

            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado Civil</label>
                <select name="estado_civil" id="estado_civil" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    <option value="">Selecione...</option>
                    <option value="Solteiro(a)" <?= ($membro['estado_civil'] ?? '') == 'Solteiro(a)' ? 'selected' : '' ?>>Solteiro(a)</option>
                    <option value="Casado(a)" <?= ($membro['estado_civil'] ?? '') == 'Casado(a)' ? 'selected' : '' ?>>Casado(a)</option>
                    <option value="Divorciado(a)" <?= ($membro['estado_civil'] ?? '') == 'Divorciado(a)' ? 'selected' : '' ?>>Divorciado(a)</option>
                    <option value="Viúvo(a)" <?= ($membro['estado_civil'] ?? '') == 'Viúvo(a)' ? 'selected' : '' ?>>Viúvo(a)</option>
                    <option value="União Estável" <?= ($membro['estado_civil'] ?? '') == 'União Estável' ? 'selected' : '' ?>>União Estável</option>
                </select>
            </div>

            <div class="md:col-span-3" id="container_forma_comunhao">
                <label class="block text-sm font-medium text-gray-700 mb-1">Forma de Comunhão</label>
                <select name="forma_comunhao" id="forma_comunhao" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    <option value="">Selecione...</option>
                    <option value="N/A" <?= ($membro['forma_comunhao'] ?? '') == 'N/A' ? 'selected' : '' ?>>N/A</option>
                    <option value="Comunhão Parcial" <?= ($membro['forma_comunhao'] ?? '') == 'Comunhão Parcial' ? 'selected' : '' ?>>Comunhão Parcial</option>
                    <option value="Comunhão Universal" <?= ($membro['forma_comunhao'] ?? '') == 'Comunhão Universal' ? 'selected' : '' ?>>Comunhão Universal</option>
                    <option value="Separação Total" <?= ($membro['forma_comunhao'] ?? '') == 'Separação Total' ? 'selected' : '' ?>>Separação Total</option>
                    <option value="Participação Final nos Aquestos" <?= ($membro['forma_comunhao'] ?? '') == 'Participação Final nos Aquestos' ? 'selected' : '' ?>>Participação Final nos Aquestos</option>
                </select>
            </div>

            <div class="md:col-span-4" id="container_conjuge_nome">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome do Cônjuge</label>
                <input type="text" name="conjuge_nome" id="conjuge_nome" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['conjuge_nome'] ?? '') ?>">
            </div>
            <div class="md:col-span-2" id="container_conjuge_cpf">
                <label class="block text-sm font-medium text-gray-700 mb-1">CPF do Cônjuge</label>
                <input type="text" name="conjuge_cpf" id="conjuge_cpf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['conjuge_cpf'] ?? '') ?>">
            </div>

            <!-- Profissão -->
            <div class="md:col-span-6 mt-4 mb-2">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider border-b border-gray-200 pb-2 mb-4">Dados Profissionais</h3>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Profissão (1)</label>
                <input type="text" name="profissao_1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['profissao_1'] ?? '') ?>">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Reg./Órgão (1)</label>
                <input type="text" name="profissao_1_registro" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['profissao_1_registro'] ?? '') ?>" placeholder="Nº Registro">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Órgão Emissor (1)</label>
                <input type="text" name="profissao_1_orgao" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['profissao_1_orgao'] ?? '') ?>">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Profissão (2)</label>
                <input type="text" name="profissao_2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['profissao_2'] ?? '') ?>">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Reg./Órgão (2)</label>
                <input type="text" name="profissao_2_registro" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['profissao_2_registro'] ?? '') ?>" placeholder="Nº Registro">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Órgão Emissor (2)</label>
                <input type="text" name="profissao_2_orgao" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['profissao_2_orgao'] ?? '') ?>">
            </div>

            <!-- Endereço e Contato -->
            <div class="md:col-span-6 mt-4 mb-2">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider border-b border-gray-200 pb-2 mb-4">Endereço e Contato</h3>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">CEP</label>
                <div class="relative">
                    <input type="text" name="cep" id="cep" required class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['cep'] ?? '') ?>" placeholder="00000-000">
                    <button type="button" id="btn_buscar_cep" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-indigo-600 transition bg-transparent" title="Buscar CEP">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="md:col-span-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Logradouro Residencial</label>
                <input type="text" name="endereco" id="endereco" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['endereco']) ?>" placeholder="Rua, Avenida, etc.">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Número</label>
                <input type="text" name="numero" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['numero'] ?? '') ?>">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Complemento</label>
                <input type="text" name="complemento" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['complemento'] ?? '') ?>">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Bairro</label>
                <input type="text" name="bairro" id="bairro" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['bairro'] ?? '') ?>">
            </div>

            <div class="md:col-span-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Município</label>
                <input type="text" name="cidade" id="cidade" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['cidade']) ?>">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado (UF)</label>
                <input type="text" name="estado" id="estado" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['estado']) ?>" placeholder="Ex: SP">
            </div>

            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Telefone / Mensagens</label>
                <input type="text" name="telefone" id="telefone" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['telefone']) ?>" placeholder="(00) 00000-0000">
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" value="<?= htmlspecialchars($membro['email']) ?>">
            </div>

            <!-- Documents Section -->
            <div class="md:col-span-2 mt-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider border-b border-gray-200 pb-2 mb-4">Substituição de Documentos</h3>
                <p class="text-xs text-amber-600 mb-4 bg-amber-50 p-2 rounded border border-amber-100 font-medium">
                    Aviso: Envie um novo arquivo apenas se desejar substituir o documento atual. Caso contrário, deixe o campo de arquivo vazio e o documento atual será preservado.
                </p>
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
            
            foreach($documentos as $key => $label): 
            ?>
            <div class="md:col-span-2 bg-gray-50 border border-gray-200 rounded-lg p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-800 mb-1"><?= $label ?></label>
                    <?php if(!empty($membro[$key])): ?>
                        <div class="text-xs flex items-center text-green-700 mb-2">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Arquivo atual: <a href="<?= htmlspecialchars($membro[$key]) ?>" target="_blank" class="font-medium underline ml-1 text-indigo-600 hover:text-indigo-800">Ver Anexo</a>
                        </div>
                    <?php else: ?>
                        <div class="text-xs text-red-500 mb-2 font-medium">Nenhum arquivo enviado anteriormente.</div>
                    <?php endif; ?>
                    
                    <input type="file" name="<?= $key ?>" id="<?= $key ?>" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-white file:border-gray-300 file:border file:text-gray-700 hover:file:bg-gray-100 cursor-pointer">
                    <div id="preview-<?= $key ?>" class="mt-2 hidden"></div>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Spouse Documents (Hidden by default unless Casado/União Estável) -->
            <div id="container_docs_conjuge" class="hidden md:col-span-6 grid grid-cols-1 md:grid-cols-6 gap-6 mt-6 pt-6 border-t border-indigo-200">
                <div class="md:col-span-6 mb-2">
                    <h3 class="text-sm font-bold text-indigo-900 uppercase tracking-wider mb-1">Substituição de Documentos (Cônjuge)</h3>
                    <p class="text-xs text-indigo-700">Como o associado declarou ser casado(a) ou estar em união estável, gerencie a documentação do companheiro(a).</p>
                </div>

                <?php
                $documentos_conjuge = [
                    'doc_conjuge_identidade' => 'Identificação do Cônjuge',
                    'doc_conjuge_quitacao_eleitoral' => 'Quitação Eleitoral do Cônjuge',
                    'doc_conjuge_fiscal_federal' => 'Sit. Fiscal Federal do Cônjuge',
                    'doc_conjuge_fiscal_estadual' => 'Sit. Fiscal Estadual do Cônjuge',
                    'doc_conjuge_fiscal_municipal' => 'Sit. Fiscal Municipal do Cônjuge',
                    'doc_conjuge_situacao_cpf' => 'Situação no CPF do Cônjuge'
                ];
                
                foreach($documentos_conjuge as $sp_key => $sp_label): 
                ?>
                <div class="md:col-span-2 bg-indigo-50/50 border border-indigo-100 rounded-lg p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-800 mb-1"><?= $sp_label ?></label>
                        <?php if(!empty($membro[$sp_key])): ?>
                            <div class="text-xs flex items-center text-green-700 mb-2">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                Arquivo atual: <a href="<?= htmlspecialchars($membro[$sp_key]) ?>" target="_blank" class="font-medium underline ml-1 text-indigo-600 hover:text-indigo-800">Ver Anexo</a>
                            </div>
                        <?php else: ?>
                            <div class="text-xs text-amber-600 mb-2 font-medium">Documento ausente.</div>
                        <?php endif; ?>
                        
                        <input type="file" name="<?= $sp_key ?>" id="<?= $sp_key ?>" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-white file:border-indigo-200 file:border file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                        <div id="preview-<?= $sp_key ?>" class="mt-2 hidden"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            </div>

        </form>

        <!-- Submit Button outside the grid to prevent stretching -->
        <div class="mt-8 pt-4 border-t border-gray-200 flex justify-end">
            <button type="submit" form="formEditarAssociado" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-lg transition shadow-md w-full sm:w-auto">
                Salvar Alterações
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Utility: Input Masking
    const applyMask = (input, maskFn) => {
        input.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = maskFn(value);
        });
    };

    const cpfMask = (v) => v.replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d{1,2})$/, '$1-$2').substring(0, 14);
    const cepMask = (v) => v.replace(/(\d{5})(\d)/, '$1-$2').substring(0, 9);
    const phoneMask = (v) => {
        if (v.length > 10) return v.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3').substring(0, 15);
        return v.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3').substring(0, 14);
    };

    const cpfFields = ['cpf', 'filiacao_1_cpf', 'filiacao_2_cpf', 'conjuge_cpf'];
    cpfFields.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            applyMask(el, cpfMask);
            // Trigger initial mask
            el.dispatchEvent(new Event('input'));
        }
    });

    const cepEl = document.getElementById('cep');
    if (cepEl) {
        applyMask(cepEl, cepMask);
        cepEl.dispatchEvent(new Event('input'));
    }

    const phoneEl = document.getElementById('telefone');
    if (phoneEl) {
        applyMask(phoneEl, phoneMask);
        phoneEl.dispatchEvent(new Event('input'));
    }

    // 2. Dynamic Marital Status Fields
    const estadoCivilSelect = document.getElementById('estado_civil');
    const containerComunhao = document.getElementById('container_forma_comunhao');
    const containerConjNome = document.getElementById('container_conjuge_nome');
    const containerConjCpf = document.getElementById('container_conjuge_cpf');
    const containerDocsConjuge = document.getElementById('container_docs_conjuge');
    const inputComunhao = document.getElementById('forma_comunhao');
    const inputConjNome = document.getElementById('conjuge_nome');
    const inputConjCpf = document.getElementById('conjuge_cpf');

    function handleMaritalStatus() {
        if (!estadoCivilSelect) return;
        
        const value = estadoCivilSelect.value;
        const hideFields = ['Solteiro(a)', 'Viúvo(a)', 'Divorciado(a)'].includes(value);

        if (hideFields) {
            containerComunhao.classList.add('hidden');
            containerConjNome.classList.add('hidden');
            containerConjCpf.classList.add('hidden');
            containerDocsConjuge.classList.add('hidden');
            
            inputComunhao.value = 'N/A';
            inputConjNome.value = 'N/A';
            inputConjCpf.value = 'N/A';
        } else {
            containerComunhao.classList.remove('hidden');
            containerConjNome.classList.remove('hidden');
            containerConjCpf.classList.remove('hidden');
            containerDocsConjuge.classList.remove('hidden');
            
            if (inputComunhao.value === 'N/A') inputComunhao.value = '';
            if (inputConjNome.value === 'N/A') inputConjNome.value = '';
            if (inputConjCpf.value === 'N/A') inputConjCpf.value = '';
        }
    }

    if (estadoCivilSelect) {
        estadoCivilSelect.addEventListener('change', handleMaritalStatus);
        handleMaritalStatus(); // Run on load
    }

    // 3. ViaCEP Integration
    const buscarCepHelper = async function() {
        const cep = cepEl.value.replace(/\D/g, '');
        if (cep.length === 8) {
            const btnBuscarCep = document.getElementById('btn_buscar_cep');
            if (btnBuscarCep) btnBuscarCep.innerHTML = '<svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            
            try {
                const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                const data = await response.json();
                if (!data.erro) {
                    document.getElementById('endereco').value = data.logradouro;
                    document.getElementById('bairro').value = data.bairro;
                    document.getElementById('cidade').value = data.localidade;
                    document.getElementById('estado').value = data.uf;
                    // Focus on number field if empty
                    const numEl = document.getElementsByName('numero')[0];
                    if (numEl && !numEl.value) numEl.focus();
                } else {
                    alert('CEP não encontrado. Por favor, verifique se está correto.');
                }
            } catch (err) {
                console.error('ViaCEP Error:', err);
                alert('Erro ao buscar o CEP. Tente novamente mais tarde.');
            } finally {
                if (btnBuscarCep) btnBuscarCep.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>';
            }
        } else if (cep.length > 0 && cep.length < 8) {
            alert('CEP incompleto. Digite os 8 dígitos.');
        }
    };

    if (cepEl) {
        cepEl.addEventListener('blur', function() {
            if (this.value.replace(/\D/g, '').length === 8) {
                buscarCepHelper();
            }
        });

        const btnBuscarCep = document.getElementById('btn_buscar_cep');
        if (btnBuscarCep) {
            btnBuscarCep.addEventListener('click', buscarCepHelper);
        }
    }

    // 4. Calcular Idade
    const dataNascimentoInput = document.getElementById('data_nascimento');
    const idadeInput = document.getElementById('idade');

    if (dataNascimentoInput && idadeInput) {
        const calcularIdade = () => {
            if (dataNascimentoInput.value) {
                const [year, month, day] = dataNascimentoInput.value.split('-');
                if (year && month && day) {
                    const birth = new Date(year, month - 1, day);
                    const today = new Date();
                    let age = today.getFullYear() - birth.getFullYear();
                    const m_diff = today.getMonth() - birth.getMonth();
                    if (m_diff < 0 || (m_diff === 0 && today.getDate() < birth.getDate())) {
                        age--;
                    }
                    idadeInput.value = age >= 0 ? age : 0;
                }
            } else {
                idadeInput.value = '';
            }
        };

        dataNascimentoInput.addEventListener('change', calcularIdade);
        dataNascimentoInput.addEventListener('input', calcularIdade);
    }

    // 5. File Previews
    const fileInputs = [
        'doc_identidade', 'doc_quitacao_eleitoral', 'doc_fiscal_federal', 
        'doc_fiscal_estadual', 'doc_fiscal_municipal', 'doc_situacao_cpf',
        'doc_conjuge_identidade', 'doc_conjuge_quitacao_eleitoral', 'doc_conjuge_fiscal_federal', 
        'doc_conjuge_fiscal_estadual', 'doc_conjuge_fiscal_municipal', 'doc_conjuge_situacao_cpf'
    ];

    fileInputs.forEach(id => {
        const el = document.getElementById(id);
        const preview = document.getElementById('preview-' + id);
        
        if (el && preview) {
            el.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    preview.classList.remove('hidden');
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            preview.innerHTML = `<img src="${e.target.result}" class="h-24 w-auto rounded border border-gray-200 shadow-sm mt-3">`;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        preview.innerHTML = `<div class="flex items-center space-x-2 text-indigo-600 font-semibold text-xs bg-indigo-50 p-2 rounded border border-indigo-100 mt-3">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path></svg>
                            <span>${file.name} (PDF/Doc)</span>
                        </div>`;
                    }
                }
            });
        }
    });
});
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>
