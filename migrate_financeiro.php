<?php
require_once __DIR__ . '/config/Database.php';

try {
    $db = Database::getConnection();
    
    // Associacoes
    $db->exec("ALTER TABLE associacoes ADD COLUMN pagamento_comprovante TEXT");
    $db->exec("ALTER TABLE associacoes ADD COLUMN pagamento_valor DECIMAL(10,2)");
    $db->exec("ALTER TABLE associacoes ADD COLUMN pagamento_data DATETIME");
    
    // Associados
    $db->exec("ALTER TABLE associados ADD COLUMN pagamento_comprovante TEXT");
    $db->exec("ALTER TABLE associados ADD COLUMN pagamento_valor DECIMAL(10,2)");
    $db->exec("ALTER TABLE associados ADD COLUMN pagamento_data DATETIME");
    
    echo "Migration completed successfully!\n";
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
