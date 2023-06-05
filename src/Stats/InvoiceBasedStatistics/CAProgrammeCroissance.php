<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

/**
 * @OA\Schema(
 *  schema="StatisticProgrammeCroissance",
 *  type="object",
 *  @OA\Property(
 *      property="CA_PROGRAMME_CROISSANCE",
 *          @OA\Property(
 *              property="ID_DATALAKE_DU_CONSEILLER",
 *              type="float",
 *              example=123.01
 *      )
 *  )
 * )
 */
class CAProgrammeCroissance extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_PROGRAMME_CROISSANCE';
    protected array $accounts = [706013];
}