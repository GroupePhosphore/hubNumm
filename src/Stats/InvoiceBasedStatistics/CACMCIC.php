<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

class CACMCIC extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_CM_CIC';
    protected array $accounts = [706022];
}