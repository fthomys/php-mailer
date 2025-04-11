<?php

namespace PhpMailer\Database;

interface Table
{
    /**
     * Get the primary key of the table
     *
     * @return string
     */
    public function getPrimaryKey(): array;

    /**
     * Get the columns of the table
     *
     * @return array
     */
    public function getColumns(): array;

    /**
     * Get the name of the table
     *
     * @return string
     */
    public function getTableName(): string;
}
