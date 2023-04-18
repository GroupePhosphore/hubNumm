<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

class CALicenceDirecte extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_LICENCE_DIRECTE';
    protected array $accounts = [706011];
}
