<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

class CAProgrammeCroissance extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_PROGRAMME_CROISSANCE';
    protected array $accounts = [706013];
}