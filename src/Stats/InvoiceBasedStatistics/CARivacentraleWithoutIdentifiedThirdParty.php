<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty;

class CARivacentraleWithoutIdentifiedThirdParty extends AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty
{
    protected string $slug = 'CA_RIVACENTRALE';
    protected array $accounts = [ 706017, 706018 ];
}
