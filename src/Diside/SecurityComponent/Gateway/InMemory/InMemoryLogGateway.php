<?php

namespace Diside\SecurityComponent\Gateway\InMemory;

use Diside\SecurityComponent\Gateway\LogGateway;
use Diside\SecurityComponent\Model\Log;

class InMemoryLogGateway implements LogGateway
{
    private $logs = array();

    public function save(Log $log)
    {
        if($log->getId() == null) {
            $log->setId(count($this->logs) + 1);
        }

        $this->logs[$log->getId()] = $log;

        return $log;
    }

    public function findAll(array $filters = array(), $pageIndex = 0, $pageSize = PHP_INT_MAX)
    {
        $logs = $this->filterLogs($filters);

        return array_slice($logs, $pageIndex * $pageSize, $pageSize);
    }

    public function countAll(array $filters = array())
    {
        return count($this->filterLogs($filters));
    }

    private function filterLogs($filters)
    {
        $logs = $this->logs;

        if(array_key_exists(self::FILTER_BY_COMPANY_ID, $filters))
            $logs = $this->filterByCompanyId($filters[self::FILTER_BY_COMPANY_ID], $logs);

        if(array_key_exists(self::FILTER_BY_ACTION, $filters))
            $logs = $this->filterByAction($filters[self::FILTER_BY_ACTION], $logs);

        return $logs;
    }

    private function filterByCompanyId($companyId, $logs)
    {
        $results = array();

        /** @var Log $log */
        foreach ($logs as $log) {
            if ($log->getUser()->getCompanyId() === $companyId)
                $results[] = $log;
        }

        return $results;
    }

    private function filterByAction($action, $logs)
    {
        $results = array();

        /** @var Log $log */
        foreach ($logs as $log) {
            if ($log->getAction() == $action)
                $results[] = $log;
        }

        return $results;
    }

    public function getName()
    {
        return self::NAME;
    }

}