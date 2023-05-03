<?php

namespace App\External\Salesforce\Utils;

class QueryUtils
{
    private $command = 'SELECT';
    private $selectedFields = [];
    private ?string $table;
    private $conditions = [];
    private ?string $order;
    private ?string $group;

    /**
     * Set the command (useless if the command if SELECT)
     *
     * @param string $command
     * @return void
     */
    public function setCommand(string $command): void
    {
        $this->command = $command;
    }

    /**
     * Set a new field to the fields to select
     *
     * @param string $fieldName
     * @return void
     */
    public function addField(string $fieldName): void
    {
        $this->selectedFields[] = $fieldName;
    }

    /**
     * Set the table to query
     *
     * @param string $table
     * @return void
     */
    public function setTable(string $table): void
    {
        $this->table = $table;
    }

    /**
     * Compare the field with the given string
     *
     * @param string $field
     * @param string $operator
     * @param string $expected
     * @return void
     */
    public function setCompareTextValueCondition(
        string $field,
        string $operator,
        string $expected
    ): void {
        $this->conditions[] = $field . ' ' . $operator . " '" . $expected . "'";
    }

    /**
     * Compare the field with the given string
     *
     * @param string $field
     * @param string $operator
     * @param string $expected
     * @return void
     */
    public function setStringCondition(
        string $condition
    ): void {
        $this->conditions[] = $condition;
    }

    public function setOrXCondition($condition1, $condition2): void
    {
        $this->conditions[] = '( ' . $condition1 . ' OR ' . $condition2 . ' )';
    }

    public function setNullCondition(string $field): void
    {
        $this->conditions[] = $field . ' = null';
    }

    public function setNotNullCondition(string $field): void
    {
        $this->conditions[] = $field . ' != null';
    }

    /**
     * Compare the field with the given number
     *
     * @param string $field
     * @param string $operator
     * @param string $expected
     * @return void
     */
    public function setCompareNumericValueCondition(
        string $field,
        string $operator,
        string $expected
    ): void {
        $this->conditions[] = $field . ' ' . $operator . ' ' . $expected;
    }

    /**
     * Compare the field with the given date
     *
     * @param string $field
     * @param \Datetime $date
     * @param string $operator
     * @return void
     */
    public function setDateCondition(
        string $field,
        \Datetime $date,
        string $operator
    ): void {
        $this->conditions[] = $field . " " . $operator . " " . $date->format('Y-m-d');
    }

    /**
     * Set IN condition where the field has to be in the array
     *
     * @param string $field
     * @param array $array
     * @return void
     */
    public function setInArrayCondition(
        string $field,
        array $array
    ): void {
        $this->conditions[] = $field . ' IN ' . $this->concatArrayForInCondition($array);
    }

    /**
     * Set the fields to order by
     *
     * @param array $fields
     * @return void
     */
    public function orderBy(array $fields): void
    {
        $this->order = implode(', ', $fields);
    }

    /**
     * Set the fields to group by
     *
     * @param array $fields
     * @return void
     */
    public function groupBy(array $fields): void
    {
        $this->group = implode(', ', $fields);
    }

    /**
     * Return the formatted query as a string
     *
     * @return string
     */
    public function getQuery(): string
    {
        $query = $this->command . ' ';
        $query .= implode(', ', $this->selectedFields) . ' ';
        $query .= 'FROM ' . $this->table . ' ';

        if (count($this->conditions) > 0) {
            $query .= 'WHERE ';
            $query .= implode(' AND ', $this->conditions) . ' ';
        }

        if (isset($this->group)) {
            $query .= 'GROUP BY ' . $this->group . ' ';
        }
        if (isset($this->order)) {
            $query .= 'ORDER BY ' . $this->order;
        }
        return $query;
    }

    /**
     * Format the array for IN conditions as intellegible string for SOQL
     *
     * @param array $array
     * @return string
     */
    private function concatArrayForInCondition(array $array): string
    {
        return "('" . implode("', '", $array) . "') ";
    }
}
