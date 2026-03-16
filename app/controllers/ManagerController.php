<?php

class ManagerController extends Controller {
    public function __construct() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function loginForm() {
        if (isset($_SESSION['manager_id'])) {
            $this->redirect('/manager/dashboard');
        }
        $this->view('manager/login');
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identificador = $_POST['identificador'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $associacaoModel = new Associacao();
            $manager = $associacaoModel->login($identificador, $senha);

            if ($manager) {
                $_SESSION['manager_id'] = $manager['id'];
                $_SESSION['manager_nome'] = $manager['nome'];
                $this->redirect('/manager/dashboard');
            } else {
                $this->view('manager/login', ['error' => 'CNPJ, E-mail ou Senha inválidos.']);
            }
        }
    }

    public function logout() {
        unset($_SESSION['manager_id']);
        unset($_SESSION['manager_nome']);
        $this->redirect('/manager/login');
    }

    public function alterarSenha() {
        if (!isset($_SESSION['manager_id'])) {
            $this->redirect('/manager/login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $novaSenha = $_POST['nova_senha'] ?? '';
            $confirmaSenha = $_POST['confirma_senha'] ?? '';

            if (empty($novaSenha) || empty($confirmaSenha)) {
                $_SESSION['error_msg'] = "Preencha ambas as senhas.";
                $this->redirect('/manager/dashboard');
                return;
            }

            if ($novaSenha !== $confirmaSenha) {
                $_SESSION['error_msg'] = "Puxa! As senhas não conferem. Tente novamente.";
                $this->redirect('/manager/dashboard');
                return;
            }

            $associacaoModel = new Associacao();
            $success = $associacaoModel->alterarSenha($_SESSION['manager_id'], $novaSenha);

            if ($success) {
                $_SESSION['success_msg'] = "Sua senha foi atualizada com sucesso!";
            } else {
                $_SESSION['error_msg'] = "Ocorreu um erro ao atualizar a senha.";
            }
            
            $this->redirect('/manager/dashboard');
        }
    }

    public function dashboard() {
        if (!isset($_SESSION['manager_id'])) {
            $this->redirect('/manager/login');
        }

        $search = $_GET['search'] ?? null;
        $situacao = isset($_GET['situacao']) && $_GET['situacao'] !== '' ? $_GET['situacao'] : null;

        $associacaoId = $_SESSION['manager_id'];
        $associacaoModel = new Associacao();
        $associadoModel = new Associado();
        
        $membros = $associadoModel->search($associacaoId, $search, $situacao);

        // Fetch association specific metrics
        $metrics = [
            'total_membros' => $associadoModel->getTotalByAssociacao($associacaoId),
            'membros_ativos' => $associadoModel->getTotalActiveByAssociacao($associacaoId),
            'membros_inativos' => $associadoModel->getTotalInactiveByAssociacao($associacaoId)
        ];

        $associacao = $associacaoModel->findById($associacaoId);
        
        $configModel = new Configuracao();
        $config = $configModel->getAll();

        $this->view('manager/dashboard', [
            'membros' => $membros,
            'associacao_nome' => $_SESSION['manager_nome'],
            'associacao' => $associacao,
            'metrics' => $metrics,
            'config' => $config,
            'filters' => [
                'search' => $search,
                'situacao' => $situacao
            ]
        ]);
    }

    public function membro($id) {
        $this->checkAccess($id);
        
        $associadoModel = new Associado();
        $membro = $associadoModel->findById($id);

        if (!$membro) {
            $this->redirect('/manager/dashboard');
        }

        $configModel = new Configuracao();
        $config = $configModel->getAll();

        $finModel = new Financeiro();
        $pagamentos = $finModel->getPagamentos($id, 'associado');

        $this->view('manager/membro', [
            'membro' => $membro, 
            'config' => $config,
            'pagamentos' => $pagamentos
        ]);
    }

    public function financeiro() {
        if (!isset($_SESSION['manager_id'])) {
            $this->redirect('/manager/login');
        }

        $associacaoId = $_SESSION['manager_id'];
        $finModel = new Financeiro();

        $filters = [
            'associacao_id' => $associacaoId,
            'data_inicio' => $_GET['data_inicio'] ?? null,
            'data_fim' => $_GET['data_fim'] ?? null,
            'status' => $_GET['status'] ?? null
        ];

        $pagamentos = $finModel->getPagamentos($filters);

        $this->view('manager/financeiro', [
            'pagamentos' => $pagamentos,
            'filters' => $filters,
            'associacao_nome' => $_SESSION['manager_nome']
        ]);
    }

    public function ficha($id) {
        $this->checkAccess($id);

        $associadoModel = new Associado();
        $membro = $associadoModel->findById($id);

        if (!$membro) {
            die("Membro não encontrado");
        }

        $configModel = new Configuracao();
        $config = $configModel->getAll();

        $this->view('manager/ficha', ['membro' => $membro, 'config' => $config]);
    }

    public function atualizarMembro($id) {
        $this->checkAccess($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updates = [];
            $docs = [
                'doc_identidade', 'doc_quitacao_eleitoral', 'doc_fiscal_federal',
                'doc_fiscal_estadual', 'doc_fiscal_municipal', 'doc_situacao_cpf'
            ];
            
            foreach ($docs as $doc) {
                if (isset($_POST[$doc . '_status'])) {
                    $updates[$doc . '_status'] = 1;
                } elseif (isset($_POST["{$doc}_status_hidden"])) {
                    $updates[$doc . '_status'] = 0; // Checkbox unchecked
                }
                
                if (isset($_POST[$doc . '_validade'])) {
                    $updates[$doc . '_validade'] = !empty($_POST[$doc . '_validade']) ? $_POST[$doc . '_validade'] : null;
                }
            }
            $associadoModel = new Associado();
            $associadoModel->updateDocumentStatus($id, $updates);

            $this->redirect('/manager/membros/' . $id);
        }
    }

    public function uploadFicha($id) {
        $this->checkAccess($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_FILES['doc_ficha_assinada']) && $_FILES['doc_ficha_assinada']['error'] === UPLOAD_ERR_OK) {
                $uploadPath = __DIR__ . '/../../public/uploads/docs/';
                if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);
                
                $ext = pathinfo($_FILES['doc_ficha_assinada']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('doc_ficha_assinada_') . '.' . $ext;
                if (move_uploaded_file($_FILES['doc_ficha_assinada']['tmp_name'], $uploadPath . $filename)) {
                    $associadoModel = new Associado();
                    $associadoModel->updateDataAndFiles($id, ['doc_ficha_assinada' => '/uploads/docs/' . $filename]);
                }
            }
            $this->redirect('/manager/membros/' . $id);
        }
    }

    public function atualizarStatusGlobal($id) {
        $this->checkAccess($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $situacao = isset($_POST['situacao']) ? 1 : 0;
            $validade = !empty($_POST['validade']) ? $_POST['validade'] : null;

            $associadoModel = new Associado();
            $associadoModel->updateGlobalStatus($id, $situacao, $validade);

            $this->redirect('/manager/membros/' . $id);
        }
    }

    public function editarMembro($id) {
        $this->checkAccess($id);
        
        $associadoModel = new Associado();
        $membro = $associadoModel->findById($id);

        if (!$membro) {
            $this->redirect('/manager/dashboard');
        }

        $this->view('manager/editar', ['membro' => $membro]);
    }

    public function salvarMembro($id) {
        $this->checkAccess($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nome' => $_POST['nome'] ?? null,
                'cpf' => $_POST['cpf'] ?? null,
                'email' => $_POST['email'] ?? null,
                'telefone' => $_POST['telefone'] ?? null,
                'endereco' => $_POST['endereco'] ?? null,
                'numero' => $_POST['numero'] ?? null,
                'complemento' => $_POST['complemento'] ?? null,
                'bairro' => $_POST['bairro'] ?? null,
                'cep' => $_POST['cep'] ?? null,
                'cidade' => $_POST['cidade'] ?? null,
                'estado' => $_POST['estado'] ?? null,
                'nacionalidade' => $_POST['nacionalidade'] ?? null,
                'naturalidade' => $_POST['naturalidade'] ?? null,
                'data_nascimento' => $_POST['data_nascimento'] ?? null,
                'idade' => $_POST['idade'] ?? null,
                'rg' => $_POST['rg'] ?? null,
                'rg_orgao_emissor' => $_POST['rg_orgao_emissor'] ?? null,
                'filiacao_1_nome' => $_POST['filiacao_1_nome'] ?? null,
                'filiacao_1_cpf' => $_POST['filiacao_1_cpf'] ?? null,
                'filiacao_2_nome' => $_POST['filiacao_2_nome'] ?? null,
                'filiacao_2_cpf' => $_POST['filiacao_2_cpf'] ?? null,
                'estado_civil' => $_POST['estado_civil'] ?? null,
                'forma_comunhao' => $_POST['forma_comunhao'] ?? null,
                'conjuge_nome' => $_POST['conjuge_nome'] ?? null,
                'conjuge_cpf' => $_POST['conjuge_cpf'] ?? null,
                'profissao_1' => $_POST['profissao_1'] ?? null,
                'profissao_1_registro' => $_POST['profissao_1_registro'] ?? null,
                'profissao_1_orgao' => $_POST['profissao_1_orgao'] ?? null,
                'profissao_2' => $_POST['profissao_2'] ?? null,
                'profissao_2_registro' => $_POST['profissao_2_registro'] ?? null,
                'profissao_2_orgao' => $_POST['profissao_2_orgao'] ?? null
            ];

            // Handle potential file replacements
            $uploadPath = __DIR__ . '/../../public/uploads/docs/';
            $documents = [
                'doc_identidade', 'doc_quitacao_eleitoral', 'doc_fiscal_federal',
                'doc_fiscal_estadual', 'doc_fiscal_municipal', 'doc_situacao_cpf',
                'doc_nascimento_casamento'
            ];
            
            $spouseDocuments = [
                'doc_conjuge_identidade', 'doc_conjuge_quitacao_eleitoral', 'doc_conjuge_fiscal_federal',
                'doc_conjuge_fiscal_estadual', 'doc_conjuge_fiscal_municipal', 'doc_conjuge_situacao_cpf',
                'doc_conjuge_nascimento_casamento'
            ];

            foreach ($documents as $doc) {
                if (isset($_FILES[$doc]) && $_FILES[$doc]['error'] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($_FILES[$doc]['name'], PATHINFO_EXTENSION);
                    $filename = uniqid($doc . '_') . '.' . $ext;
                    if (move_uploaded_file($_FILES[$doc]['tmp_name'], $uploadPath . $filename)) {
                        $data[$doc] = '/uploads/docs/' . $filename;
                    }
                } else {
                    $data[$doc] = null; // null means "don't update this field" in our updateDataAndFiles method
                }
            }

            // Handle potential spouse file replacements conditionally
            $isSingleStatus = in_array($data['estado_civil'], ['Solteiro(a)', 'Viúvo(a)', 'Divorciado(a)']);
            
            foreach ($spouseDocuments as $sDoc) {
                if (!$isSingleStatus && isset($_FILES[$sDoc]) && $_FILES[$sDoc]['error'] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($_FILES[$sDoc]['name'], PATHINFO_EXTENSION);
                    $filename = uniqid($sDoc . '_') . '.' . $ext;
                    if (move_uploaded_file($_FILES[$sDoc]['tmp_name'], $uploadPath . $filename)) {
                        $data[$sDoc] = '/uploads/docs/' . $filename;
                    }
                } else {
                    // For single spouses, or when no new file is uploaded, we map to null
                    // This way it won't overwrite existing db fields with empty if they didn't upload a new one
                    $data[$sDoc] = null; 
                }
            }

            $associadoModel = new Associado();
            $associadoModel->updateDataAndFiles($id, $data);

            $this->redirect('/manager/membros/' . $id);
        }
    }

    public function deletarMembro($id) {
        $this->checkAccess($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $associadoModel = new Associado();
            $associadoModel->delete($id);
            $this->redirect('/manager/dashboard');
        }
    }

    public function nominata() {
        if (!isset($_SESSION['manager_id'])) {
            $this->redirect('/manager/login');
        }

        $associacaoId = $_SESSION['manager_id'];
        $nominataModel = new Nominata();
        $associadoModel = new Associado();

        $nominata = $nominataModel->getByAssociacaoId($associacaoId);
        $membros = $associadoModel->getByAssociacaoId($associacaoId);

        $this->view('manager/nominata', [
            'nominata' => $nominata,
            'membros' => $membros,
            'associacao_nome' => $_SESSION['manager_nome']
        ]);
    }

    public function salvarNominata() {
        if (!isset($_SESSION['manager_id'])) {
            $this->redirect('/manager/login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $associacaoId = $_SESSION['manager_id'];
            $nominataModel = new Nominata();
            
            $nominataIds = $_POST['nominata_id'] ?? [];
            $cargos = $_POST['cargo'] ?? [];
            $membroIds = $_POST['associado_id'] ?? [];

            foreach ($nominataIds as $index => $id) {
                if (isset($cargos[$index])) {
                    $nominataModel->updateCargoName($associacaoId, $id, $cargos[$index]);
                }
                if (isset($membroIds[$index])) {
                    $nominataModel->updatePosition($associacaoId, $id, $membroIds[$index]);
                }
            }

            $_SESSION['success_msg'] = "Nominata atualizada com sucesso!";
            $this->redirect('/manager/nominata');
        }
    }

    public function imprimirLista() {
        if (!isset($_SESSION['manager_id'])) {
            $this->redirect('/manager/login');
        }

        $associacaoId = $_SESSION['manager_id'];
        $showNominata = isset($_GET['nominata']) && $_GET['nominata'] === '1';

        $associacaoModel = new Associacao();
        $associacao = $associacaoModel->findById($associacaoId);

        $associadoModel = new Associado();
        $membros = $associadoModel->getByAssociacaoId($associacaoId);

        $nominata = [];
        if ($showNominata) {
            $nominataModel = new Nominata();
            $nominata = $nominataModel->getByAssociacaoId($associacaoId);
        }

        $this->view('shared/presenca', [
            'associacao' => $associacao,
            'membros' => $membros,
            'nominata' => $nominata,
            'show_nominata' => $showNominata
        ]);
    }

    public function confirmarPagamento($id) {
        $finModel = new Financeiro();
        $pagamento = $this->checkPaymentAccess($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $comprovante = null;
            if (isset($_FILES['comprovante']) && $_FILES['comprovante']['error'] === UPLOAD_ERR_OK) {
                $uploadPath = __DIR__ . '/../../public/uploads/financeiro/';
                if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);
                
                $ext = pathinfo($_FILES['comprovante']['name'], PATHINFO_EXTENSION);
                $filename = 'proof_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES['comprovante']['tmp_name'], $uploadPath . $filename)) {
                    $comprovante = '/uploads/financeiro/' . $filename;
                }
            }
            
            $finModel->confirmPayment($id, $comprovante);
            $_SESSION['success_msg'] = "Pagamento confirmado com sucesso!";
            $this->redirect('/manager/membros/' . $pagamento['ref_id']);
        }
    }

    public function recibo($id) {
        $pagamento = $this->checkPaymentAccess($id);
        
        if ($pagamento['status'] !== 'pago') {
            die("Recibo disponível apenas para pagamentos confirmados.");
        }

        $associacaoModel = new Associacao();
        $isManager = isset($_SESSION['manager_id']);
        $id_busca = $isManager ? $_SESSION['manager_id'] : null;

        if (!$id_busca) {
            // Admin context: find the association of the member
            $assocId = $this->getAssociationIdForPayment($pagamento);
            $associacao = $associacaoModel->findById($assocId);
        } else {
            $associacao = $associacaoModel->findById($id_busca);
        }

        $this->view('shared/recibo', [
            'pagamento' => $pagamento,
            'associacao' => $associacao
        ]);
    }

    private function getAssociationIdForPayment($pagamento) {
        if ($pagamento['tipo'] === 'associacao') {
            return $pagamento['ref_id'];
        }
        $associadoModel = new Associado();
        $membro = $associadoModel->findById($pagamento['ref_id']);
        return $membro['associacao_id'];
    }

    public function gerarNovaCobranca($id) {
        $pagamento = $this->checkPaymentAccess($id);
        $finModel = new Financeiro();
        
        if ($finModel->generateNextCharge($id)) {
            $_SESSION['success_msg'] = "Nova cobrança gerada com sucesso!";
        } else {
            $_SESSION['error_msg'] = "Erro ao gerar nova cobrança ou pagamento não é recorrente.";
        }
        
        $this->redirect('/manager/membros/' . $pagamento['ref_id']);
    }

    public function downloadComprovante($id) {
        $pagamento = $this->checkPaymentAccess($id);
        if ($pagamento && $pagamento['comprovante']) {
            $path = __DIR__ . '/../../public' . $pagamento['comprovante'];
            if (file_exists($path)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($path).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($path));
                readfile($path);
                exit;
            }
        }
        $this->redirect('/manager/dashboard');
    }
    private function checkPaymentAccess($id) {
        $finModel = new Financeiro();
        $pagamento = $finModel->findById($id);

        if (!$pagamento || $pagamento['tipo'] !== 'associado') {
            $this->redirect(isset($_SESSION['admin_id']) ? '/admin/dashboard' : '/manager/dashboard');
        }

        $isAdmin = isset($_SESSION['admin_id']);
        $isManager = isset($_SESSION['manager_id']);

        if (!$isAdmin && !$isManager) {
            $this->redirect('/login');
        }

        $associadoModel = new Associado();
        $membro = $associadoModel->findById($pagamento['ref_id']);

        if ($isManager && !$isAdmin) {
            if (!$membro || $membro['associacao_id'] != $_SESSION['manager_id']) {
                $this->redirect('/manager/dashboard');
            }
        }
        
        return $pagamento;
    }

    public function registrarPagamentoManual($id) {
        $this->checkAccess($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $valor = !empty($_POST['valor']) ? (float)str_replace(',', '.', $_POST['valor']) : 0;
            $vencimento = $_POST['data_vencimento'] ?? date('Y-m-d');
            $recorrencia = $_POST['recorrencia'] ?? 'uma_vez';

            $finModel = new Financeiro();
            $finModel->createPayment([
                'tipo' => 'associado',
                'ref_id' => $id,
                'valor' => $valor,
                'data_vencimento' => $vencimento,
                'status' => 'pendente',
                'recorrencia' => $recorrencia
            ]);

            $_SESSION['success_msg'] = "Lançamento manual registrado com sucesso!";
            $this->redirect((isset($_SESSION['admin_id']) ? '/admin' : '/manager') . '/membros/' . $id);
        }
    }

    private function checkAccess($memberId) {
        // The admin can also see this, so let's check if the user is admin OR manager
        $isAdmin = isset($_SESSION['admin_id']);
        $isManager = isset($_SESSION['manager_id']);

        if (!$isAdmin && !$isManager) {
            $this->redirect('/manager/login');
        }

        if ($isManager && !$isAdmin) {
            // Check if the member belongs to the manager's association
            $associadoModel = new Associado();
            $membro = $associadoModel->findById($memberId);
            if (!$membro || $membro['associacao_id'] != $_SESSION['manager_id']) {
                $this->redirect('/manager/dashboard');
            }
        }
    }
}
