<?php
// Small file-based counter store. Keep data.json writable by the PHP process.
const DATA_FILE = __DIR__ . '/data.json';
function with_data($callback) {
    $fp = fopen(DATA_FILE, 'c+');
    if (!$fp) { http_response_code(500); header('Content-Type: application/json; charset=utf-8'); echo json_encode(['error'=>'storage unavailable']); exit; }
    flock($fp, LOCK_EX);
    $raw = stream_get_contents($fp);
    $data = json_decode($raw ?: '', true);
    if (!is_array($data)) $data = ['downloads'=>0, 'likes'=>0, 'liked'=>[]];
    $result = $callback($data);
    ftruncate($fp, 0); rewind($fp); fwrite($fp, json_encode($data, JSON_UNESCAPED_UNICODE)); fflush($fp);
    flock($fp, LOCK_UN); fclose($fp);
    return $result;
}
function visitor_id() {
    if (empty($_COOKIE['apex_visitor']) || !preg_match('/^[a-f0-9]{64}$/', $_COOKIE['apex_visitor'])) {
        $id = bin2hex(random_bytes(32));
        setcookie('apex_visitor', $id, ['expires'=>time()+60*60*24*365*2, 'path'=>'/', 'secure'=>!empty($_SERVER['HTTPS']), 'httponly'=>true, 'samesite'=>'Lax']);
        return $id;
    }
    return $_COOKIE['apex_visitor'];
}
function json_headers() { header('Content-Type: application/json; charset=utf-8'); header('Cache-Control: no-store'); }
