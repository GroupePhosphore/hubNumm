<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

/**
 * @OA\Schema(
 *  schema="StatisticTotalTPE",
 *  type="object",
 *  @OA\Property(
 *      property="CA_TOTAL_TPE",
 *          @OA\Property(
 *              property="ID_DATALAKE_DU_CONSEILLER",
 *              type="float",
 *              example=123.01
 *      )
 *  )
 * )
 */
class CATotalTpe extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_TOTAL_TPE';
    protected array $accounts = [ 706022, 706024, 706023, 706013 ];
}
