<?php
require_once __DIR__ . '/config/database.php';
$db = Database::getConnection();
$db->exec("INSERT INTO admins (nome, email, senha) VALUES ('Admin', 'admin@admin.com', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')");
$db->exec("INSERT INTO associacoes (nome, cnpj, responsavel, email, telefone, status, token, acesso_senha) VALUES ('Assoc Test', '11.111.111/1111-11', 'Resp', 'test@test.com', '123', 'approved', 'tokentest123', 'senha123')");
$db->exec("INSERT INTO associados (associacao_id, nome, cpf, email, telefone, endereco, cidade, estado) VALUES (1, 'Membro Teste Manager', '111.111.111-11', 'm@m.com', '123', 'Rua 1', 'City', 'SP')");
echo "Database seeded successfully.\n";
