<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

/**
 * @OA\Schema(
 *  schema="StatisticCMCIC",
 *  type="object",
 *  @OA\Property(
 *      property="CA_CM_CIC",
 *          @OA\Property(
 *              property="ID_DATALAKE_DU_CONSEILLER",
 *              type="float",
 *              example=123.01
 *      )
 *  )
 * )
 */
class CACMCIC extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_CM_CIC';
    protected array $accounts = [706022];
}