<?php

$file = __DIR__ . '/../storage/upload/note_2001/file_1769082129_7d9e312f.pdf';

echo 'user: '.trim(shell_exec('whoami'))."\n";
echo 'which: '.trim(shell_exec('which pdftotext 2>&1'))."\n";
exec('/opt/homebrew/bin/pdftotext ' . escapeshellarg($file) . ' - 2>&1', $out, $rc);
var_dump($rc, $out);

?>