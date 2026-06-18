<?php
header('Content-Type: text/plain; charset=utf-8');
echo "RESTORING LOGO:\n";
$out1 = shell_exec("git restore frontend/assets/img/logo2026.png 2>&1");
echo $out1 . "\n";
echo "STATUS AFTER RESTORE:\n";
echo shell_exec("git status frontend/assets/img/logo2026.png 2>&1");
