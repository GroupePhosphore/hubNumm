<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

/**
 * @OA\Schema(
 *  schema="StatisticComnat",
 *  type="object",
 *  @OA\Property(
 *      property="CA_COMNAT",
 *          @OA\Property(
 *              property="ID_DATALAKE_DU_CONSEILLER",
 *              type="float",
 *              example=123.01
 *      )
 *  )
 * )
 */
class CAComnat extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_COMNAT';
    protected array $accounts = [706012];
}