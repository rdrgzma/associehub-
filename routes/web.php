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
$router->get('/admin/associacoes', ['AdminController', 'associacoes']);
$router->get('/admin/associacoes/{id}/aprovar', ['AdminController', 'aprovar']);
$router->post('/admin/associacoes/{id}/rejeitar', ['AdminController', 'rejeitar']);
$router->get('/admin/associacoes/{id}/membros', ['AdminController', 'membros']);
$router->post('/admin/associados/{id}/atualizar', ['AdminController', 'atualizarMembro']);
$router->post('/admin/associados/{id}/atualizar-status-global', ['AdminController', 'atualizarStatusGlobal']);
$router->get('/admin/associados/{id}/editar', ['AdminController', 'editarMembro']);
$router->post('/admin/associados/{id}/editar', ['AdminController', 'salvarMembro']);
$router->post('/admin/associados/{id}/deletar', ['AdminController', 'deletarMembro']);

// Manager Area routes
$router->get('/manager/login', ['ManagerController', 'loginForm']);
$router->post('/manager/login', ['ManagerController', 'login']);
$router->get('/manager/logout', ['ManagerController', 'logout']);
$router->get('/manager/dashboard', ['ManagerController', 'dashboard']);
$router->get('/manager/membros/{id}', ['ManagerController', 'membro']);
$router->post('/manager/membros/{id}/atualizar', ['ManagerController', 'atualizarMembro']);
$router->post('/manager/membros/{id}/atualizar-status-global', ['ManagerController', 'atualizarStatusGlobal']);
$router->get('/manager/membros/{id}/ficha', ['ManagerController', 'ficha']);
$router->get('/manager/membros/{id}/editar', ['ManagerController', 'editarMembro']);
$router->post('/manager/membros/{id}/editar', ['ManagerController', 'salvarMembro']);
$router->post('/manager/membros/{id}/deletar', ['ManagerController', 'deletarMembro']);
