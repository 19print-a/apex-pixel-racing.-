<?php
require __DIR__ . '/common.php';
json_headers();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['error'=>'POST required']); exit; }
$id = visitor_id();
$result = with_data(function (&$d) use ($id) {
    if (!in_array($id, $d['liked'], true)) { $d['liked'][] = $id; $d['likes'] = (int)$d['likes'] + 1; }
    return ['likes'=>(int)$d['likes'], 'liked'=>true];
});
echo json_encode($result);
