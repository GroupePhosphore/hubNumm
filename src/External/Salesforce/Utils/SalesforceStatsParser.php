<?php

namespace App\External\Salesforce\Utils;

class SalesforceStatsParser
{
    const STAT_CA_TOTAL_RESEAU = 'CA_TOTAL_RESEAU';
    const STAT_CA_TOTAL_TPE = 'CA_TOTAL_TPE';
    const STAT_CA_REDEVANCE = 'CA_REDEVANCE' ;
    const STAT_CA_COMNAT = 'CA_COMNAT';
    const STAT_CA_RENOUVELLEMENT = 'CA_RENOUVELLEMENT';
    const STAT_CA_RIVASHOP = 'CA_RIVASHOP';
    const STAT_CA_LICENCE_DIRECTE = 'CA_LICENCE_DIRECTE';
    const STAT_CA_PROGRAMME_CROISSANCE = 'CA_PROGRAMME_CROISSANCE';
    const STAT_CA_CLIENT_RIVALIS = 'CA_CLIENT_RIVALIS';
    const STAT_CA_CM_CIC = 'CA_CM_CIC';

    const STAT_ALL=[
        self::STAT_CA_TOTAL_RESEAU,
        self::STAT_CA_TOTAL_TPE,
        self::STAT_CA_REDEVANCE,
        self::STAT_CA_COMNAT,
        self::STAT_CA_RENOUVELLEMENT,
        self::STAT_CA_RIVASHOP,
        self::STAT_CA_LICENCE_DIRECTE,
        self::STAT_CA_PROGRAMME_CROISSANCE,
        self::STAT_CA_CLIENT_RIVALIS,
        self::STAT_CA_CM_CIC,
    ];

    const ACCOUNTS_CA_TOTAL_RESEAU = [];
    const ACCOUNTS_CA_TOTAL_TPE = [706022, 706024, 706023];
    const ACCOUNTS_CA_REDEVANCE = [706009, 706010, 706014, 706015];
    const ACCOUNTS_CA_COMNAT = [ 706012 ];
    const ACCOUNTS_CA_RENOUVELLEMENT = [706020, 706021];
    const ACCOUNTS_CA_RIVASHOP = [707100, 707101];
    const ACCOUNTS_CA_LICENCE_DIRECTE = [706011];
    const ACCOUNTS_CA_PROGRAMME_CROISSANCE = [706013];
    const ACCOUNTS_CA_CLIENT_RIVALIS = [706024, 706023];
    const ACCOUNTS_CA_CM_CIC = [706022];

    const STAT_MAPPING = [
        self::STAT_CA_TOTAL_RESEAU => self::ACCOUNTS_CA_TOTAL_RESEAU,
        self::STAT_CA_TOTAL_TPE => self::ACCOUNTS_CA_TOTAL_TPE,
        self::STAT_CA_REDEVANCE => self::ACCOUNTS_CA_REDEVANCE,
        self::STAT_CA_COMNAT => self::ACCOUNTS_CA_COMNAT,
        self::STAT_CA_RENOUVELLEMENT => self::ACCOUNTS_CA_RENOUVELLEMENT,
        self::STAT_CA_RIVASHOP => self::ACCOUNTS_CA_RIVASHOP,
        self::STAT_CA_LICENCE_DIRECTE => self::ACCOUNTS_CA_LICENCE_DIRECTE,
        self::STAT_CA_PROGRAMME_CROISSANCE => self::ACCOUNTS_CA_PROGRAMME_CROISSANCE,
        self::STAT_CA_CLIENT_RIVALIS => self::ACCOUNTS_CA_CLIENT_RIVALIS,
        self::STAT_CA_CM_CIC => self::ACCOUNTS_CA_CM_CIC,
    ];

    private ?array $selectedStats = null;

    public function setStatsToFetch(array $statsId)
    {
        return $this->selectedStats = $statsId;
    }

    public function getSelectedAccounts(): array
    {
        $accounts = [];
        foreach ($this->selectedStats as $stat) {
            $accounts = array_merge($accounts, self::STAT_MAPPING[$stat]);
        }
        return $accounts;
    }

    public function groupInvoicesByConseillerAndAccount(array $invoices): array
    {
        $results = [];

        foreach ($invoices as $invoice) {
            $conseillerId =
                $invoice->numm__Piece__r->numm__Role_du_Tiers__r->numm__ThirdParty__r->ID_Datalake_Referent__c ?:
                $invoice->numm__Piece__r->numm__Role_du_Tiers__r->numm__ThirdParty__r->IdDataLake__c;
            $results[
                    $conseillerId
                ][
                    $invoice->numm__IdAccountingCode__r->Name
                ]['invoices'][] = $invoice;
        }

        return $results;
    }

    public function makeSumByAccount(array $stats): array
    {
        foreach ($stats as $conseillerId => $conseillerData) {
            foreach ($conseillerData as $key => $value) {
                $result = array_reduce($value['invoices'], function ($acc, $invoice) {
                    return $invoice->numm__Tech_SensMontant__c == 'C' ?
                        $acc += $invoice->numm__Amount__c :
                        $acc -= $invoice->numm__Amount__c
                        ;
                      }, 0);
                $stats[$conseillerId]['totaux'][$key] = $result;
            }
        }
        return $stats;
    }

    public function agglomerateStatByConseiller(array $stats): array
    {
        if (in_array(self::STAT_CA_TOTAL_RESEAU, $this->selectedStats)) {
            $this->selectedStats = self::STAT_ALL;
        }
        $agglomeratedStats = [];
        foreach ($stats as $conseillerId => $conseillerData) {
            if (!isset($agglomeratedStats[$conseillerId])) {
                $agglomeratedStats[$conseillerId] = ['total' => 0];
            }

            foreach ($this->selectedStats as $stat) {
                $agglomeratedStats[$conseillerId][$stat] = 0;
                $mapping = self::STAT_MAPPING[$stat];
                foreach ($mapping as $account) {
                    $agglomeratedStats[$conseillerId][$stat] += (float)($conseillerData['totaux'][(int)$account] ?? 0) ;
                    $agglomeratedStats[$conseillerId]['total'] += (float)($conseillerData['totaux'][(int)$account] ?? 0) ;
                }

            }

            if (in_array(self::STAT_CA_TOTAL_RESEAU, $this->selectedStats)) {
                $agglomeratedStats[$conseillerId][self::STAT_CA_TOTAL_RESEAU] = (
                    $agglomeratedStats[$conseillerId]['total']
                    - $agglomeratedStats[$conseillerId][self::STAT_CA_TOTAL_TPE]
                );
            }
        }

        return $agglomeratedStats;
    }

    public function formatStatsFromInvoices($invoices): array
    {
        $stats = $this->groupInvoicesByConseillerAndAccount($invoices);

        $stats = $this->makeSumByAccount($stats);

        $stats = $this->agglomerateStatByConseiller($stats);

        return $stats;
    }
}
