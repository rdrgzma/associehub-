<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login da Associação</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md border border-gray-100">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Portal da Associação</h1>
            <p class="text-sm text-gray-500 mt-2">Acesse para gerenciar seus associados</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-6 border border-red-100 text-center">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="/manager/login" method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">CNPJ ou E-mail da Associação</label>
                <input type="text" name="identificador" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" placeholder="00.000.000/0000-00 ou email@exemplo.com">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Senha de Acesso</label>
                <input type="password" name="senha" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition" placeholder="••••••••">
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-4 rounded-lg transition shadow-md mt-4">
                Entrar
            </button>
        </form>
        
        <div class="mt-6 text-center text-sm text-gray-500">
            <a href="/" class="hover:text-indigo-600 hover:underline">Página Inicial</a> | <a href="/admin/login" class="hover:text-indigo-600 hover:underline">Admin Login</a>
        </div>
    </div>
</body>
</html>
