<?php
require __DIR__ . '/common.php';
json_headers();
$id = visitor_id();
$result = with_data(function (&$d) use ($id) { return ['downloads'=>(int)$d['downloads'], 'likes'=>(int)$d['likes'], 'liked'=>in_array($id, $d['liked'], true)]; });
echo json_encode($result);
