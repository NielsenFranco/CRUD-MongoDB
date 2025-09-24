<?php
require 'config.php';
header('Content-Type: application/json');

$query = $_GET['q'] ?? '';
$filter = [];
if (!empty($query)) {
    $filter = ['$or' => [
        ['titulo' => ['$regex' => $query, '$options' => 'i']],
        ['autor' => ['$regex' => $query, '$options' => 'i']]
    ]];
}

$books = $collection->find($filter, ['limit' => 50])->toArray();
echo json_encode($books);
?>
