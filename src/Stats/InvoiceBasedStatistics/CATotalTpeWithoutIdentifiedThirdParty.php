<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty;

class CATotalTpeWithoutIdentifiedThirdParty extends AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty
{
    protected string $slug = 'CA_TOTAL_TPE';
    protected array $accounts = [ 706022, 706024, 706023 ];
}