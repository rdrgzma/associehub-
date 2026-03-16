<?php
/**
 * Script de Emergência para Criação de Super Admin
 * Uso: php maintenance/create_admin.php "Nome" "email@exemplo.com" "senha123"
 */

require_once __DIR__ . '/../config/database.php';

if ($argc < 4) {
    echo "\n\033[1;33mUso correto:\033[0m\n";
    echo "php maintenance/create_admin.php \"Nome do Admin\" \"email@exemplo.com\" \"senha_desejada\"\n\n";
    exit(1);
}

$nome = $argv[1];
$email = $argv[2];
$senha = $argv[3];

try {
    $db = Database::getConnection();

    // Verificar se o e-mail já existe
    $check = $db->prepare("SELECT id FROM admins WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) {
        echo "\n\033[0;31m[ERRO] O e-mail '$email' já está cadastrado como administrador.\033[0m\n\n";
        exit(1);
    }

    $hashedPassword = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "INSERT INTO admins (nome, email, senha) VALUES (:nome, :email, :senha)";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        'nome' => $nome,
        'email' => $email,
        'senha' => $hashedPassword
    ]);

    echo "\n\033[0;32m[SUCESSO] Administrador criado com sucesso!\033[0m\n";
    echo str_repeat("-", 40) . "\n";
    echo "Nome:  $nome\n";
    echo "Email: $email\n";
    echo str_repeat("-", 40) . "\n\n";

} catch (Exception $e) {
    echo "\n\033[0;31m[ERRO] Falha ao criar administrador: " . $e->getMessage() . "\033[0m\n\n";
}
