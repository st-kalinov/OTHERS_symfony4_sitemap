<?php
/**
 * Created by PhpStorm.
 * User: stoyan.kalinov
 * Date: 5.10.2018 г.
 * Time: 16:40
 */

namespace App\Service\ServiceInterface;


interface RouteSortInteface
{
    public function getRoutesForSitemap(): array;
    public function getAllStaticRoutesForSitemap(): array;
}