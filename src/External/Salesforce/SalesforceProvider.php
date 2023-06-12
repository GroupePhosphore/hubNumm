<?php

namespace App\External\Salesforce;

use App\External\Salesforce\Utils\QueryUtils;
use DateTime;
use Doctrine\DBAL\Query;
use Exception;
use GuzzleHttp\{Client, RequestOptions};
use Psr\Log\LoggerInterface;

class SalesforceProvider
{
    public function __construct(
        private CustomClient $nummClient,
        private LoggerInterface $logger
    ) {
    }

    public function getAllConseillers()
    {
        $q = new QueryUtils();

        $q->setTable('numm__ThirdParty__c');

        $q->addField('Id');
        $q->addField('Name');
        $q->addField('IdDataLake__c');
        $q->addField('Id_Datalake_Referent__c');
        $q->addField('Cat_gorie_de_tiers__c');

        $q->setInArrayCondition('Cat_gorie_de_tiers__c', ['Conseiller', 'Conseiller Distributeur']);
        return $this->sql($q->getQuery());
    }

    public function getConseillerByIdDatalake(string $id)
    {
        $q = new QueryUtils();

        $q->setTable('numm__ThirdParty__c');

        $q->addField('Id');
        $q->addField('Name');
        $q->addField('IdDataLake__c');
        $q->addField('Id_Datalake_Referent__c');
        $q->addField('Cat_gorie_de_tiers__c');

        $sq = new QueryUtils();
        $sq->setTable('numm__IdthirdParty__r');
        $sq->addField('Id');
        $sq->addField('Name');
        $sq->addField('numm__Code_Tiers_externe__c');
        $sq->addField('numm__Type_de_collectif__c');
        $sq->addField('numm__Entite__r.Name');
        $sq->addField('numm__Entite__r.Id');
        $sq = $sq->getQuery();

        $q->addField('(' . $sq . ')');

        $q->setInArrayCondition('Cat_gorie_de_tiers__c', ['Conseiller', 'Conseiller Distributeur']);
        $q->setStringCondition("IdDataLake__c = '" . $id . "'");

        $q->setLimit(1, 0);

        return $this->sql($q->getQuery());
    }

    public function createConseiller(string $datalakeId, array $updateFields)
    {
        $data = array_merge($updateFields, ['id' => $datalakeId]);

        return $this->nummClient->makeRequest('POST', '/services/apexrest/nummapi/v1/thirdParty/create', $data);

    }

    public function updateConseiller(string $datalakeId, array $updateFields)
    {
        $data = array_merge($updateFields, ['id' => $datalakeId]);

        return $this->nummClient->makeRequest('PATCH', '/services/apexrest/nummapi/v1/thirdParty/update', $data);

    }


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
    ): Object {
        $q = new QueryUtils();

        $q->addField('numm__Piece__r.numm__Role_du_Tiers__r.numm__ThirdParty__r.IdDataLake__c');
        $q->addField('numm__Piece__r.numm__Role_du_Tiers__r.numm__ThirdParty__r.ID_Datalake_Referent__c');
        $q->addField('numm__IdAccountingCode__r.Name');
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


    public function updateInvoiceBaseline(
        string $initialBaseLine,
        string $finalBaseLine,
        string $journalName,
        string $entityName,
        string $establishementName,
        \DateTime $date
    ): Object {
        return $this->nummClient->makeRequest('POST', '/services/apexrest/nummapi/v1/voucher/updateNumber', [
            'initialNumber' => $initialBaseLine,
            'finalNumber' => $finalBaseLine,
            'initialDate' => $date->format('Y-m-d'),
            'initialEntity' => $entityName,
            'initialEstablishment' => $establishementName,
            'initialJournal' => $journalName
        ]);
    }

    public function findLineId(array $line)
    {
        try {
            $date = \DateTime::createFromFormat('d/m/Y', $line['date']);

            $line['date'] = $date->format('Y-m-d');
            $result = $this->nummClient->makeRequest('POST', '/services/apexrest/nummapi/v1/voucher/switchEntity', $line);
            return $result;
        } catch (\Exception $e) {
            $this->logger->error('Import error - SFP1<br> ----------------------------------<br>' .$e->getMessage() . '<br> ----------------------------------<br>' .json_encode($line));
            return null;
        }
    }
}
