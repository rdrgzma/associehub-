<?php
$dbPath = __DIR__ . '/database/database.sqlite';

try {
    $pdo = new PDO("sqlite:" . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conectando ao banco de dados SQLite em: $dbPath\n";

    // Create the generic settings table
    $sql = "CREATE TABLE IF NOT EXISTS configuracoes (
        chave TEXT PRIMARY KEY,
        valor TEXT
    )";
    $pdo->exec($sql);
    echo "Tabela 'configuracoes' criada ou já existente.\n";

    // Seed empty default keys if they don't exist
    $keys = ['pix_chave' => '', 'pix_valor_cadastro' => '', 'pix_instrucoes' => ''];
    $stmt = $pdo->prepare("INSERT OR IGNORE INTO configuracoes (chave, valor) VALUES (:chave, :valor)");

    foreach ($keys as $k => $v) {
        $stmt->execute(['chave' => $k, 'valor' => $v]);
    }
    
    echo "Chaves globais de Pagamento injetadas.\n";

} catch (PDOException $e) {
    echo "Erro na migração: " . $e->getMessage() . "\n";
}
