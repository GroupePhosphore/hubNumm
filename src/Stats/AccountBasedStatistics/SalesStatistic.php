<?php

namespace App\Stats\AccountBasedStatistics;

use App\Stats\AccountBasedStatistics\AbstractAccountBasedStatistic;

/**
 * @OA\Schema(
 *  schema="Sales",
 *  type="object",
 *  @OA\Property(
 *      property="sales",
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
*                           example="produi"
*                       )
*               ),
*           )
*               
*           )
 *  )
 * )
 */
class SalesStatistic extends AbstractAccountBasedStatistic
{
    protected string $type = self::TYPE_PRODUCT;
    protected string $slug = "sales";
    protected array $accountsRanges = ['7'];
}
