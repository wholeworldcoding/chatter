<?php
header('Content-Type: application/json');

$messagesFile = __DIR__ . '/messages.json';

// Initialize file if not exists
if (!file_exists($messagesFile)) {
    file_put_contents($messagesFile, json_encode([]));
}

// GET: return messages
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $messages = json_decode(file_get_contents($messagesFile), true);
    echo json_encode($messages);
    exit;
}

// POST: add message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['text']) || trim($data['text']) === '') {
        http_response_code(400);
        echo json_encode(['error' => 'Message cannot be empty']);
        exit;
    }

    $messages = json_decode(file_get_contents($messagesFile), true);
    $messages[] = [
        'text' => htmlspecialchars($data['text']),
        'time' => date('H:i:s')
    ];
    file_put_contents($messagesFile, json_encode($messages));
    echo json_encode(['success' => true]);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
