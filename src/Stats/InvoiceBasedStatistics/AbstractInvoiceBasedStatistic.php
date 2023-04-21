<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\External\Salesforce\Utils\QueryUtils;
use App\Stats\AbstractStatistic;

abstract class AbstractInvoiceBasedStatistic extends AbstractStatistic
{
    protected array $accounts = [];
    protected array $conseillerIdArray = [];
    
    protected function setAccounts(array $accounts = []): void
    {
        $this->accounts = $accounts;
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

        $q->addField('numm__Tech_Entite__r.Name');
        $q->addField('numm__Piece__r.numm__Role_du_Tiers__r.numm__ThirdParty__r.IdDataLake__c');
        $q->addField('numm__Piece__r.numm__Role_du_Tiers__r.numm__ThirdParty__r.ID_Datalake_Referent__c');
        $q->addField('numm__IdAccountingCode__r.Name');
        $q->addField('numm__Amount__c');
        $q->addField('numm__Tech_SensMontant__c');
        
        $q->setTable('numm__VoucherTransaction__c');

        if ($this->conseillerIdArray && count($this->conseillerIdArray) > 1) {
            $q->setOrXCondition(
                $q->setInArrayCondition(
                    'numm__Piece__r.numm__Role_du_Tiers__r.numm__ThirdParty__r.IdDataLake__c',
                    $this->conseillerIdArray
                ),
                $q->setInArrayCondition(
                    'numm__Piece__r.numm__Role_du_Tiers__r.numm__ThirdParty__r.ID_Datalake_Referent__c',
                    $this->conseillerIdArray
                )
                );
        }

        $q->setOrXCondition(
            'numm__Piece__r.numm__Role_du_Tiers__r.numm__ThirdParty__r.IdDataLake__c != null',
            'numm__Piece__r.numm__Role_du_Tiers__r.numm__ThirdParty__r.ID_Datalake_Referent__c != null'
        );

        $q->setCompareTextValueCondition(
            'numm__Piece__r.numm__Role_du_Tiers__r.numm__IdctrlAccounting__r.Name',
            '=',
            'CLI'
        );
        if ($this->hasPeriod) {
            $q->setDateCondition('numm__Piece__r.numm__BaselineDate__c', $this->start, '>=');
            $q->setDateCondition('numm__Piece__r.numm__BaselineDate__c', $this->end, '<=');
        }
        if (count($this->accounts) > 0) {
            $q->setInArrayCondition(
                'numm__IdAccountingCode__r.Name',
                $this->accounts
            );
        } else {
            $q->setCompareTextValueCondition('numm__IdAccountingCode__r.Name', 'LIKE', '7%');
        }

		$q->setCompareTextValueCondition('numm__Tech_Entite__r.Name', '=', 'BM EST');

        $q->orderBy(["numm__Piece__r.numm__Role_du_Tiers__c", "numm__IdAccountingCode__c"]);
    

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

    
    public function parse () 
    {
        foreach($this->rawData as $invoice) {
            $conseillerId =
                $invoice->numm__Piece__r->numm__Role_du_Tiers__r->numm__ThirdParty__r->ID_Datalake_Referent__c ?:
                $invoice->numm__Piece__r->numm__Role_du_Tiers__r->numm__ThirdParty__r->IdDataLake__c;
            if(!isset($this->parsedData[$conseillerId])) $this->parsedData[$conseillerId] = 0;
            $this->parsedData[$conseillerId] += $invoice->numm__Tech_SensMontant__c == 'D' ? -$invoice->numm__Amount__c : $invoice->numm__Amount__c;
        }
    }


    public function getResult()
    {
        $this->fetchData();
        $this->parse();
        return $this->parsedData;
    }
}