<?php

namespace Diside\SecurityComponent\Gateway;

use Diside\SecurityComponent\Model\Log;

interface LogGateway extends Gateway
{
    const NAME = 'log_gateway';

    const FILTER_BY_COMPANY_ID = 'filter_by_company_id';
    const FILTER_BY_ACTION = 'filter_by_action';

    /**
     * @param Log $log
     * @return Log
     */
    public function save(Log $log);
}