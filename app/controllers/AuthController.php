<?php

class AuthController extends Controller {
    public function loginForm() {
        if (isset($_SESSION['admin_id'])) {
            $this->redirect('/admin/dashboard');
        }
        $this->view('admin/login');
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['senha'] ?? '';
            
            $adminModel = new Admin();
            $admin = $adminModel->authenticate($email, $password);
            
            if ($admin) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_nome'] = $admin['nome'];
                $_SESSION['admin_email'] = $admin['email'];
                $this->redirect('/admin/dashboard');
            } else {
                $this->view('admin/login', ['error' => 'Credenciais inválidas.']);
            }
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('/admin/login');
    }
}
