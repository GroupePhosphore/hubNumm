<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty;

class CALicenceDirecteWithoutIdentifiedThirdParty extends AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty
{
    protected string $slug = 'CA_LICENCE_DIRECTE';
    protected array $accounts = [706011];
}
