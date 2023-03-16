<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

class CARedevance extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_REDEVANCE';
    protected array $accounts = [ 706009, 706010, 706014, 706015 ];
}