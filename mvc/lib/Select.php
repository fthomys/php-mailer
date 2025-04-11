<?php

namespace PhpMailer;

use PDO;
use PDOStatement;
use PhpMailer\Database\Table;

class Select
{
    protected PDO $connection;

    protected array $columns;

    protected Table $table;

    protected string $whereStmt;

    protected array $whereData;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function from(Table $table): void
    {
        $this->table = $table;
    }

    public function columns(array $columns): void
    {
        $this->columns = $columns;
    }

    public function where(string $whereStmt, array $whereData): void
    {
        $this->whereStmt = $whereStmt;
        $this->whereData = $whereData;
    }

    public function fetchAll(): array
    {
        $stmt = $this->prepareExec();
        if ($stmt === null) {
            return [];
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function prepareExec(): ?PDOStatement
    {
        if (isset($this->columns) && ! in_array('*', $this->columns )) {
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
                    echo "Error: Column {$column} not found in table {$this->table->getTableName()}\n";
                    $allFound = false;
                }
            }

            if (!$allFound) {
                return null;
            }

            $columns = implode(', ', $this->columns);
        }
        else {
            $columns = '*';
        }

        $query = "SELECT {$columns} FROM {$this->table->getTableName()}";


        if (isset($this->whereStmt)) {
            $query .= " WHERE {$this->whereStmt}";
        }


        $stmt = $this->connection->prepare($query);

        if (isset($this->whereStmt)) {
            foreach ($this->whereData as $key => $value) {
                $stmt->bindValue($key, $value);
            }
        }

        return $stmt;
    }
}
