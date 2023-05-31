<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

/**
 * @OA\Schema(
 *  schema="StatisticRedevance",
 *  type="object",
 *  @OA\Property(
 *      property="CA_REDEVANCE",
 *          @OA\Property(
 *              property="ID_DATALAKE_DU_CONSEILLER",
 *              type="float",
 *              example=123.01
 *      )
 *  )
 * )
 */
class CARedevance extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_REDEVANCE';
    protected array $accounts = [ 706009, 706010, 706014, 706015 ];
}