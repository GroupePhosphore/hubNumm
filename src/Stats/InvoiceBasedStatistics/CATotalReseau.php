<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

/**
 * @OA\Schema(
 *  schema="StatisticTotalReseau",
 *  type="object",
 *  @OA\Property(
 *      property="CA_TOTAL_RESEAU",
 *          @OA\Property(
 *              property="ID_DATALAKE_DU_CONSEILLER",
 *              type="float",
 *              example=123.01
 *      )
 *  )
 * )
 */
class CATotalReseau extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_TOTAL_RESEAU';
    protected array $accounts = [
        706009,
        706010,
        706011,
        706012,
        706013,
        706014,
        706015,
        706020,
        706021,
        706022,
        706023,
        706024,
        707100,
        707101,
    ];
}