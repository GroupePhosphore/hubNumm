<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\External\Salesforce\Utils\QueryUtils;
use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

abstract class AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty extends AbstractInvoiceBasedStatistic
{
    protected function firstCall()
    {
        $q = new QueryUtils();

        $q->addField('numm__Tech_Entite__r.Name');
        $q->addField('numm__Piece__r.numm__Role_du_Tiers__r.numm__ThirdParty__r.Name');
        $q->addField('numm__Piece__r.numm__Role_du_Tiers__r.numm__ThirdParty__r.IdDataLake__c');
        $q->addField('numm__IdAccountingCode__r.Name');
        $q->addField('numm__Amount__c');
        $q->addField('numm__Tech_SensMontant__c');
        
        $q->setTable('numm__VoucherTransaction__c');

		$q->setNullCondition('numm__Piece__r.numm__Role_du_Tiers__r.numm__ThirdParty__r.IdDataLake__c');
		$q->setNullCondition('numm__Piece__r.numm__Role_du_Tiers__r.numm__ThirdParty__r.ID_Datalake_Referent__c');

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

    public function parse () 
    {
        foreach($this->rawData as $invoice) {
            $conseillerName =
                $invoice->numm__Piece__r->numm__Role_du_Tiers__r->numm__ThirdParty__r->Name;
            if(!isset($this->parsedData[$conseillerName])) $this->parsedData[$conseillerName] = 0;
            $this->parsedData[$conseillerName] += $invoice->numm__Tech_SensMontant__c == 'D' ? -$invoice->numm__Amount__c : $invoice->numm__Amount__c;
        }
    }
}
