<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

/**
 * @OA\Schema(
 *  schema="StatisticRenouvellement",
 *  type="object",
 *  @OA\Property(
 *      property="CA_RENOUVELLEMENT",
 *          @OA\Property(
 *              property="ID_DATALAKE_DU_CONSEILLER",
 *              type="float",
 *              example=123.01
 *      )
 *  )
 * )
 */
class CARenouvellement extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_RENOUVELLEMENT';
    protected array $accounts = [706020, 706021];
}