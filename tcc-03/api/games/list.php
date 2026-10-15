<?php

$search = trim((string) ($_GET['search'] ?? ''));
$genre = trim((string) ($_GET['genre'] ?? ''));
$sort = trim((string) ($_GET['sort'] ?? ''));

$sql = 'SELECT * FROM games WHERE 1 = 1';
$params = [];

if ($search !== '') {
    $sql .= ' AND (title LIKE :search OR genre LIKE :search OR developer LIKE :search)';
    $params['search'] = '%' . $search . '%';
}

if ($genre !== '') {
    $sql .= ' AND genre = :genre';
    $params['genre'] = $genre;
}

if ($sort === 'price_asc') {
    $sql .= ' ORDER BY price ASC';
} elseif ($sort === 'price_desc') {
    $sql .= ' ORDER BY price DESC';
} elseif ($sort === 'title_asc') {
    $sql .= ' ORDER BY title ASC';
} else {
    $sql .= ' ORDER BY id DESC';
}

$stmt = db()->prepare($sql);
$stmt->execute($params);
$games = $stmt->fetchAll();

sendJson(['success' => true, 'data' => $games, 'message' => 'Games loaded successfully.'], 200);
