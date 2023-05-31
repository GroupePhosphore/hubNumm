<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

/**
 * @OA\Schema(
 *  schema="StatisticLeasis",
 *  type="object",
 *  @OA\Property(
 *      property="CA_LEASIS",
 *          @OA\Property(
 *              property="ID_DATALAKE_DU_CONSEILLER",
 *              type="float",
 *              example=123.01
 *      )
 *  )
 * )
 */
class CALeasis extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_LEASIS';
    protected array $accounts = [ 706100, 707100, 707200 ];
}
