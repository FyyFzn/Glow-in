<?php
require_once 'config.php';

echo "Adding dummy users...\n";

$dummyUsers = [
    ['username' => 'sarah', 'name' => 'Sarah Wilson', 'points' => 4800],
    ['username' => 'jane', 'name' => 'Jane Doe', 'points' => 4200],
    ['username' => 'emily', 'name' => 'Emily Davis', 'points' => 3800],
    ['username' => 'alex', 'name' => 'Alex Smith', 'points' => 3200],
    ['username' => 'mike', 'name' => 'Mike Johnson', 'points' => 2800],
    ['username' => 'chris', 'name' => 'Chris Lee', 'points' => 2400],
    ['username' => 'amanda', 'name' => 'Amanda Brown', 'points' => 2000],
    ['username' => 'david', 'name' => 'David Wilson', 'points' => 1600],
];

$aiProfilePics = [
    'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80',
    'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80',
    'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80',
    'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=200&q=80',
    'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=200&q=80',
    'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=200&q=80',
    'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80',
    'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?auto=format&fit=crop&w=200&q=80'
];

foreach ($dummyUsers as $index => $userData) {

    $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $check->execute([$userData['username']]);
    if ($check->rowCount() > 0) {
        echo "User {$userData['username']} already exists, skipping...\n";
        continue;
    }

    $api_key = bin2hex(random_bytes(16));
    $hashed_password = password_hash('password123', PASSWORD_DEFAULT);
    $profile_pic = $aiProfilePics[$index % count($aiProfilePics)];

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, name, password, api_key, role, points, profile_pic) VALUES (?, ?, ?, ?, 'postinger', ?, ?)");
        $stmt->execute([
            $userData['username'],
            $userData['name'],
            $hashed_password,
            $api_key,
            $userData['points'],
            $profile_pic
        ]);
        echo "Added user: {$userData['name']} ({$userData['username']})\n";
    } catch (PDOException $e) {
        echo "Error adding user {$userData['username']}: " . $e->getMessage() . "\n";
    }
}

echo "\nDone!\n";
?>