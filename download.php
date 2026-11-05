<?php
require __DIR__ . '/common.php';
$file = __DIR__ . '/game.apk';
if (!is_file($file) || !is_readable($file)) { http_response_code(404); echo 'Game file not found'; exit; }
$fp = fopen($file, 'rb');
if (!$fp) { http_response_code(500); echo 'Unable to open game file'; exit; }
with_data(function (&$d) { $d['downloads'] = (int)$d['downloads'] + 1; return true; });
header('Content-Type: application/vnd.android.package-archive');
header('Content-Disposition: attachment; filename="Apex-Pixel-Racing.apk"');
header('Content-Length: ' . filesize($file));
header('Cache-Control: no-store');
fpassthru($fp); fclose($fp);
