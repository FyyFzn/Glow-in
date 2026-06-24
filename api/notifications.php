<?php
require_once 'middleware.php';
$user = checkApiKey();
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {

        $dummyNotifs = [
            [
                'id' => 1,
                'user_id' => 2,
                'username' => 'jane_doe',
                'name' => 'Jane Doe',
                'profile_pic' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29a0?auto=format&fit=crop&w=200&q=80',
                'type' => 'like',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 minutes ago'))
            ],
            [
                'id' => 2,
                'user_id' => 3,
                'username' => 'john_smith',
                'name' => 'John Smith',
                'profile_pic' => 'https://images.unsplash.com/photo-1599566150163-29194dc29e6?auto=format&fit=crop&w=200&q=80',
                'type' => 'comment',
                'is_read' => 0,
                'created_at' => date('Y-m-d H:i:s', strtotime('-30 minutes ago'))
            ],
            [
                'id' => 3,
                'user_id' => 4,
                'username' => 'sarah_wilson',
                'name' => 'Sarah Wilson',
                'profile_pic' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=200&q=80',
                'type' => 'follow',
                'is_read' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 hours ago'))
            ],
            [
                'id' => 4,
                'user_id' => 5,
                'username' => 'mike_jones',
                'name' => 'Mike Jones',
                'profile_pic' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=200&q=80',
                'type' => 'like',
                'is_read' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day ago'))
            ],
            [
                'id' => 5,
                'user_id' => 6,
                'username' => 'emily_davis',
                'name' => 'Emily Davis',
                'profile_pic' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80',
                'type' => 'comment',
                'is_read' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days ago'))
            ]
        ];
        echo json_encode($dummyNotifs);
    } else if ($method === 'PUT') {
        echo json_encode(['success' => true, 'message' => 'All notifications marked as read']);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
