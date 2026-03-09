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
            $cnpj = $_POST['cnpj'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $associacaoModel = new Associacao();
            $manager = $associacaoModel->login($cnpj, $senha);

            if ($manager) {
                $_SESSION['manager_id'] = $manager['id'];
                $_SESSION['manager_nome'] = $manager['nome'];
                $this->redirect('/manager/dashboard');
            } else {
                $this->view('manager/login', ['error' => 'CNPJ ou Senha inválidos.']);
            }
        }
    }

    public function logout() {
        unset($_SESSION['manager_id']);
        unset($_SESSION['manager_nome']);
        $this->redirect('/manager/login');
    }

    public function dashboard() {
        if (!isset($_SESSION['manager_id'])) {
            $this->redirect('/manager/login');
        }

        $associacaoId = $_SESSION['manager_id'];
        $associacaoModel = new Associacao();
        $associadoModel = new Associado();
        $membros = $associadoModel->getByAssociacaoId($associacaoId);

        // Fetch association specific metrics
        $metrics = [
            'total_membros' => $associadoModel->getTotalByAssociacao($associacaoId),
            'membros_ativos' => $associadoModel->getTotalActiveByAssociacao($associacaoId),
            'membros_inativos' => $associadoModel->getTotalInactiveByAssociacao($associacaoId)
        ];

        $associacao = $associacaoModel->findById($associacaoId);
        
        $this->view('manager/dashboard', [
            'membros' => $membros,
            'associacao_nome' => $_SESSION['manager_nome'],
            'associacao' => $associacao,
            'metrics' => $metrics
        ]);
    }

    public function membro($id) {
        $this->checkAccess($id);
        
        $associadoModel = new Associado();
        $membro = $associadoModel->findById($id);

        if (!$membro) {
            $this->redirect('/manager/dashboard');
        }

        $this->view('manager/membro', ['membro' => $membro]);
    }

    public function ficha($id) {
        $this->checkAccess($id);

        $associadoModel = new Associado();
        $membro = $associadoModel->findById($id);

        if (!$membro) {
            die("Membro não encontrado");
        }

        $this->view('manager/ficha', ['membro' => $membro]);
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
                $updates[$doc . '_status'] = isset($_POST[$doc . '_status']) ? 1 : 0;
                $updates[$doc . '_validade'] = !empty($_POST[$doc . '_validade']) ? $_POST[$doc . '_validade'] : null;
            }

            $associadoModel = new Associado();
            $associadoModel->updateDocumentStatus($id, $updates);

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
                'doc_fiscal_estadual', 'doc_fiscal_municipal', 'doc_situacao_cpf'
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
