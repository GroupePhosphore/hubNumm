<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

/**
 * @OA\Schema(
 *  schema="StatisticRivashop",
 *  type="object",
 *  @OA\Property(
 *      property="CA_RIVASHOP",
 *          @OA\Property(
 *              property="ID_DATALAKE_DU_CONSEILLER",
 *              type="float",
 *              example=123.01
 *      )
 *  )
 * )
 */
class CARivashop extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_RIVASHOP';
    protected array $accounts = [707100, 707101];
}