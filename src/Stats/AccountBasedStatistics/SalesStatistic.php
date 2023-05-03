<?php

namespace App\Stats\AccountBasedStatistics;

use App\Stats\AccountBasedStatistics\AbstractAccountBasedStatistic;

class SalesStatistic extends AbstractAccountBasedStatistic
{
    protected string $slug = "sales";
    protected array $accountsRanges = ['7'];
}
