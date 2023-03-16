<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

class CAComnat extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_COMNAT';
    protected array $accounts = [706012];
}