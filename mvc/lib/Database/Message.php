<?php

namespace PhpMailer\Database;


class Message implements Table
{
    public function getPrimaryKey(): array
    {
        return ['id'];
    }

    public function getColumns(): array
    {
        return ['id', 'sender_id', 'receiver_id', 'message', 'sent_at', 'read_at'];
    }

    public function getTableName(): string
    {
        return 'messages';
    }
}
