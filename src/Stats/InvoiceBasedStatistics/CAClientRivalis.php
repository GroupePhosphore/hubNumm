<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

class CAClientRivalis extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_CLIENT_RIVALIS';
    protected array $accounts = [706024, 706023];
}