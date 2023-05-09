<?php

namespace App\Stats\AccountBasedStatistics;

use App\Stats\AccountBasedStatistics\AbstractAccountBasedStatistic;

class SocialExpensesStatistic extends AbstractAccountBasedStatistic
{
    protected string $type = self::TYPE_EXPENSE;
    protected string $slug = "social_expenses";
    protected array $accountsRanges = ['64'];
}
