<?php
header('Content-Type: text/plain; charset=utf-8');

echo "Staging files (git add -A)...\n";
echo shell_exec('git add -A 2>&1') . "\n";

echo "Committing changes (git commit)...\n";
echo shell_exec('git commit -m "Remocao completa da pagina de relatorios e melhorias de interface" 2>&1') . "\n";

echo "Pushing to remote repository (git push)...\n";
echo shell_exec('git push origin copilot/improve-slow-code 2>&1') . "\n";
