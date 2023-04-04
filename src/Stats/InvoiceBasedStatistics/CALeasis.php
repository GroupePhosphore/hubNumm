<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

class CALeasis extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_LEASIS';
    protected array $accounts = [ 706100, 707100, 707200 ];
}
