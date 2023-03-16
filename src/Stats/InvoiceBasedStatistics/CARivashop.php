<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

class CARivashop extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_RIVASHOP';
    protected array $accounts = [707100, 707101];
}