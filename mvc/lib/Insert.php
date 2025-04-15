<?php

namespace PhpMailer;

use PDO;
use PDOStatement;
use PhpMailer\Database\Table;

class Insert
{
    protected PDO $connection;

    protected array $columns;

    protected Table $table;

    protected array $values;


    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function into(Table $table): static
    {
        $this->table = $table;
        return $this;
    }

    public function columns(array $columns): static
    {
        $this->columns = $columns;
        return $this;
    }

    public function values(array $values): static
    {
        $this->values = $values;
        return $this;
    }

    public function executeStmt(): static
    {
        $stmt = $this->prepareExec();
        try {
            $stmt->execute();
        } catch (Throwable $e) {
            echo("Exception thrown {$e->getMessage()}");
        }
        return $this;
    }

    protected function prepareExec(): ?PDOStatement
    {
        if (!isset($this->columns)) {
            $columns = "*";
        } else {


            $allFound = true;

            foreach ($this->columns as $column) {
                $found = false;
                foreach ($this->table->getColumns() as $tableColumn) {
                    if ($column === $tableColumn) {
                        $found = true;
                        break;
                    }

                }
                if (!$found) {
                    echo "Error: Column {$column} not found in table {$this->table->getTableName()}";
                    $allFound = false;
                }
            }

            if (!$allFound) {
                return null;
            }

            $columns = implode(', ', $this->columns);
        }


        $placeholders = implode(', ', array_fill(0, count($this->values), '?'));
        $query = "INSERT INTO {$this->table->getTableName()} ({$columns}) VALUES ({$placeholders})";

        $stmt = $this->connection->prepare($query);
        foreach ($this->values as $key => $value) {
            $stmt->bindValue($key + 1, $value);
        }

        return $stmt;
    }
}
