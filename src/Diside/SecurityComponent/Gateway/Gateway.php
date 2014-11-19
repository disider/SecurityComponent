<?php

namespace Diside\SecurityComponent\Gateway;

interface Gateway
{
    /**
     * @param array $filters
     * @param int $pageIndex
     * @param int $pageSize
     * @return array
     */
    public function findAll(array $filters = array(), $pageIndex = 0, $pageSize = PHP_INT_MAX);

    /**
     * @return int
     */
    public function countAll(array $filters = array());

    /**
     * @return string
     */
    public function getName();
}