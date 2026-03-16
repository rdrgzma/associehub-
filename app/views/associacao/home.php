<?php require_once '../app/views/layouts/header_form.php'; ?>

<div class="min-h-[70vh] flex flex-col items-center justify-center px-4 py-12">

    <!-- Hero -->
    <div class="text-center max-w-xl mx-auto mb-10">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-100 text-indigo-600 mb-4 shadow-sm">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">AssocieHub</h1>
        <p class="text-gray-500 text-base leading-relaxed">Plataforma de gestão para associações. Organize membros, cobranças e documentos em um só lugar.</p>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-2xl">

        <!-- Nova Associação -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col items-start hover:shadow-md transition">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <h2 class="text-lg font-bold text-gray-900 mb-1">Cadastrar Nova Associação</h2>
            <p class="text-sm text-gray-500 mb-4">Registre sua associação na plataforma. Após aprovação, você receberá acesso ao painel de gestão.</p>
            <a href="/nova-associacao" class="mt-auto w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-xl text-sm text-center transition shadow-sm">
                Solicitar Cadastro
            </a>
        </div>

        <!-- Sou Associado -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col items-start hover:shadow-md transition">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h2 class="text-lg font-bold text-gray-900 mb-1">Sou Membro Associado</h2>
            <p class="text-sm text-gray-500 mb-4">Para se cadastrar como membro, utilize o link exclusivo fornecido pelo responsável da sua associação.</p>
            <div class="mt-auto w-full bg-gray-50 border border-gray-200 text-gray-600 text-sm rounded-xl px-4 py-3 text-center leading-relaxed">
                Solicite o link de cadastro ao <strong>responsável pela associação</strong> à qual deseja se filiar.
            </div>
        </div>

    </div>

    <!-- Admin link -->
    <p class="mt-10 text-xs text-gray-400">
        É administrador? <a href="/admin/login" class="text-indigo-500 hover:underline">Acesse o painel</a>.
    </p>

</div>

<?php require_once '../app/views/layouts/footer_form.php'; ?>
