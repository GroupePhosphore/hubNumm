<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatistic;

/**
 * @OA\Schema(
 *  schema="StatisticLicenceDirecte",
 *  type="object",
 *  @OA\Property(
 *      property="CA_LICENCE_DIRECTE",
 *          @OA\Property(
 *              property="ID_DATALAKE_DU_CONSEILLER",
 *              type="float",
 *              example=123.01
 *      )
 *  )
 * )
 */
class CALicenceDirecte extends AbstractInvoiceBasedStatistic
{
    protected string $slug = 'CA_LICENCE_DIRECTE';
    protected array $accounts = [706011];
}
