<?php

namespace PhpMailer;

use PDO;
use PDOStatement;
use PhpMailer\Database\Table;

class Delete
{
    protected PDO $connection;

    protected Table $table;

    protected string $whereStmt;

    protected array $whereData;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function from(Table $table): static
    {
        $this->table = $table;
        return $this;
    }

    public function where(string $whereStmt, array $whereData): static
    {
        $this->whereStmt = $whereStmt;
        $this->whereData = $whereData;
        return $this;
    }

    public function execute(): bool
    {
        $stmt = $this->prepareExec();
        if ($stmt === null) {
            return false;
        }

        return $stmt->execute();
    }

    protected function prepareExec(): ?PDOStatement
    {
        if (!isset($this->table)) {
            echo "Error: No table specified for delete operation.\n";
            return null;
        }

        $query = "DELETE FROM {$this->table->getTableName()}";

        if (isset($this->whereStmt)) {
            $query .= " WHERE {$this->whereStmt}";
        } else {
            echo "Error: DELETE without WHERE is not allowed.\n";
            return null;
        }

        $stmt = $this->connection->prepare($query);

        foreach ($this->whereData as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        return $stmt;
    }
}
