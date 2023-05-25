<?php

namespace App\Stats\AccountBasedStatistics;

use App\External\Salesforce\Utils\QueryUtils;
use App\Stats\AbstractStatistic;

abstract class AbstractAccountBasedStatistic extends AbstractStatistic
{
    public const TYPE_EXPENSE = 'charge';
    public const TYPE_PRODUCT = 'produit';

    protected array $accountsRanges = [];

    protected function setAccounts(array $accountRange = []): void
    {
        $this->accountsRanges = $accountRange;
    }

    public function setPeriod(\DateTime $start, \DateTime $end): void
    {
        $this->start = $start;
        $this->end = $end;
        $this->hasPeriod = true;
    }

    protected function firstCall()
    {
        $q = new QueryUtils();
        $q->setTable('numm__CosAccountingVoucher__c');

        $q->addField('numm__EcritureGenerale__r.numm__IdAccountingCode__r.Name');
        $q->addField('numm__EcritureGenerale__r.numm__JournalLib__c');
        $q->addField('numm__Analytic1__r.Name');
        $q->addField('numm__Analytic2__r.Name');
        $q->addField('numm__Analytic3__r.Name');
        $q->addField('numm__Analytic4__r.Name');
        $q->addField('numm__CreditEntity__c');
        $q->addField('numm__DebitEntity__c');

        if ($this->hasPeriod) {
            $q->setDateCondition('numm__EcritureGenerale__r.numm__Piece__r.numm__BaselineDate__c', $this->start, '>=');
            $q->setDateCondition('numm__EcritureGenerale__r.numm__Piece__r.numm__BaselineDate__c', $this->end, '<=');
        }
        if (count($this->accountsRanges) > 0) {
            foreach ($this->accountsRanges as $value) {
                $range[] =  "numm__EcritureGenerale__r.numm__IdAccountingCode__r.Name LIKE '" . $value . "%'";
            }
            $range = implode(' OR ', $range);
            $q->setStringCondition('(' . $range . ')');
        }
        return $this->client->fetch->sql($q->getQuery());
    }

    protected function fetchData()
    {
        $stats = $this->firstCall();
        $this->rawData = array_merge($this->rawData, $stats->records);

        if (isset($stats->nextRecordsUrl) && $stats->done !== true) {
            do {
                $stats = $this->client->fetch->getEagerResult($stats->nextRecordsUrl);
                $this->rawData = array_merge($this->rawData, $stats->records);
            } while (isset($stats->nextRecordsUrl) && $stats->done !== true);
        }
    }


    public function parse()
    {
        foreach($this->rawData as $allocation) {
            $axis1 = $allocation->numm__Analytic1__r->Name;
            $axis2 = $allocation->numm__Analytic2__r->Name;
            $account = $allocation->numm__EcritureGenerale__r->numm__IdAccountingCode__r->Name;

            if(!isset($this->parsedData[$axis1][$axis2]['detail'][$account])) {
                $this->parsedData[$axis1][$axis2]['detail'][$account] = 0;
            }
            if (!isset($this->parsedData[$axis1][$axis2]['total'])) {
                $this->parsedData[$axis1][$axis2]['total'] = 0;
                $this->parsedData[$axis1][$axis2]['type'] = $this->getType();
            }
            $this->parsedData[$axis1][$axis2]['detail'][$account] += ($allocation->numm__CreditEntity__c - $allocation->numm__DebitEntity__c);
            $this->parsedData[$axis1][$axis2]['total'] += ($allocation->numm__CreditEntity__c - $allocation->numm__DebitEntity__c);
        }
    }


    public function getResult()
    {
        $this->fetchData();
        $this->parse();
        return $this->parsedData;
    }
}
