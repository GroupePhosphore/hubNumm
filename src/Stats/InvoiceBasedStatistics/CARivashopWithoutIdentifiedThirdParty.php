<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty;

class CARivashopWithoutIdentifiedThirdParty extends AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty
{
    protected string $slug = 'CA_RIVASHOP';
    protected array $accounts = [707100, 707101];
}