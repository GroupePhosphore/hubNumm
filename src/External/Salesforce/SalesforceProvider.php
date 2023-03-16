<?php

namespace App\External\Salesforce;

use App\External\Salesforce\Utils\QueryUtils;
use Exception;
use GuzzleHttp\{Client, RequestOptions};
use Psr\Log\LoggerInterface;


class SalesforceProvider
{
    public function __construct(
        private CustomClient $nummClient,
        private LoggerInterface $logger
    )
    {}
    /**
     * Make and execute the query to fetch all invoices from NUMM,
     * in a class 7 (Products) account or in a given list,
     * based on the array of DataLake Ids given.
     * If no ID array is given, every invoice for a conseiller will be returned
     *
     * @param \DateTime|null $start
     * @param \DateTime|null $end
     * @param array|null $conseillerIdArray
     * @param array|null $accountArray
     * @return Object
     */
    public function getInvoices(
        ?\DateTime $start = null,
        ?\DateTime $end = null,
        ?array $conseillerIdArray = null,
        ?array $accountArray = null
        ): Object
    {
        $q = new QueryUtils();

        $q->addField('numm__Piece__r.numm__Role_du_Tiers__c');
        $q->addField('numm__Piece__r.numm__Role_du_Tiers__r.Name');
        $q->addField('numm__Piece__r.numm__Role_du_Tiers__r.numm__Code_Tiers_externe__c');
        $q->addField('numm__Piece__r.numm__Role_du_Tiers__r.numm__IdctrlAccounting__r.Name');
        $q->addField('numm__Piece__r.numm__Role_du_Tiers__r.numm__ThirdParty__r.IdDataLake__c');
        $q->addField('numm__Piece__r.numm__Role_du_Tiers__r.numm__ThirdParty__r.ID_Datalake_Referent__c');
        $q->addField('numm__Piece__r.Name');
        $q->addField('numm__Piece__r.numm__AccountingDate__c');
        $q->addField('numm__Piece__r.numm__BaselineDate__c');
        $q->addField('numm__IdAccountingCode__r.Name');
        $q->addField('numm__IdAccountingCode__r.numm__Description__c');
        $q->addField('numm__Amount__c');
        $q->addField('numm__Tech_SensMontant__c');
        
        $q->setTable('numm__VoucherTransaction__c');

        if ($conseillerIdArray && count($conseillerIdArray) > 1) {
            $q->setOrXCondition(
                $q->setInArrayCondition(
                    'numm__Piece__r.numm__Role_du_Tiers__r.numm__ThirdParty__r.IdDataLake__c',
                    $conseillerIdArray
                ),
                $q->setInArrayCondition(
                    'numm__Piece__r.numm__Role_du_Tiers__r.numm__ThirdParty__r.ID_Datalake_Referent__c',
                    $conseillerIdArray
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
        if ($start) {
            $q->setDateCondition('numm__Piece__r.numm__BaselineDate__c', $start, '>=');
        }
        if ($end) {
            $q->setDateCondition('numm__Piece__r.numm__BaselineDate__c', $end, '<=');
        }
        if ($accountArray) {
            $q->setInArrayCondition(
                'numm__IdAccountingCode__r.Name',
                $accountArray
            );
        } else {
            $q->setCompareTextValueCondition('numm__IdAccountingCode__r.Name', 'LIKE', '7%');
        }

        $q->orderBy(["numm__Piece__r.numm__Role_du_Tiers__c", "numm__IdAccountingCode__c"]);
    

        return $this->sql($q->getQuery());
    }

    /**
     * Execute the given SOQL query as a string, via GET call
     *
     * @param string $query
     * @return Object
     */
    public function sql(string $query): Object
    {
        return $this->nummClient->makeRequest('GET', '/services/data/v57.0/query/?q=' . urlencode($query));
    }

    /**
     * Execute an API call to the given URI
     *
     * @param string $nextPageURL
     * @return Object
     */
    public function getEagerResult(string $nextPageURL): Object
    {
        return $this->nummClient->makeRequest('GET', $nextPageURL);
    }
}
