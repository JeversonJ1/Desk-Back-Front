<?php
header('Content-Type: text/plain; charset=utf-8');
@unlink(__DIR__ . '/git_push.php');
echo "1. frontend/api/git_push.php removido com sucesso.\n";
@unlink(__FILE__);
echo "2. frontend/api/git_debug.php removido com sucesso (autolimpeza).\n";

