<?php
/**
 * Created by PhpStorm.
 * User: stoyan.kalinov
 * Date: 4.10.2018 г.
 * Time: 15:24
 */

namespace App\Service\ServiceInterface;


interface EncodePasswordInterface
{
    public function encodeAllPasswords();
    public function setEncodedPasswords();
}