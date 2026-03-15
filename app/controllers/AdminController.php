<?php

class AdminController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['admin_id'])) {
            $this->redirect('/admin/login');
        }
    }

    public function dashboard() {
        $associacaoModel = new Associacao();
        $associadoModel = new Associado();
        
        $pending = $associacaoModel->getPending();
        $all = $associacaoModel->getAll();
        
        foreach ($all as &$assoc) {
            $assoc['total_membros'] = $associadoModel->getTotalByAssociacao($assoc['id']);
        }
        
        // Fetch global metrics
        $metrics = [
            'total_associacoes' => $associacaoModel->getTotalCount(),
            'total_pendentes' => $associacaoModel->getPendingCount(),
            'total_membros' => $associadoModel->getTotalCount(),
            'membros_ativos' => $associadoModel->getTotalActiveCount()
        ];
        
        // Fetch PIX configs
        $configModel = new Configuracao();
        $config = $configModel->getAll();

        $this->view('admin/dashboard', [
            'pending' => $pending,
            'associacoes' => $all,
            'metrics' => $metrics,
            'config' => $config
        ]);
    }

    public function salvarPix() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $configModel = new Configuracao();
            
            $configModel->set('pix_chave', $_POST['pix_chave'] ?? '');
            
            // Format currency if needed or store raw string
            $valorStr = $_POST['pix_valor_cadastro'] ?? '';
            $configModel->set('pix_valor_cadastro', $valorStr);
            
            $configModel->set('pix_instrucoes', $_POST['pix_instrucoes'] ?? '');
            
            $_SESSION['success_msg'] = "Configurações de Pagamento (PIX) salva com sucesso!";
            $this->redirect('/admin/dashboard');
        }
    }

    public function associacoes() {
        $associacaoModel = new Associacao();
        $associacoes = $associacaoModel->getAll();
        $this->view('admin/associacoes', ['associacoes' => $associacoes]);
    }

    public function aprovar($id) {
        $associacaoModel = new Associacao();
        $associacaoModel->approve($id);
        $this->redirect('/admin/dashboard');
    }

    public function rejeitar($id) {
        $associacaoModel = new Associacao();
        $associacaoModel->reject($id);
        $this->redirect('/admin/dashboard');
    }

    public function gerarNovaSenha($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $associacaoModel = new Associacao();
            $associacaoModel->gerarNovaSenha($id);
            $this->redirect('/admin/associacoes');
        }
    }

    public function alterarSenha() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $novaSenha = $_POST['nova_senha'] ?? '';
            $confirmaSenha = $_POST['confirma_senha'] ?? '';

            if (empty($novaSenha) || empty($confirmaSenha)) {
                $_SESSION['error_msg'] = "Preencha ambas as senhas.";
                $this->redirect('/admin/dashboard');
                return;
            }

            if ($novaSenha !== $confirmaSenha) {
                $_SESSION['error_msg'] = "Puxa! As senhas não conferem. Tente novamente.";
                $this->redirect('/admin/dashboard');
                return;
            }

            $adminModel = new Admin();
            $success = $adminModel->updatePassword($_SESSION['admin_id'], $novaSenha);

            if ($success) {
                $_SESSION['success_msg'] = "Sua senha foi atualizada com sucesso!";
            } else {
                $_SESSION['error_msg'] = "Ocorreu um erro ao atualizar a senha.";
            }
            
            $this->redirect('/admin/dashboard');
        }
    }

    public function revelarSenhaManager() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            // Determine content type of request
            $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
            if ($contentType === "application/json") {
                $content = trim(file_get_contents("php://input"));
                $decoded = json_decode($content, true);
                $associacaoId = $decoded['associacao_id'] ?? null;
                $adminPassword = $decoded['admin_senha'] ?? null;
            } else {
                $associacaoId = $_POST['associacao_id'] ?? null;
                $adminPassword = $_POST['admin_senha'] ?? null;
            }

            if (!$associacaoId || !$adminPassword) {
                echo json_encode(['success' => false, 'message' => 'Dados incompletos fornecidos.']);
                return;
            }

            $adminModel = new Admin();
            $adminEmail = $_SESSION['admin_email'] ?? '';
            
            // Fallback for sessions that were created before the email was added to the session
            if (!$adminEmail && isset($_SESSION['admin_id'])) {
                $adminData = $adminModel->findById($_SESSION['admin_id']);
                if ($adminData) {
                    $adminEmail = $adminData['email'];
                    $_SESSION['admin_email'] = $adminEmail; // Fix the session for subsequent requests
                }
            }

            if (!$adminEmail) {
                echo json_encode(['success' => false, 'message' => 'Sessão inválida.']);
                return;
            }

            $adminUser = $adminModel->authenticate($adminEmail, $adminPassword);

            if (!$adminUser) {
                echo json_encode(['success' => false, 'message' => 'Senha de administrador inválida.']);
                return;
            }

            $associacaoModel = new Associacao();
            $associacao = $associacaoModel->findById($associacaoId);

            if ($associacao && $associacao['status'] === 'approved') {
                echo json_encode(['success' => true, 'password' => $associacao['acesso_senha']]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Associação não encontrada ou não aprovada.']);
            }
        }
    }

    public function membros($id) {
        $associacaoModel = new Associacao();
        $associadoModel = new Associado();
        
        $associacao = $associacaoModel->findById($id);
        if (!$associacao) {
            $this->redirect('/admin/dashboard');
        }
        
        $membros = $associadoModel->getByAssociacaoId($id);
        
        $metrics = [
            'total_membros' => $associadoModel->getTotalByAssociacao($id),
            'membros_ativos' => $associadoModel->getTotalActiveByAssociacao($id),
            'membros_inativos' => $associadoModel->getTotalInactiveByAssociacao($id)
        ];

        $configModel = new Configuracao();
        $config = $configModel->getAll();

        $this->view('admin/membros', [
            'associacao' => $associacao,
            'membros' => $membros,
            'metrics' => $metrics,
            'config' => $config
        ]);
    }

    // --- ASSOCIATIONS MANAGEMENT ---

    public function editarAssociacao($id) {
        $associacaoModel = new Associacao();
        $associacao = $associacaoModel->findById($id);

        if (!$associacao) {
            $this->redirect('/admin/associacoes');
        }

        $this->view('admin/editar_associacao', ['associacao' => $associacao]);
    }

    public function atualizarAssociacao($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nome' => $_POST['nome'] ?? '',
                'cnpj' => preg_replace('/[^0-9]/', '', $_POST['cnpj'] ?? ''),
                'responsavel' => $_POST['responsavel'] ?? '',
                'email' => $_POST['email'] ?? '',
                'telefone' => preg_replace('/[^0-9]/', '', $_POST['telefone'] ?? ''),
                'status' => $_POST['status'] ?? 'pending'
            ];

            if (empty($data['nome']) || empty($data['cnpj']) || empty($data['responsavel']) || empty($data['email'])) {
                $_SESSION['error_msg'] = "Preencha todos os campos obrigatórios.";
                $this->redirect('/admin/associacoes/' . $id . '/editar');
                return;
            }

            $associacaoModel = new Associacao();
            
            try {
                if ($associacaoModel->update($id, $data)) {
                    // Se o status foi alterado para 'approved', garantir que tenha token e senha
                    if ($data['status'] === 'approved') {
                        $associacaoModel->approve($id);
                    }
                    $_SESSION['success_msg'] = "Dados da Associação atualizados com sucesso!";
                } else {
                    $_SESSION['error_msg'] = "Nenhuma alteração foi realizada.";
                }
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) { // Constraint violation (usually unique CNPJ)
                    $_SESSION['error_msg'] = "Já existe outra associação registrada com este CNPJ.";
                } else {
                    $_SESSION['error_msg'] = "Erro ao atualizar os dados: " . $e->getMessage();
                }
            }
            
            $this->redirect('/admin/associacoes');
        }
    }

    public function deletarAssociacao($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $associacaoModel = new Associacao();
            $associacao = $associacaoModel->findById($id);

            if (!$associacao) {
                $_SESSION['error_msg'] = "Associação não encontrada.";
                $this->redirect('/admin/associacoes');
                return;
            }

            // 1. Apagar todos os membros e seus respectivos documentos
            $associadoModel = new Associado();
            $membros = $associadoModel->getByAssociacaoId($id);
            
            $errosFiles = 0;
            foreach ($membros as $membro) {
                // O método delete() no AssociadoModel já faz o unlink dos anexos físicos!
                if (!$associadoModel->delete($membro['id'])) {
                    $errosFiles++;
                }
            }

            // 2. Apagar a Associação pai
            if ($associacaoModel->delete($id)) {
                $_SESSION['success_msg'] = "Associação e todos os seus registros foram excluídos permanentemente.";
            } else {
                $_SESSION['error_msg'] = "Falha ao excluir o registro da Associação.";
            }

            $this->redirect('/admin/associacoes');
        }
    }

    public function atualizarMembro($id) {
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $situacao = isset($_POST['situacao']) ? 1 : 0;
            $validade = !empty($_POST['validade']) ? $_POST['validade'] : null;

            $associadoModel = new Associado();
            $associadoModel->updateGlobalStatus($id, $situacao, $validade);

            $this->redirect('/manager/membros/' . $id);
        }
    }

    public function editarMembro($id) {
        $associadoModel = new Associado();
        $membro = $associadoModel->findById($id);

        if (!$membro) {
            $this->redirect('/admin/dashboard');
        }

        $this->view('manager/editar', ['membro' => $membro]);
    }

    public function salvarMembro($id) {
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
                    $data[$doc] = null; // null means "don't update this field"
                }
            }

            $associadoModel = new Associado();
            $associadoModel->updateDataAndFiles($id, $data);

            $this->redirect('/manager/membros/' . $id);
        }
    }

    public function deletarMembro($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $associadoModel = new Associado();
            $membro = $associadoModel->findById($id);
            if ($membro) {
                $assocId = $membro['associacao_id'];
                $associadoModel->delete($id);
                $this->redirect('/admin/associacoes/' . $assocId . '/membros');
            } else {
                $this->redirect('/admin/dashboard');
            }
        }
    }

    // --- ADMIN USERS MANAGEMENT ---

    public function usuarios() {
        $adminModel = new Admin();
        $admins = $adminModel->getAll();
        $this->view('admin/usuarios', ['admins' => $admins]);
    }

    public function criarUsuario() {
        $this->view('admin/usuario_form');
    }

    public function salvarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nome' => $_POST['nome'] ?? '',
                'email' => $_POST['email'] ?? '',
                'senha' => $_POST['senha'] ?? ''
            ];

            if (empty($data['nome']) || empty($data['email']) || empty($data['senha'])) {
                $_SESSION['error_msg'] = "Preencha todos os campos obrigatórios.";
                $this->redirect('/admin/usuarios/criar');
                return;
            }

            $adminModel = new Admin();
            if ($adminModel->create($data)) {
                $_SESSION['success_msg'] = "Administrador cadastrado com sucesso!";
            } else {
                $_SESSION['error_msg'] = "Falha ao cadastrar o administrador. E-mail pode já estar em uso.";
            }
            $this->redirect('/admin/usuarios');
        }
    }

    public function editarUsuario($id) {
        $adminModel = new Admin();
        $admin = $adminModel->findById($id);

        if (!$admin) {
            $this->redirect('/admin/usuarios');
        }

        $this->view('admin/usuario_form', ['admin' => $admin]);
    }

    public function atualizarUsuario($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nome' => $_POST['nome'] ?? '',
                'email' => $_POST['email'] ?? '',
                'senha' => $_POST['senha'] ?? '' // Optional in edit
            ];

            if (empty($data['nome']) || empty($data['email'])) {
                $_SESSION['error_msg'] = "Nome e E-mail são obrigatórios.";
                $this->redirect('/admin/usuarios/' . $id . '/editar');
                return;
            }

            $adminModel = new Admin();
            if ($adminModel->update($id, $data)) {
                $_SESSION['success_msg'] = "Administrador atualizado com sucesso!";
            } else {
                $_SESSION['error_msg'] = "Falha ao atualizar o administrador.";
            }
            $this->redirect('/admin/usuarios');
        }
    }

    public function deletarUsuario($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Prevent deleting oneself
            if ($id == $_SESSION['admin_id']) {
                $_SESSION['error_msg'] = "Você não pode deletar a sua própria conta ativa.";
                $this->redirect('/admin/usuarios');
                return;
            }

            $adminModel = new Admin();
            if ($adminModel->delete($id)) {
                $_SESSION['success_msg'] = "Administrador removido com sucesso!";
            } else {
                $_SESSION['error_msg'] = "Falha ao remover o administrador.";
            }
            $this->redirect('/admin/usuarios');
        }
    }
}
