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
