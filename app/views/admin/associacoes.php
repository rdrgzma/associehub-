<?php require_once '../app/views/layouts/header.php'; ?>

<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Todas as Associações</h1>
        <p class="text-sm text-gray-500 mt-1">Gerenciamento completo das associações da plataforma</p>
    </div>
    <a href="/admin/dashboard" class="text-gray-600 hover:text-gray-900 font-medium text-sm border border-gray-300 px-3 py-1.5 rounded-lg">&larr; Voltar ao Dashboard</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <?php if(empty($associacoes)): ?>
        <div class="p-6 text-center text-gray-500">Nenhuma associação cadastrada no momento.</div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                        <th class="py-3 px-6 font-medium">ID</th>
                        <th class="py-3 px-6 font-medium">Associação</th>
                        <th class="py-3 px-6 font-medium">Responsável</th>
                        <th class="py-3 px-6 font-medium">Status</th>
                        <th class="py-3 px-6 font-medium">Link de Cadastro & Senha</th>
                        <th class="py-3 px-6 font-medium text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-100">
                    <?php foreach($associacoes as $assoc): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-6 text-gray-500">#<?= $assoc['id'] ?></td>
                        <td class="py-3 px-6 text-gray-900 font-medium">
                            <?= htmlspecialchars($assoc['nome']) ?>
                            <div class="text-xs text-gray-500 font-normal">CNPJ: <?= htmlspecialchars($assoc['cnpj']) ?></div>
                        </td>
                        <td class="py-3 px-6 text-gray-600">
                            <?= htmlspecialchars($assoc['responsavel']) ?>
                            <div class="text-xs text-gray-500">Tel: <?= htmlspecialchars($assoc['telefone']) ?></div>
                            <div class="text-xs text-gray-500">Email: <?= htmlspecialchars($assoc['email']) ?></div>
                        </td>
                        <td class="py-3 px-6">
                            <?php if($assoc['status'] == 'approved'): ?>
                                <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded-full text-xs font-medium border border-green-200">Aprovada</span>
                            <?php elseif($assoc['status'] == 'rejected'): ?>
                                <span class="bg-red-100 text-red-800 px-2 py-0.5 rounded-full text-xs font-medium border border-red-200">Rejeitada</span>
                            <?php else: ?>
                                <span class="bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full text-xs font-medium border border-amber-200">Pendente</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-6">
                            <?php if($assoc['status'] == 'approved' && $assoc['token']): ?>
                                <?php 
                                    $link = "http://" . $_SERVER['HTTP_HOST'] . "/cadastro/" . $assoc['token']; 
                                ?>
                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <input type="text" readonly value="<?= $link ?>" class="text-xs bg-gray-50 border border-gray-200 rounded px-2 py-1 w-48 text-gray-500 outline-none">
                                        <button type="button" onclick="navigator.clipboard.writeText('<?= $link ?>'); alert('Link copiado!')" class="text-indigo-600 hover:text-indigo-800 text-xs font-semibold" title="Copiar Link">
                                            Copiar Link
                                        </button>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs text-gray-600 font-medium whitespace-nowrap">Senha Manager:</span>
                                        <div class="relative flex items-center">
                                            <input type="password" readonly value="********" id="pwd-<?= $assoc['id'] ?>" class="text-xs bg-gray-50 border border-gray-200 rounded px-2 py-1 w-24 text-gray-800 font-mono outline-none tracking-widest cursor-not-allowed">
                                            <button type="button" onclick="openRevealModal(<?= $assoc['id'] ?>)" class="ml-2 text-gray-400 hover:text-indigo-600 transition" title="Revelar Senha" id="btn-reveal-<?= $assoc['id'] ?>">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <span class="text-gray-400 text-xs italic">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-6 text-right">
                            <?php if($assoc['status'] == 'approved'): ?>
                                <div class="flex flex-col items-end space-y-2">
                                    <div class="flex items-center space-x-3 mb-1">
                                        <a href="/admin/associacoes/<?= $assoc['id'] ?>/editar" class="text-blue-600 hover:text-blue-800 font-medium text-xs flex items-center bg-blue-50 px-2 py-1 rounded transition">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Editar Dados
                                        </a>
                                        <a href="/admin/associacoes/<?= $assoc['id'] ?>/membros" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">Ver Membros</a>
                                    </div>
                                    <form action="/admin/associacoes/<?= $assoc['id'] ?>/nova-senha" method="POST" class="inline-block mt-1">
                                        <button type="submit" class="text-amber-600 hover:text-amber-800 font-medium text-xs whitespace-nowrap" onclick="return confirm('Tem certeza que deseja gerar uma nova senha para o manager desta associação? A senha anterior será perdida.')">Gerar Nova Senha</button>
                                    </form>
                                </div>
                            <?php elseif($assoc['status'] == 'pending'): ?>
                                <div class="flex flex-col items-end space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <a href="/admin/associacoes/<?= $assoc['id'] ?>/aprovar" class="text-green-600 hover:text-green-800 font-medium text-xs bg-green-50 px-2 py-1 rounded transition">Aprovar</a>
                                        <form action="/admin/associacoes/<?= $assoc['id'] ?>/rejeitar" method="POST" class="inline-block">
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-xs bg-red-50 px-2 py-1 rounded transition" onclick="return confirm('Tem certeza?')">Rejeitar</button>
                                        </form>
                                    </div>
                                    <a href="/admin/associacoes/<?= $assoc['id'] ?>/editar" class="text-blue-600 hover:text-blue-800 font-medium text-xs flex items-center bg-blue-50 px-2 py-1 rounded transition w-fit">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Editar Dados
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="flex flex-col items-end space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-gray-400 text-sm">Rejeitada</span>
                                        <a href="/admin/associacoes/<?= $assoc['id'] ?>/editar" class="text-blue-600 hover:text-blue-800 font-medium text-xs flex items-center bg-blue-50 px-2 py-1 rounded transition">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Editar Dados
                                        </a>
                                    </div>
                                    <form action="/admin/associacoes/<?= $assoc['id'] ?>/deletar" method="POST" class="inline-block">
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-xs flex items-center bg-red-50 px-2 py-1 rounded transition whitespace-nowrap" onclick="return confirm('ALERTA DE SEGURANÇA:\n\nTem certeza que deseja excluir DE VEZ esta associação rejeitada e todos os seus vínculos?\n\nEsta ação é 100% irreversível e não poderá ser desfeita.')">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Excluir Definitivamente
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Reveal Password Modal -->
<div id="revealModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeRevealModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Revelar Senha
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 mb-4">
                                Por segurança, informe sua <strong class="text-gray-700">senha de Administrador</strong> para revelar esta credencial.
                            </p>
                            <input type="hidden" id="revealTargetId" value="">
                            
                            <div id="revealErrorBox" class="hidden mb-3 text-xs bg-red-50 text-red-600 border border-red-100 p-2 rounded">
                                <!-- Error messages appear here -->
                            </div>
                            
                            <div class="mb-2">
                                <input type="password" id="adminPasswordInput" placeholder="Senha do Admin Mestre" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse relative">
                <!-- Loader Overlay -->
                <div id="revealLoader" class="absolute inset-0 bg-gray-50 bg-opacity-70 hidden flex items-center justify-center">
                    <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                <button type="button" onclick="submitReveal()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition">
                    Verificar
                </button>
                <button type="button" onclick="closeRevealModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('revealModal');
    const targetIdInput = document.getElementById('revealTargetId');
    const adminPasswordInput = document.getElementById('adminPasswordInput');
    const errorBox = document.getElementById('revealErrorBox');
    const loader = document.getElementById('revealLoader');

    function openRevealModal(associacaoId) {
        // Find if it's already revealed
        const pwdInput = document.getElementById('pwd-' + associacaoId);
        if (pwdInput.type === 'text' && pwdInput.value !== '********') {
            // Already revealed, simply re-hide it
            pwdInput.type = 'password';
            pwdInput.value = '********';
            pwdInput.classList.add('tracking-widest');
            pwdInput.classList.remove('font-bold');
            return;
        }

        // Needs revealing
        targetIdInput.value = associacaoId;
        adminPasswordInput.value = '';
        errorBox.classList.add('hidden');
        errorBox.innerText = '';
        modal.classList.remove('hidden');
        setTimeout(() => adminPasswordInput.focus(), 100);
    }

    function closeRevealModal() {
        modal.classList.add('hidden');
        targetIdInput.value = '';
        adminPasswordInput.value = '';
    }

    async function submitReveal() {
        const associacaoId = targetIdInput.value;
        const password = adminPasswordInput.value;

        if (!password) {
            showError("A senha não pode estar vazia.");
            return;
        }

        errorBox.classList.add('hidden');
        loader.classList.remove('hidden');

        try {
            const response = await fetch('/admin/associacoes/revelar-senha', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    associacao_id: associacaoId,
                    admin_senha: password
                })
            });

            const data = await response.json();

            if (data.success) {
                // Success: update the input
                const pwdInput = document.getElementById('pwd-' + associacaoId);
                // Reveal the password
                pwdInput.type = 'text';
                pwdInput.value = data.password;
                pwdInput.classList.remove('tracking-widest');
                pwdInput.classList.add('font-bold');
                
                closeRevealModal();
            } else {
                showError(data.message || "Erro desconhecido. Tente novamente.");
            }
        } catch (error) {
            console.error("Fetch falhou:", error);
            showError("Erro na conexão com o servidor.");
        } finally {
            loader.classList.add('hidden');
        }
    }

    function showError(message) {
        errorBox.innerText = message;
        errorBox.classList.remove('hidden');
    }

    // Allow Enter key to submit
    adminPasswordInput.addEventListener("keyup", function(event) {
        if (event.key === "Enter") {
            submitReveal();
        }
    });
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>
