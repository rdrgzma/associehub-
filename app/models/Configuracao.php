<?php

class Configuracao extends Model
{
    public function get($chave, $default = null)
    {
        $stmt = $this->db->prepare("SELECT valor FROM configuracoes WHERE chave = :chave");
        $stmt->execute(['chave' => $chave]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? $result['valor'] : $default;
    }

    public function set($chave, $valor)
    {
        // Use SQLite UPSERT behavior
        $sql = "INSERT INTO configuracoes (chave, valor) VALUES (:chave, :valor) 
                ON CONFLICT(chave) DO UPDATE SET valor = excluded.valor";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['chave' => $chave, 'valor' => $valor]);
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT chave, valor FROM configuracoes");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $config = [];
        foreach ($results as $row) {
            $config[$row['chave']] = $row['valor'];
        }
        return $config;
    }
}
