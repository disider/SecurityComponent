<?php

namespace Diside\SecurityComponent\Gateway\InMemory;

use Diside\SecurityComponent\Gateway\Gateway;

abstract class InMemoryBaseGateway implements Gateway
{
    private $items = array();

    public function persist($item)
    {
        if($item->getId() == null) {
            $item->setId(count($this->items) + 1);
        }

        $this->items[$item->getId()] = $item;

        return $item;
    }

    public function delete($id)
    {
        foreach($this->items as $item) {
            if($item->getId() == $id) {
                unset($this->items[$id]);
                return $item;
            }
        }
    }

    /**
     * @return array
     */
    public function findAll(array $filters = array(), $pageIndex = 0, $pageSize = PHP_INT_MAX)
    {
        $items = $this->applyFilters($this->items, $filters);

        return array_slice($items, $pageIndex * $pageSize, $pageSize);
    }

    public function countAll(array $filters = array())
    {
        return count($this->applyFilters($this->items, $filters));
    }

    public function findOneById($id)
    {
        foreach($this->items as $item)
            if($item->getId() == $id)
                return $item;

        return null;
    }

    protected function getItems()
    {
        return $this->items;
    }

    abstract protected function applyFilters($items, array $filters = array());
}