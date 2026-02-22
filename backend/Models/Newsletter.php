<?php

namespace App\Koketsu\Models;
use PDO;

class Newsletter {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Insere um novo e-mail na newsletter
     */
    public function inserir(string $email) {
        $sql = "INSERT INTO tbl_newsletter (email_newsletter, data_inscricao) VALUES (:email, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

    /**
     * Verifica se um e-mail já existe
     */
    public function emailExiste(string $email) {
        $sql = "SELECT id_newsletter FROM tbl_newsletter WHERE email_newsletter = :email AND excluido_em IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lista todos os inscritos não excluídos
     */
    public function listarTodos() {
        $sql = "SELECT * FROM tbl_newsletter WHERE excluido_em IS NULL ORDER BY data_inscricao DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Exclui (soft delete) um inscrito
     */
    public function excluir(int $id) {
        $sql = "UPDATE tbl_newsletter SET excluido_em = NOW() WHERE id_newsletter = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
