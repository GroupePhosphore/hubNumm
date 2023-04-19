<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty;

class CATotalReseauWithoutIdentifiedThirdParty extends AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty
{
    protected string $slug = 'CA_TOTAL_RESEAU';
    protected array $accounts = [
        706009,
        706010,
        706011,
        706012,
        706013,
        706014,
        706015,
        706020,
        706021,
        706022,
        706023,
        706024,
        707100,
        707101,
    ];
}