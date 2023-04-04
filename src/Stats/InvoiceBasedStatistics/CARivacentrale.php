<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

class CARivacentrale extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_RIVACENTRALE';
    protected array $accounts = [ 706017, 706018 ];
}
