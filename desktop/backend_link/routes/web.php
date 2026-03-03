<?php

use App\Koketsu\Controles\AuthController;
use App\Koketsu\Controles\Admin\DashboardController;

// Login site (cliente)
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'authenticar']);
$router->get('/logout', [AuthController::class, 'logout']);

// Painel Admin
$router->get('/admin/dashboard', [DashboardController::class, 'index']);
