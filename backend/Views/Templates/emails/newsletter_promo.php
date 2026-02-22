<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koketsu Grife - Novidades</title>
    <style>
        body { margin: 0; padding: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #000; color: #fff; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #000; padding-bottom: 40px; }
        .main { background-color: #111; margin: 0 auto; width: 100%; max-width: 600px; border-spacing: 0; color: #fff; border: 1px solid #222; }
        .header { background-color: #000; padding: 30px; text-align: center; border-bottom: 2px solid #f2cc7d; }
        .logo { width: 120px; }
        .content { padding: 40px 30px; text-align: center; }
        .promo-image { width: 100%; height: auto; border-radius: 8px; margin-bottom: 25px; border: 1px solid #333; }
        .title { color: #f2cc7d; font-size: 24px; font-weight: 800; text-transform: uppercase; margin-bottom: 20px; letter-spacing: 1px; }
        .message { color: #ccc; font-size: 16px; line-height: 1.6; margin-bottom: 30px; }
        .button { background-color: #f2cc7d; color: #000; padding: 15px 30px; text-decoration: none; font-weight: bold; border-radius: 4px; text-transform: uppercase; font-size: 14px; display: inline-block; }
        .footer { background-color: #000; padding: 20px; text-align: center; font-size: 12px; color: #666; }
        .footer p { margin: 5px 0; }
        .social-link { color: #f2cc7d; text-decoration: none; margin: 0 10px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <table class="main">
            <tr>
                <td class="header">
                    <!-- URL absoluta do logo (substitua pela real se possível) -->
                    <h1 style="color: #f2cc7d; margin: 0; font-style: italic;">KOKETSU</h1>
                </td>
            </tr>
            <tr>
                <td class="content">
                    <?php if (!empty($imagem_url) || !empty($local_caminho)): ?>
                        <img src="cid:promo_banner" alt="Promoção Koketsu" class="promo-image">
                    <?php endif; ?>
                    
                    <h2 class="title"><?= $assunto ?></h2>
                    
                    <div class="message">
                        <?= nl2br($mensagem) ?>
                    </div>
                    
                    <a href="http://localhost:4000" class="button">Ver Coleção Completa</a>
                </td>
            </tr>
            <tr>
                <td class="footer">
                    <p><b>Koketsu Grife &copy; 2026. Todos os direitos reservados.</b></p>
                    <p>Você recebeu este e-mail porque se cadastrou em nossa newsletter.</p>
                    <div style="margin-top: 15px;">
                        <a href="#" class="social-link">Instagram</a> | 
                        <a href="#" class="social-link">Facebook</a> | 
                        <a href="#" class="social-link">WhatsApp</a>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
