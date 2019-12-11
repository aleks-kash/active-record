<?php

namespace core\query;

use core\Model;

class SelectQueryModel extends Model
{
    public function __construct()
    {
        $this->select = [];
        $this->where = [];
        $this->orderBy = [];
        $this->groupBy = [];
        $this->limit = 0;
    }

    public function getSelect(): string
    {
        return 'SELECT ' . ($this->select ? implode(', ', $this->select) : '*');
    }

    public function setSelect(string $select = null): self
    {
        if(!$select){
            $this->select = [];
        } else {
            $this->select[] = array_merge($this->select, [$select]);
        }

        return $this;
    }

    public function getWhere(): string
    {
        return 'WHERE ' . ($this->where ? implode('', $this->where) : 1);
    }

    public function setWhere(string $where = null): self
    {
        if(!$where){
            $this->where = [];
        } else {
            $this->where = array_merge($this->where, [$where]);
        }

        return $this;
    }

    public function getOrderBy(): ? string
    {
        return $this->orderBy ? 'ORDER BY ' . implode(', ', $this->orderBy) : null;
    }

    public function setOrderBy(string $orderBy = null): self
    {
        if(!$orderBy){
            $this->orderBy = [];
        } else {
            $orderBy = array_merge($this->orderBy, [$orderBy]);
            $this->orderBy = $orderBy;
        }

        return $this;
    }

    public function getGroupBy(): ? string
    {
        return $this->groupBy ? 'GROUP BY ' . implode(', ', $this->groupBy) : null;
    }

    public function setGroupBy(string $groupBy = null): self
    {
        if(!$groupBy){
            $this->groupBy = [];
        } else {
            $this->groupBy[] = array_merge($this->groupBy, [$groupBy]);
        }

        return $this;
    }

    public function getLimit(): ? int
    {
        return $this->limit ? 'LIMIT ' . (int) $this->limit : null;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }
}
