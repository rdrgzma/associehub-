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
                'numero' => $_POST['numero'] ?? '',
                'complemento' => $_POST['complemento'] ?? '',
                'bairro' => $_POST['bairro'] ?? '',
                'cep' => $_POST['cep'] ?? '',
                'cidade' => $_POST['cidade'] ?? '',
                'estado' => $_POST['estado'] ?? '',
                'nacionalidade' => $_POST['nacionalidade'] ?? '',
                'naturalidade' => $_POST['naturalidade'] ?? '',
                'data_nascimento' => $_POST['data_nascimento'] ?? '',
                'idade' => $_POST['idade'] ?? '',
                'rg' => $_POST['rg'] ?? '',
                'rg_orgao_emissor' => $_POST['rg_orgao_emissor'] ?? '',
                'filiacao_1_nome' => $_POST['filiacao_1_nome'] ?? '',
                'filiacao_1_cpf' => $_POST['filiacao_1_cpf'] ?? '',
                'filiacao_2_nome' => $_POST['filiacao_2_nome'] ?? '',
                'filiacao_2_cpf' => $_POST['filiacao_2_cpf'] ?? '',
                'estado_civil' => $_POST['estado_civil'] ?? '',
                'forma_comunhao' => $_POST['forma_comunhao'] ?? '',
                'conjuge_nome' => $_POST['conjuge_nome'] ?? '',
                'conjuge_cpf' => $_POST['conjuge_cpf'] ?? '',
                'profissao_1' => $_POST['profissao_1'] ?? '',
                'profissao_1_registro' => $_POST['profissao_1_registro'] ?? '',
                'profissao_1_orgao' => $_POST['profissao_1_orgao'] ?? '',
                'profissao_2' => $_POST['profissao_2'] ?? '',
                'profissao_2_registro' => $_POST['profissao_2_registro'] ?? '',
                'profissao_2_orgao' => $_POST['profissao_2_orgao'] ?? ''
            ];

            // Handle file uploads
            $uploadPath = __DIR__ . '/../../public/uploads/docs/';
            $documents = [
                'doc_identidade', 'doc_quitacao_eleitoral', 'doc_fiscal_federal',
                'doc_fiscal_estadual', 'doc_fiscal_municipal', 'doc_situacao_cpf'
            ];
            
            $spouseDocuments = [
                'doc_conjuge_identidade', 'doc_conjuge_quitacao_eleitoral', 'doc_conjuge_fiscal_federal',
                'doc_conjuge_fiscal_estadual', 'doc_conjuge_fiscal_municipal', 'doc_conjuge_situacao_cpf'
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

            // Handle spouse documents conditionally
            $isSingleStatus = in_array($data['estado_civil'], ['Solteiro(a)', 'Viúvo(a)', 'Divorciado(a)']);
            foreach ($spouseDocuments as $sDoc) {
                $data[$sDoc] = null; 
                if (!$isSingleStatus && isset($_FILES[$sDoc]) && $_FILES[$sDoc]['error'] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($_FILES[$sDoc]['name'], PATHINFO_EXTENSION);
                    $filename = uniqid($sDoc . '_') . '.' . $ext;
                    if (move_uploaded_file($_FILES[$sDoc]['tmp_name'], $uploadPath . $filename)) {
                        $data[$sDoc] = '/uploads/docs/' . $filename;
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
