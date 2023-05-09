<?php

namespace App\Stats\AccountBasedStatistics;

use App\Stats\AccountBasedStatistics\AbstractAccountBasedStatistic;

class ExternalExpensesStatistic extends AbstractAccountBasedStatistic
{
    protected string $type = self::TYPE_EXPENSE;

    protected string $slug = "external_expenses";
    protected array $accountsRanges = ['60', '61', '62'];
}
