<?php
namespace App\Koketsu\Validadores;

class UsuarioValidador {
    public static function ValidarEntradas($dados) {
        $erros = [];

        if (empty($dados['nome_usuarios']) && empty($dados['nome_usuarios'])) {
            $erros[] = "O campo nome é obrigatório.";
        }

        if (empty($dados['email_usuarios']) && empty($dados['email_usuarios'])) {
            $erros[] = "O campo email é obrigatório e deve ser um email válido.";
        } elseif (!filter_var($dados['email_usuarios'], FILTER_VALIDATE_EMAIL)) {
            $erros[] = "O campo email deve ser um email válido.";
        }

        if (empty($dados['senha_usuarios']) && empty($dados['senha_usuarios'])) {
            $erros[] = "O campo senha é obrigatório e deve ter pelo menos 6 caracteres.";
        } elseif (strlen($dados['senha_usuarios']) < 6) {
            $erros[] = "O campo senha deve ter pelo menos 6 caracteres.";
        }
        
        return $erros;
    }
}