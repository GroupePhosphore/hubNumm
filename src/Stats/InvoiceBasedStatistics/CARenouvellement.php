<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

class CARenouvellement extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_RENOUVELLEMENT';
    protected array $accounts = [706020, 706021];
}