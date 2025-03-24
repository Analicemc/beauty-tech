<?php

class TransformationService
{
    public static function formatPhone(String $phone)
    {
        return "(".substr($phone,0,2).") ".substr($phone,2,-4)." - ".substr($phone,-4);
    }
}
