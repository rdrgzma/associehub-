<?php

class AssociacaoController extends Controller {
    public function home() {
        $this->view('associacao/home');
    }

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
                'telefone' => $_POST['telefone'] ?? '',
                'pagamento_valor' => !empty($_POST['pagamento_valor']) ? str_replace(',', '.', $_POST['pagamento_valor']) : 0,
                'pagamento_data' => date('Y-m-d H:i:s'),
                'recorrencia' => $_POST['recorrencia'] ?? 'uma_vez'
            ];

            // Handle payment proof upload
            if (isset($_FILES['pagamento_comprovante']) && $_FILES['pagamento_comprovante']['error'] === UPLOAD_ERR_OK) {
                $uploadPath = __DIR__ . '/../../public/uploads/financeiro/';
                if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);
                
                $ext = pathinfo($_FILES['pagamento_comprovante']['name'], PATHINFO_EXTENSION);
                $filename = 'assoc_pay_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES['pagamento_comprovante']['tmp_name'], $uploadPath . $filename)) {
                    $data['pagamento_comprovante'] = '/uploads/financeiro/' . $filename;
                }
            }

            $model = new Associacao();
            try {
                $db = Database::getConnection();
                $db->beginTransaction();

                if ($model->create($data)) {
                    $assocId = $db->lastInsertId();
                    
                    // Create payment record
                    $finModel = new Financeiro();
                    $finModel->createPayment([
                        'tipo' => 'associacao',
                        'ref_id' => $assocId,
                        'valor' => $data['pagamento_valor'],
                        'status' => !empty($data['pagamento_comprovante']) ? 'pago' : 'pendente',
                        'recorrencia' => $data['recorrencia'],
                        'comprovante' => $data['pagamento_comprovante'] ?? null,
                        'data_pagamento' => !empty($data['pagamento_comprovante']) ? $data['pagamento_data'] : null
                    ]);

                    $db->commit();
                    $this->redirect('/sucesso');
                } else {
                    $db->rollBack();
                    $this->view('associacao/cadastro', ['error' => 'Erro ao registrar.', 'data' => $data]);
                }
            } catch (PDOException $e) {
                if (isset($db)) $db->rollBack();
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
