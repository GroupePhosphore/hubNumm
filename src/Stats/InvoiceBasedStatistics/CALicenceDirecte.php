<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

class CALienceDirecte extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_LICENCE_DIRECTE';
    protected array $accounts = [707100, 707101];
}