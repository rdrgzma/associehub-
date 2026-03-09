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
        
        $this->view('admin/dashboard', [
            'pending' => $pending,
            'associacoes' => $all,
            'metrics' => $metrics
        ]);
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
        
        $this->view('admin/membros', [
            'associacao' => $associacao,
            'membros' => $membros,
            'metrics' => $metrics
        ]);
    }

    public function atualizarMembro($id) {
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
}
