<?php

class AssociacaoController extends Controller {
    public function cadastroForm() {
        $this->view('associacao/cadastro');
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nome' => $_POST['nome'] ?? '',
                'cnpj' => $_POST['cnpj'] ?? '',
                'responsavel' => $_POST['responsavel'] ?? '',
                'email' => $_POST['email'] ?? '',
                'telefone' => $_POST['telefone'] ?? ''
            ];

            $model = new Associacao();
            try {
                if ($model->create($data)) {
                    $this->redirect('/sucesso');
                } else {
                    $this->view('associacao/cadastro', ['error' => 'Erro ao registrar.', 'data' => $data]);
                }
            } catch (PDOException $e) {
                // simple check for duplicate unique fields (like email or cnpj)
                $errorMsg = 'Erro no banco de dados. Talvez o CNPJ já esteja cadastrado.';
                $this->view('associacao/cadastro', ['error' => $errorMsg, 'data' => $data]);
            }
        }
    }

    public function sucesso() {
        $this->view('associacao/sucesso');
    }
}
