<?php

namespace App\Stats;

use App\External\Salesforce\SalesforceClient;
use App\External\Salesforce\Utils\QueryUtils;

abstract class AbstractStatistic 
{
    protected string $slug;
    protected array $conseillerIdArray = [];
    protected bool $hasPeriod = false;
    protected \DateTime $start;
    protected \DateTime $end;

    protected array $rawData = [];
    protected array $parsedData = [];

    public function __construct(
        protected SalesforceClient $client
    )
    {
        
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setPeriod(\DateTime $start, \DateTime $end): void
    {
        $this->start = $start;
        $this->end = $end;
        $this->hasPeriod = true;
    }


    protected function fetchData()
    {
        $records = [];
        $stats = $this->firstCall();
        $records = array_merge($records, $stats->records);
        if (isset($stats->nextRecordsUrl) && $stats->done !== true) {
            do {
                $stats = $this->client->fetch->getEagerResult($stats->nextRecordsUrl);
                $records = array_merge($records, $stats->records);
            } while (isset($stats->nextRecordsUrl) && $stats->done !== true);
        }

        return $records;
    }

    abstract protected function firstCall();
    abstract protected function parse ();
    abstract public function getResult();
}