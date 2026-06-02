<?php
header('Content-Type: text/plain; charset=utf-8');
echo "--- EXECUÇÃO DO PROCESSO GIT KOKETSU via PROXY ---\n\n";

// Garante que o PHP mude para o diretório raiz do projeto antes de rodar os comandos Git
chdir(__DIR__ . '/../../');

$output = [];
$return_var = 0;

echo "1. Adicionando modificações ao Git...\n";
exec('git add . 2>&1', $output, $return_var);
echo "Status: " . ($return_var === 0 ? "SUCESSO" : "ERRO ($return_var)") . "\n";
echo implode("\n", $output) . "\n\n";

$output = [];
echo "2. Realizando Commit...\n";
exec('git commit -m "feat: upgrade premium das paginas de newsletter e configuracoes" 2>&1', $output, $return_var);
echo "Status: " . ($return_var === 0 ? "SUCESSO" : "ERRO ($return_var)") . "\n";
echo implode("\n", $output) . "\n\n";

$output = [];
echo "3. Enviando para o GitHub (Push)...\n";
exec('git push 2>&1', $output, $return_var);
echo "Status: " . ($return_var === 0 ? "SUCESSO" : "ERRO ($return_var)") . "\n";
echo implode("\n", $output) . "\n\n";

echo "--- FIM DO PROCESSO ---\n";
