<?php require_once '../app/views/layouts/header.php'; ?>

<div x-data="{ showDeleteModal: false }">

<div class="mb-8">
    <div class="flex items-center space-x-2 text-sm text-gray-500 mb-4">
        <a href="/admin/dashboard" class="hover:text-indigo-600 transition">Dashboard</a>
        <span>/</span>
        <a href="/admin/associacoes" class="hover:text-indigo-600 transition">Associações</a>
        <span>/</span>
        <span class="text-gray-900 font-medium">Editar Cadastro</span>
    </div>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Editar Parceria: <?= htmlspecialchars($associacao['nome']) ?>
            </h1>
            <p class="text-sm text-gray-500 mt-1">Modifique as credenciais cadastrais e detalhes de contabilidade da associação.</p>
        </div>
        <a href="/admin/associacoes" class="inline-flex justify-center items-center bg-white border border-gray-300 text-gray-700 font-medium py-2 px-4 rounded-lg text-sm hover:bg-gray-50 transition shadow-sm w-full sm:w-auto">
            Cancelar e Voltar
        </a>
    </div>
</div>

<?php if (isset($_SESSION['error_msg'])): ?>
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex items-center shadow-sm">
        <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div class="flex-grow text-sm font-medium"><?= htmlspecialchars($_SESSION['error_msg']) ?></div>
    </div>
    <?php unset($_SESSION['error_msg']); ?>
<?php endif; ?>

<div class="max-w-4xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 flex items-center">
                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Ficha de Cadastro Central
            </h3>
            <span class="text-xs font-mono text-gray-400">ID: #<?= str_pad($associacao['id'], 3, '0', STR_PAD_LEFT) ?></span>
        </div>
        
        <form action="/admin/associacoes/<?= $associacao['id'] ?>/atualizar" method="POST" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Nome da Associação -->
                <div class="md:col-span-2">
                    <label for="nome" class="block text-sm font-bold text-gray-700 mb-2">Razão Social / Nome da Associação *</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($associacao['nome'] ?? '') ?>" required
                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
                        placeholder="Nome Empresarial Completo">
                </div>

                <!-- CNPJ -->
                <div>
                    <label for="cnpj" class="block text-sm font-bold text-gray-700 mb-2">CNPJ *</label>
                    <input type="text" id="cnpj" name="cnpj" value="<?= htmlspecialchars($associacao['cnpj'] ?? '') ?>" required
                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
                        placeholder="00.000.000/0000-00"
                        x-data
                        x-mask="99.999.999/9999-99">
                </div>

                <!-- Responsável -->
                <div>
                    <label for="responsavel" class="block text-sm font-bold text-gray-700 mb-2">Nome do Responsável Legal *</label>
                    <input type="text" id="responsavel" name="responsavel" value="<?= htmlspecialchars($associacao['responsavel'] ?? '') ?>" required
                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
                        placeholder="Nome do Diretor/Presidente">
                </div>

                <!-- E-mail -->
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-2">E-mail Institucional *</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($associacao['email'] ?? '') ?>" required
                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
                        placeholder="contato@empresa.com.br">
                </div>

                <!-- Telefone -->
                <div>
                    <label for="telefone" class="block text-sm font-bold text-gray-700 mb-2">Telefone Padrão *</label>
                    <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($associacao['telefone'] ?? '') ?>" required
                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
                        placeholder="(00) 00000-0000"
                        x-data
                        x-mask="(99) 99999-9999">
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-bold text-gray-700 mb-2">Status da Associação *</label>
                    <select id="status" name="status" required
                        class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm">
                        <option value="approved" <?= $associacao['status'] === 'approved' ? 'selected' : '' ?>>Aprovada</option>
                        <option value="pending" <?= $associacao['status'] === 'pending' ? 'selected' : '' ?>>Pendente</option>
                        <option value="rejected" <?= $associacao['status'] === 'rejected' ? 'selected' : '' ?>>Rejeitada</option>
                    </select>
                </div>

            </div>

            <!-- Formulário Submission -->
            <div class="mt-8 flex flex-col-reverse sm:flex-row justify-between items-center bg-gray-50 -my-6 -mx-6 px-6 py-4 border-t border-gray-100">
                <button type="button" @click.prevent="showDeleteModal = true" class="w-full sm:w-auto mt-4 sm:mt-0 bg-white border border-red-200 hover:bg-red-50 text-red-600 font-bold py-3 px-6 rounded-lg text-sm transition shadow-sm flex justify-center items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Excluir Associação
                </button>
                <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg text-sm transition shadow-sm flex justify-center items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Salvar Mudanças Exclusivas
                </button>
            </div>
        </form>
    </div>

    <!-- Alpine.js Delete Confirmation Modal -->
    <div x-show="showDeleteModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;"
         x-cloak>
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" 
                 @click="showDeleteModal = false"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200"
                 @click.away="showDeleteModal = false">
                
                <div class="bg-white px-6 pt-6 pb-4 sm:pb-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl font-bold text-gray-900 leading-tight">
                                Excluir Associação Definitivamente
                            </h3>
                            <div class="mt-3">
                                <p class="text-gray-600">
                                    Tem certeza de que deseja excluir a associação <span class="font-bold text-gray-900"><?= htmlspecialchars($associacao['nome']) ?></span>?
                                </p>
                                <div class="mt-4 bg-red-50 p-4 rounded-xl border border-red-100 border-dashed">
                                    <p class="text-xs text-red-800 font-bold uppercase tracking-wider mb-2">Atenção: Ação Irreversível</p>
                                    <ul class="text-sm text-red-700 space-y-2">
                                        <li class="flex items-start">
                                            <svg class="w-4 h-4 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                            Todos os Membros serão apagados.
                                        </li>
                                        <li class="flex items-start">
                                            <svg class="w-4 h-4 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                            Documentos (PDF/JPG) serão deletados do servidor.
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse gap-3 border-t border-gray-100">
                    <form action="/admin/associacoes/<?= $associacao['id'] ?>/deletar" method="POST">
                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-6 py-2.5 bg-red-600 text-base font-bold text-white hover:bg-red-700 focus:outline-none sm:text-sm transition-all active:scale-95">
                            Sim, Excluir Tudo permanentemente
                        </button>
                    </form>
                    <button type="button" @click="showDeleteModal = false" class="mt-3 sm:mt-0 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-6 py-2.5 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:text-sm transition-all">
                        Não, Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Alpine JS Mask for auto-formatting the CNPJ and Telephone numbers inside the inputs natively in DOM -->
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
