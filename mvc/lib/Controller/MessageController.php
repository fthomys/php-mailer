<?php

namespace PhpMailer\Controller;

use PhpMailer\Controller;
use PhpMailer\Database\Message;
use PhpMailer\Insert;
use PhpMailer\Library\Snowflake\Snowflake;
use PhpMailer\Select;

class MessageController extends Controller
{
    public function sendAction(): void
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Nicht eingeloggt']);
            return;
        }

        $senderId = $_SESSION['user_id'];
        $receiverId = $_POST['receiver_id'] ?? null;
        $messageText = $_POST['message'] ?? '';

        if (!$receiverId || trim($messageText) === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Ungültige Eingabe']);
            return;
        }

        // Rate Limiting - Token Bucket: 5 messages per 2 seconds
        $now = microtime(true);
        $bucket = &$_SESSION['rate_limit'][$senderId]['bucket'];
        $lastCheck = &$_SESSION['rate_limit'][$senderId]['last_check'];

        if (!isset($bucket)) {
            $bucket = 5;
            $lastCheck = $now;
        }

        $elapsed = $now - $lastCheck;
        $refillRate = 5 / 2;

        $tokensToAdd = floor($elapsed * $refillRate);
        if ($tokensToAdd > 0) {
            $bucket = min(1, $bucket + $tokensToAdd);
            $lastCheck = $now;
        }

        if ($bucket <= 0) {
            http_response_code(429);
            echo json_encode(['error' => 'Too many requests, please wait.']);
            return;
        }

        $bucket--;

        $flakegen = Snowflake::getInstance();

        $insert = (new Insert($this->connection))
            ->into(new Message())
            ->columns(['id', 'sender_id', 'receiver_id', 'message'])
            ->values([
                $flakegen->generate(),
                $senderId,
                $receiverId,
                $messageText,
            ])
            ->executeStmt();

        echo json_encode(['status' => 'gesendet']);
    }


    public function fetchAction(): void
    {



        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Nicht eingeloggt']);
            return;
        }

        $userId = $_SESSION['user_id'];
        $chatPartnerId = $_GET['with'] ?? null;

        if (!$chatPartnerId) {
            http_response_code(400);
            echo json_encode(['error' => 'Empfänger fehlt']);
            return;
        }

        $select = new Select($this->connection);
        $select->from(new Message())
            ->where(
                '(sender_id = :me AND receiver_id = :them) OR (sender_id = :them AND receiver_id = :me)',
                ['me' => $userId, 'them' => $chatPartnerId]
            );
        $messages = $select->fetchAll();


        $updateRead = $this->connection->prepare("
    UPDATE messages 
    SET read_at = NOW()
    WHERE receiver_id = :me AND sender_id = :them AND read_at IS NULL
");
        $updateRead->execute(['me' => $userId, 'them' => $chatPartnerId]);


        echo json_encode($messages);
    }

    public function indexAction()
    {
    }
}
