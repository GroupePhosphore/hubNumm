<?php

namespace App\Stats\AccountBasedStatistics;

use App\Stats\AccountBasedStatistics\AbstractAccountBasedStatistic;

class OutsourcingStatistic extends AbstractAccountBasedStatistic
{
    protected string $slug = "outsourcing";
    protected array $accountsRanges = ['611814'];
}
