<?php

namespace App\Stats\AccountBasedStatistics;

use App\Stats\AccountBasedStatistics\AbstractAccountBasedStatistic;

/**
 * @OA\Schema(
 *  schema="Outsourcing",
 *  type="object",
 *  @OA\Property(
 *      property="outsourcing",
 *      type="object",
*       @OA\Property(
*               property="Nom du Service",
*               type="object",
*               @OA\Property(
*                       property="Nom du BU",
*                       type="object",
*                       @OA\Property(
*                           property="detail",
*                           type="object",
*                           @OA\Property(
*                               property="numéro du compte",
*                               type="float",
*                               example=62.23
*                           )
*                       ),
*                       @OA\Property(
*                           property="total",
*                           type="float",
*                           example=123.57
*                       ),
*                       @OA\Property(
*                           property="type",
*                           type="string",
*                           example="charge"
*                       )
*               ),
*           )
*               
*           )
 *  )
 * )
 */
class OutsourcingStatistic extends AbstractAccountBasedStatistic
{
    protected string $type = self::TYPE_EXPENSE;
    protected string $slug = "outsourcing";
    protected array $accountsRanges = ['611814'];
}
