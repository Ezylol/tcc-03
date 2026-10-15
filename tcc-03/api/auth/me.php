<?php
requireAuth();
$user = currentUser();
if (!$user) {
    sendJson(['success' => false, 'data' => null, 'message' => 'User not found.'], 404);
}

sendJson(['success' => true, 'data' => ['user' => $user], 'message' => 'User data loaded.'], 200);
