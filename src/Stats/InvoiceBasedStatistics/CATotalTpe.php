<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

class CATotalTpe extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_TOTAL_TPE';
    protected array $accounts = [ 706022, 706024, 706023 ];
}