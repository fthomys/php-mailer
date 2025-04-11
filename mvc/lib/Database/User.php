<?php

namespace PhpMailer\Database;
class User implements Table
{

    public function getPrimaryKey(): array
    {
        return ['id'];
    }

    public function getColumns(): array
    {
        return [
            'id',
            'username',
            'password_hash',
            'created_at'
        ];
    }

    public function getTableName(): string
    {
        return 'user';
    }


    public function user()
    {

    }
}