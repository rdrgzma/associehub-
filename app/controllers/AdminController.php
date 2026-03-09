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
        
        $this->view('admin/dashboard', [
            'pending' => $pending,
            'associacoes' => $all
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
        
        $this->view('admin/membros', [
            'associacao' => $associacao,
            'membros' => $membros
        ]);
    }
}
