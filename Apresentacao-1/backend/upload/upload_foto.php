<?php
session_start();
include('conexao.php'); // Verifique se o nome do seu arquivo de conexão está certo

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['nova_foto'])) {
    
    // AQUI vai a linha que você perguntou:
    $pasta = "upload/usuarios/"; 
    
    $arquivo = $_FILES['nova_foto'];
    $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

    // Gera um nome único para não bugar se dois usuários subirem "foto.jpg"
    $novoNome = uniqid() . "." . $extensao;
    $caminhoNoBanco = $pasta . $novoNome;

    // Move o arquivo físico para a pasta upload/usuarios/
    if (move_uploaded_file($arquivo["tmp_name"], $caminhoNoBanco)) {
        $id_usuario = $_SESSION['id_usuarios']; 
        
        // Salva o caminho no banco de dados
        $sql = "UPDATE tbl_usuarios SET foto_usuarios = '$caminhoNoBanco' WHERE id_usuarios = '$id_usuario'";
        $mysqli->query($sql);

        // Atualiza a sessão para a foto mudar na sidebar imediatamente
        $_SESSION['foto_usuarios'] = $caminhoNoBanco;

        header("Location: dashboard.php"); // Volta para o painel
    }
}
?>