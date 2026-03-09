<?php

class AssociadoController extends Controller {
    public function formulario($token) {
        $associacaoModel = new Associacao();
        $associacao = $associacaoModel->findByToken($token);

        if (!$associacao) {
            die("Link de cadastro inválido ou associação não aprovada.");
        }

        $this->view('associado/formulario', ['associacao' => $associacao, 'token' => $token]);
    }

    public function registrar($token) {
        $associacaoModel = new Associacao();
        $associacao = $associacaoModel->findByToken($token);

        if (!$associacao) {
            die("Link de cadastro inválido.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'associacao_id' => $associacao['id'],
                'nome' => $_POST['nome'] ?? '',
                'cpf' => $_POST['cpf'] ?? '',
                'email' => $_POST['email'] ?? '',
                'telefone' => $_POST['telefone'] ?? '',
                'endereco' => $_POST['endereco'] ?? '',
                'cidade' => $_POST['cidade'] ?? '',
                'estado' => $_POST['estado'] ?? ''
            ];

            // Handle file uploads
            $uploadPath = __DIR__ . '/../../public/uploads/docs/';
            $documents = [
                'doc_identidade', 'doc_quitacao_eleitoral', 'doc_fiscal_federal',
                'doc_fiscal_estadual', 'doc_fiscal_municipal', 'doc_situacao_cpf'
            ];

            foreach ($documents as $doc) {
                $data[$doc] = null; // Default to null if no file
                if (isset($_FILES[$doc]) && $_FILES[$doc]['error'] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($_FILES[$doc]['name'], PATHINFO_EXTENSION);
                    $filename = uniqid($doc . '_') . '.' . $ext;
                    if (move_uploaded_file($_FILES[$doc]['tmp_name'], $uploadPath . $filename)) {
                        $data[$doc] = '/uploads/docs/' . $filename;
                    }
                }
            }

            $associadoModel = new Associado();
            try {
                if ($associadoModel->create($data)) {
                    $this->redirect('/associado/sucesso');
                } else {
                    $this->view('associado/formulario', [
                        'error' => 'Erro ao registrar associado.', 
                        'associacao' => $associacao,
                        'token' => $token,
                        'data' => $data
                    ]);
                }
            } catch (PDOException $e) {
                $errorMsg = 'Erro no banco de dados. O CPF pode já estar em uso.';
                $this->view('associado/formulario', [
                    'error' => $errorMsg, 
                    'associacao' => $associacao,
                    'token' => $token,
                    'data' => $data
                ]);
            }
        }
    }

    public function sucesso() {
        $this->view('associado/sucesso');
    }
}
