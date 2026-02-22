<?php
$perfil = $perfil ?? [];
?>

<div class="page-wrapper">
    <div class="premium-card">
        <div class="card-header">
            <h1 class="gold-gradient-text"><i class="fa fa-user-circle"></i> Editar Perfil</h1>
            <p>Atualize as informações de contato e localização do sistema.</p>
        </div>

        <form action="/backend/perfil/atualizar/<?= $perfil['id_perfil']; ?>" method="post" class="modern-form">
            
            <div class="form-group">
                <label class="label-gray" for="telefone_perfil">Telefone de Contato</label>
                <div class="input-container">
                    <i class="fa fa-phone input-icon"></i>
                    <input type="tel" id="telefone_perfil" name="telefone_perfil" 
                           placeholder="(00) 00000-0000"
                           value="<?= $perfil['telefone_perfil']; ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="label-gray" for="endereco_perfil">Endereço Completo</label>
                <div class="input-container">
                    <i class="fa fa-map-marker input-icon"></i>
                    <input type="text" id="endereco_perfil" name="endereco_perfil" 
                           placeholder="Rua, Número, Bairro - Cidade"
                           value="<?= $perfil['endereco_perfil'];?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="label-gray" for="data_cadastro">Data de Cadastro</label>
                <div class="input-container">
                    <i class="fa fa-calendar input-icon"></i>
                    <input type="date" id="data_cadastro" name="data_cadastro" 
                           value="<?= $perfil['data_cadastro']; ?>" required>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="fa fa-save"></i> Salvar Alterações
                </button>
                <a href="/backend/pedido/listar" class="btn-cancel">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<style>
    /* Estilização Geral baseada nas imagens do seu Dashboard */
    .page-wrapper {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .premium-card {
        background-color: #111; /* Preto profundo do seu dashboard */
        border-radius: 20px;
        border: 1px solid #222;
        box-shadow: 0 30px 60px rgba(0,0,0,0.8);
        width: 100%;
        max-width: 600px;
        padding: 40px;
        position: relative;
    }

    /* Título com degradê dourado */
    .gold-gradient-text {
        background: linear-gradient(135deg, #f2cc7d 0%, #b8860b 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
        font-size: 24px;
        text-align: center;
        margin-bottom: 10px;
    }

    .card-header p {
        color: #888;
        font-size: 14px;
        text-align: center;
        margin-bottom: 40px;
    }

    /* Inputs Modernos (Fundo escuro, borda que brilha em ouro) */
    .form-group { margin-bottom: 25px; }

    .label-gray {
        color: #e2c93e; /* Dourado nos labels para destaque */
        font-size: 11px;
        font-weight: 700;
        margin-bottom: 10px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .input-container {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon {
        position: absolute;
        left: 15px;
        color: #555;
    }

    .modern-form input {
        background-color: #1a1a1a !important; /* Mesma cor dos outros forms */
        color: #fff !important;
        border: 1px solid #333;
        border-radius: 10px;
        padding: 14px 15px 14px 45px;
        width: 100%;
        font-size: 15px;
        transition: all 0.3s ease;
    }

    .modern-form input:focus {
        border-color: #f2cc7d;
        outline: none;
        box-shadow: 0 0 10px rgba(242, 204, 125, 0.1);
        background-color: #222 !important;
    }

    /* Botão de Ação Estilo Koketsu */
    .btn-save {
        background: #f2cc7d;
        color: #000;
        font-weight: 700;
        border: none;
        padding: 16px;
        border-radius: 10px;
        width: 100%;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.3s;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }

    .btn-save:hover {
        background: #fff;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(242, 204, 125, 0.2);
    }

    .btn-cancel {
        display: block;
        text-align: center;
        margin-top: 15px;
        color: #666;
        text-decoration: none;
        font-size: 13px;
        transition: 0.3s;
    }

    .btn-cancel:hover { color: #fff; }

    /* Ajuste para ícone de calendário nativo */
    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
        cursor: pointer;
    }
</style>