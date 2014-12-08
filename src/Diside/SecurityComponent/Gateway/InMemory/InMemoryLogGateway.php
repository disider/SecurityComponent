<?php

namespace Diside\SecurityComponent\Gateway\InMemory;

use Diside\SecurityComponent\Gateway\LogGateway;
use Diside\SecurityComponent\Model\Log;

class InMemoryLogGateway extends InMemoryBaseGateway implements LogGateway
{
    private $logs = array();

    public function getName()
    {
        return self::NAME;
    }

    public function save(Log $log)
    {
        return $this->persist($log);
    }

    protected function applyFilters($logs, array $filters = array())
    {
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

}