<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty;

class CACMCICWithoutIdentifiedThirdParty extends AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty
{
    protected string $slug = 'CA_CM_CIC';
    protected array $accounts = [706022];
}