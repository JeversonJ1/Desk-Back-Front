<?php
namespace App\Koketsu\Models;
use PDO;

class Preferencias {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function buscarPorUsuario($id_usuario) {
        $sql = "SELECT * FROM tbl_preferencias WHERE id_usuarios = :id_usuario LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function salvarOuAtualizar($id_usuario, $dados) {
        $existente = $this->buscarPorUsuario($id_usuario);

        // Sanitize defaults
        $t_cam = $dados['tamanho_camiseta'] ?? null;
        $t_cal = $dados['tamanho_calca'] ?? null;
        $t_sap = $dados['tamanho_calcado'] ?? null;
        
        // Checkboxes return true/false or 1/0
        $n_ped = !empty($dados['notif_pedidos']) ? 1 : 0;
        $n_ofe = !empty($dados['notif_ofertas']) ? 1 : 0;
        $n_wpp = !empty($dados['notif_whatsapp']) ? 1 : 0;
        $dois_fa = !empty($dados['dois_fatores_ativo']) ? 1 : 0;

        if ($existente) {
            $sql = "UPDATE tbl_preferencias SET 
                tamanho_camiseta = :t_cam, 
                tamanho_calca = :t_cal, 
                tamanho_calcado = :t_sap,
                notif_pedidos = :n_ped,
                notif_ofertas = :n_ofe,
                notif_whatsapp = :n_wpp,
                dois_fatores_ativo = :dois_fa
                WHERE id_usuarios = :id_usuario";
        } else {
            $sql = "INSERT INTO tbl_preferencias 
                (id_usuarios, tamanho_camiseta, tamanho_calca, tamanho_calcado, notif_pedidos, notif_ofertas, notif_whatsapp, dois_fatores_ativo)
                VALUES 
                (:id_usuario, :t_cam, :t_cal, :t_sap, :n_ped, :n_ofe, :n_wpp, :dois_fa)";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':t_cam', $t_cam);
        $stmt->bindParam(':t_cal', $t_cal);
        $stmt->bindParam(':t_sap', $t_sap);
        $stmt->bindParam(':n_ped', $n_ped, PDO::PARAM_INT);
        $stmt->bindParam(':n_ofe', $n_ofe, PDO::PARAM_INT);
        $stmt->bindParam(':n_wpp', $n_wpp, PDO::PARAM_INT);
        $stmt->bindParam(':dois_fa', $dois_fa, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
