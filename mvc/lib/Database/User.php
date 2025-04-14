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
            'created_at',
            'display_name'
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