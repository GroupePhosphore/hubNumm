<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

/**
 * @OA\Schema(
 *  schema="StatisticRivacentrale",
 *  type="object",
 *  @OA\Property(
 *      property="CA_RIVACENTRALE",
 *          @OA\Property(
 *              property="ID_DATALAKE_DU_CONSEILLER",
 *              type="float",
 *              example=123.01
 *      )
 *  )
 * )
 */
class CARivacentrale extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_RIVACENTRALE';
    protected array $accounts = [ 706017, 706018 ];
}
