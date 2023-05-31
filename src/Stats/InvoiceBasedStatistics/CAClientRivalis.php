<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;
/**
 * @OA\Schema(
 *  schema="StatisticClientRivalis",
 *          @OA\Property(
 *              property="ID_DATALAKE_DU_CONSEILLER",
 *              type="float",
 *              example=12.2
 *  )
 * )
 */
class CAClientRivalis extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_CLIENT_RIVALIS';
    protected array $accounts = [706024, 706023];
}