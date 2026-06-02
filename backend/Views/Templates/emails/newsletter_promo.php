<!DOCTYPE html>
<html lang="pt-br" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Koketsu Grife - Novidades</title>
    <style>
        /* ---- RESET ---- */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }

        /* ---- BASE ---- */
        body {
            margin: 0;
            padding: 0;
            background-color: #0a0a0a;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #ffffff;
        }

        .email-wrapper {
            width: 100%;
            background-color: #0a0a0a;
            padding: 30px 0 50px;
        }

        .email-container {
            max-width: 620px;
            margin: 0 auto;
            background-color: #111111;
            border: 1px solid #2a2a2a;
            border-radius: 4px;
            overflow: hidden;
        }

        /* ---- HEADER ---- */
        .header {
            background: linear-gradient(180deg, #000000 0%, #0f0f0f 100%);
            padding: 36px 40px 28px;
            text-align: center;
            border-bottom: 2px solid #F2C84B;
        }

        .header-logo {
            display: block;
            margin: 0 auto 18px;
            height: 64px;
            width: auto;
        }

        .header-tagline {
            font-size: 10px;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: #F2C84B;
            margin: 0;
            font-weight: 600;
        }

        /* ---- DIVIDER ---- */
        .gold-divider {
            width: 60px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #F2C84B, transparent);
            margin: 0 auto;
            border: none;
        }

        /* ---- PROMO BADGE ---- */
        .promo-badge {
            display: inline-block;
            background: linear-gradient(135deg, #F2C84B, #d4a800);
            color: #000000;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 3px;
            text-transform: uppercase;
            padding: 5px 18px;
            border-radius: 100px;
            margin-bottom: 22px;
        }

        /* ---- BANNER IMAGE ---- */
        .promo-image-wrap {
            margin: 0 30px 28px;
        }

        .promo-image {
            width: 100%;
            max-width: 100%;
            height: auto;
            border-radius: 6px;
            display: block;
            border: 1px solid #2a2a2a;
        }

        /* ---- CONTENT ---- */
        .content {
            padding: 36px 40px 30px;
            text-align: center;
        }

        .title {
            color: #F2C84B;
            font-size: 26px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0 0 18px;
            line-height: 1.2;
        }

        .message {
            color: #cccccc;
            font-size: 15px;
            line-height: 1.8;
            margin: 0 0 32px;
        }

        /* ---- CTA BUTTON ---- */
        .btn-gold {
            display: inline-block;
            background: linear-gradient(135deg, #F2C84B 0%, #d4a800 100%);
            color: #000000 !important;
            text-decoration: none;
            font-weight: 800;
            font-size: 13px;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 16px 40px;
            border-radius: 4px;
            box-shadow: 0 4px 20px rgba(242, 200, 75, 0.30);
        }

        /* ---- INFO STRIP ---- */
        .info-strip {
            background-color: #0d0d0d;
            border-top: 1px solid #1e1e1e;
            border-bottom: 1px solid #1e1e1e;
            padding: 20px 40px;
            text-align: center;
        }

        .info-strip-item {
            display: inline-block;
            color: #888888;
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 0 16px;
        }

        .info-strip-item span {
            color: #F2C84B;
            font-weight: 700;
        }

        .info-strip-sep {
            color: #333;
            padding: 0 4px;
        }

        /* ---- FOOTER ---- */
        .footer {
            background-color: #000000;
            padding: 28px 40px 24px;
            text-align: center;
        }

        .footer-brand {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #F2C84B;
            margin: 0 0 10px;
        }

        .footer-text {
            font-size: 11px;
            color: #555555;
            margin: 0 0 16px;
            line-height: 1.6;
        }

        .footer-socials {
            margin: 0 0 16px;
        }

        .footer-socials a {
            color: #F2C84B;
            text-decoration: none;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 0 10px;
        }

        .footer-copy {
            font-size: 10px;
            color: #333333;
            margin: 0;
        }

        /* ---- RESPONSIVE ---- */
        @media only screen and (max-width: 620px) {
            .content { padding: 24px 22px 20px !important; }
            .header { padding: 28px 22px 22px !important; }
            .footer { padding: 22px 22px 18px !important; }
            .promo-image-wrap { margin: 0 16px 22px !important; }
            .title { font-size: 20px !important; }
            .info-strip { padding: 16px 20px !important; }
            .info-strip-item { display: block; padding: 4px 0; }
            .info-strip-sep { display: none; }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <table class="email-container" cellpadding="0" cellspacing="0" border="0" width="620" align="center">

            <!-- ===== HEADER ===== -->
            <tr>
                <td class="header">
                    <?php if (!empty($logo_cid)): ?>
                        <img src="cid:<?= $logo_cid ?>" alt="Koketsu Grife" class="header-logo">
                    <?php else: ?>
                        <h1 style="color:#F2C84B; margin:0 0 10px; font-size:32px; font-style:italic; letter-spacing:4px; font-weight:900;">KOKETSU</h1>
                    <?php endif; ?>
                    <p class="header-tagline">Premium Streetwear &nbsp;•&nbsp; Exclusividade &nbsp;•&nbsp; Estilo</p>
                </td>
            </tr>

            <!-- ===== PROMO BANNER IMAGE ===== -->
            <?php if (!empty($imagem_url) || !empty($local_caminho)): ?>
            <tr>
                <td style="padding-top:30px; background:#111111;">
                    <div class="promo-image-wrap">
                        <img src="cid:promo_banner" alt="Promoção Koketsu Grife" class="promo-image">
                    </div>
                </td>
            </tr>
            <?php endif; ?>

            <!-- ===== MAIN CONTENT ===== -->
            <tr>
                <td class="content">
                    <div class="promo-badge">✦ &nbsp; Nova Novidade &nbsp; ✦</div>
                    <hr class="gold-divider" style="margin-bottom:24px;">

                    <h2 class="title"><?= htmlspecialchars($assunto) ?></h2>

                    <div class="message">
                        <?= nl2br(htmlspecialchars($mensagem)) ?>
                    </div>

                    <a href="http://koketsu.com.br" class="btn-gold">Ver Coleção Completa</a>
                </td>
            </tr>

            <!-- ===== FOOTER ===== -->
            <tr>
                <td class="footer">
                    <p class="footer-brand">Koketsu Grife</p>

                    <div class="footer-socials">
                        <a href="#">Instagram</a>
                        <span style="color:#333;">|</span>
                        <a href="#">Facebook</a>
                        <span style="color:#333;">|</span>
                        <a href="#">WhatsApp</a>
                    </div>

                    <p class="footer-text">
                        Você recebeu este e-mail porque se inscreveu na newsletter da Koketsu Grife.<br>
                        Para cancelar a inscrição, <a href="#" style="color:#555; text-decoration:underline;">clique aqui</a>.
                    </p>

                    <hr style="border:none; border-top:1px solid #1a1a1a; margin:12px 0;">

                    <p class="footer-copy">© <?= date('Y') ?> Koketsu Grife. Todos os direitos reservados.</p>
                </td>
            </tr>

        </table>
    </div>
</body>
</html>
