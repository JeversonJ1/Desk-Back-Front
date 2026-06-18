<?php
header('Content-Type: text/plain; charset=utf-8');
echo "GIT STATUS:\n";
echo shell_exec("git status 2>&1");
echo "\nGIT DIFF FOR DELETED IMAGES:\n";
echo shell_exec("git status --porcelain | findstr /R \"^.D\" 2>&1");
