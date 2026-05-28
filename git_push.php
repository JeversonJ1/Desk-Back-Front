<?php
header('Content-Type: text/plain; charset=utf-8');
echo "--- EXECUÇÃO DO PROCESSO GIT KOKETSU ---\n\n";

$output = [];
$return_var = 0;

echo "1. Adicionando modificações ao Git...\n";
exec('git add . 2>&1', $output, $return_var);
echo "Status: " . ($return_var === 0 ? "SUCESSO" : "ERRO ($return_var)") . "\n";
echo implode("\n", $output) . "\n\n";

$output = [];
echo "2. Realizando Commit...\n";
exec('git commit -m "feat: upgrade premium da newsletter e correcao de rotas do painel" 2>&1', $output, $return_var);
echo "Status: " . ($return_var === 0 ? "SUCESSO" : "ERRO ($return_var)") . "\n";
echo implode("\n", $output) . "\n\n";

$output = [];
echo "3. Enviando para o GitHub (Push)...\n";
exec('git push 2>&1', $output, $return_var);
echo "Status: " . ($return_var === 0 ? "SUCESSO" : "ERRO ($return_var)") . "\n";
echo implode("\n", $output) . "\n\n";

echo "--- FIM DO PROCESSO ---\n";
