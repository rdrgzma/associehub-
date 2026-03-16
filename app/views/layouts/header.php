<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AssocieHub - Gestão de Associações</title>
    <!-- Tailwind CSS v4 CDN -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --font-inter: "Inter", sans-serif;
        }
        body { font-family: var(--font-inter); }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col antialiased">
    <header class="bg-indigo-600 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center">
                <?php if(isset($token) && isset($associacao['nome'])): ?>
                    <span class="text-white text-xl font-bold tracking-tight"><?= htmlspecialchars($associacao['nome']) ?></span>
                <?php else: ?>
                    <a href="/" class="text-white text-xl font-bold tracking-tight">AssocieHub</a>
                <?php endif; ?>
            </div>
            <nav class="flex items-center space-x-2">
                <?php if(!isset($token)): ?>
                    <a href="/" class="text-indigo-100 hover:text-white px-3 py-2 rounded-md font-medium">Home</a>
                <?php endif; ?>

                <?php if(isset($_SESSION['admin_id'])): ?>
                    <a href="/admin/dashboard" class="text-indigo-100 hover:text-white px-3 py-2 rounded-md font-medium border border-indigo-400 bg-indigo-500 text-sm">Painel Admin</a>
                    <a href="/admin/associacoes" class="text-indigo-100 hover:text-white px-3 py-2 rounded-md font-medium text-sm">Associações</a>
                    <a href="/admin/financeiro" class="text-indigo-100 hover:text-white px-3 py-2 rounded-md font-medium text-sm">Financeiro</a>
                    <a href="/admin/logout" class="text-indigo-100 hover:text-white px-3 py-2 rounded-md font-medium text-sm">Sair</a>
                <?php endif; ?>

                <?php if(isset($_SESSION['manager_id'])): ?>
                    <a href="/manager/dashboard" class="text-indigo-100 hover:text-white px-3 py-2 rounded-md font-medium border border-indigo-400 bg-indigo-500 text-sm">Painel da Associação</a>
                    <a href="/manager/financeiro" class="text-indigo-100 hover:text-white px-3 py-2 rounded-md font-medium text-sm">Financeiro</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <main class="flex-grow flex flex-col py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
