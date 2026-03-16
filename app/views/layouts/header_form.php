<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if(isset($associacao['nome'])): ?>
        <title>Cadastro — <?= htmlspecialchars($associacao['nome']) ?></title>
    <?php else: ?>
        <title>Cadastro — AssocieHub</title>
    <?php endif; ?>
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
    <!-- Minimal public header — no navigation -->
    <header class="bg-indigo-600 shadow-md">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-center">
            <?php if(isset($associacao['nome'])): ?>
                <span class="text-white text-lg font-bold tracking-tight"><?= htmlspecialchars($associacao['nome']) ?></span>
            <?php else: ?>
                <span class="text-white text-lg font-bold tracking-tight">AssocieHub</span>
            <?php endif; ?>
        </div>
    </header>
    <main class="flex-grow flex flex-col py-8 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto w-full">
