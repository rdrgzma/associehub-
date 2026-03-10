<?php

// Front routes
$router->get('/', ['AssociacaoController', 'cadastroForm']);
$router->post('/cadastro', ['AssociacaoController', 'registrar']);
$router->get('/sucesso', ['AssociacaoController', 'sucesso']);

// Member registration routes
$router->get('/cadastro/{token}', ['AssociadoController', 'formulario']);
$router->post('/cadastro/{token}', ['AssociadoController', 'registrar']);
$router->get('/associado/sucesso', ['AssociadoController', 'sucesso']);

// Admin auth routes
$router->get('/admin/login', ['AuthController', 'loginForm']);
$router->post('/admin/login', ['AuthController', 'login']);
$router->get('/admin/logout', ['AuthController', 'logout']);

// Admin restricted routes
$router->get('/admin/dashboard', ['AdminController', 'dashboard']);
$router->post('/admin/alterar-senha', ['AdminController', 'alterarSenha']);
$router->post('/admin/salvar-pix', ['AdminController', 'salvarPix']);
$router->get('/admin/associacoes', ['AdminController', 'associacoes']);
$router->get('/admin/associacoes/{id}/aprovar', ['AdminController', 'aprovar']);
$router->post('/admin/associacoes/{id}/rejeitar', ['AdminController', 'rejeitar']);
$router->post('/admin/associacoes/{id}/nova-senha', ['AdminController', 'gerarNovaSenha']);
$router->get('/admin/associacoes/{id}/editar', ['AdminController', 'editarAssociacao']);
$router->post('/admin/associacoes/{id}/atualizar', ['AdminController', 'atualizarAssociacao']);
$router->post('/admin/associacoes/{id}/deletar', ['AdminController', 'deletarAssociacao']);
$router->post('/admin/associacoes/revelar-senha', ['AdminController', 'revelarSenhaManager']);
$router->get('/admin/associacoes/{id}/membros', ['AdminController', 'membros']);
$router->post('/admin/associados/{id}/atualizar', ['AdminController', 'atualizarMembro']);
$router->post('/admin/associados/{id}/upload-ficha', ['AdminController', 'uploadFicha']);
$router->post('/admin/associados/{id}/atualizar-status-global', ['AdminController', 'atualizarStatusGlobal']);
$router->get('/admin/associados/{id}/editar', ['AdminController', 'editarMembro']);
$router->post('/admin/associados/{id}/editar', ['AdminController', 'salvarMembro']);
$router->post('/admin/associados/{id}/deletar', ['AdminController', 'deletarMembro']);

// Admin User Management
$router->get('/admin/usuarios', ['AdminController', 'usuarios']);
$router->get('/admin/usuarios/criar', ['AdminController', 'criarUsuario']);
$router->post('/admin/usuarios', ['AdminController', 'salvarUsuario']);
$router->get('/admin/usuarios/{id}/editar', ['AdminController', 'editarUsuario']);
$router->post('/admin/usuarios/{id}/atualizar', ['AdminController', 'atualizarUsuario']);
$router->post('/admin/usuarios/{id}/deletar', ['AdminController', 'deletarUsuario']);

// Manager Area routes
$router->get('/manager/login', ['ManagerController', 'loginForm']);
$router->post('/manager/login', ['ManagerController', 'login']);
$router->get('/manager/logout', ['ManagerController', 'logout']);
$router->get('/manager/dashboard', ['ManagerController', 'dashboard']);
$router->post('/manager/alterar-senha', ['ManagerController', 'alterarSenha']);
$router->get('/manager/membros/{id}', ['ManagerController', 'membro']);
$router->post('/manager/membros/{id}/atualizar', ['ManagerController', 'atualizarMembro']);
$router->post('/manager/membros/{id}/upload-ficha', ['ManagerController', 'uploadFicha']);
$router->post('/manager/membros/{id}/atualizar-status-global', ['ManagerController', 'atualizarStatusGlobal']);
$router->get('/manager/membros/{id}/ficha', ['ManagerController', 'ficha']);
$router->get('/manager/membros/{id}/editar', ['ManagerController', 'editarMembro']);
$router->post('/manager/membros/{id}/editar', ['ManagerController', 'salvarMembro']);
$router->post('/manager/membros/{id}/deletar', ['ManagerController', 'deletarMembro']);
