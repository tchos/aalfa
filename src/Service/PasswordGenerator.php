<?php

namespace App\Service;

class PasswordGenerator
{
    public function generate(int $length = 10): string
    {
        $lowercase = 'abcdefghijkmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $numbers   = '23456789';
        $specials  = '@#$%&*!?';

        $password = '';

        // Au moins un caractère de chaque type
        $password .= $lowercase[random_int(0, strlen($lowercase)-1)];
        $password .= $uppercase[random_int(0, strlen($uppercase)-1)];
        $password .= $numbers[random_int(0, strlen($numbers)-1)];
        $password .= $specials[random_int(0, strlen($specials)-1)];

        $all = $lowercase.$uppercase.$numbers.$specials;

        while (strlen($password) < $length) {
            $password .= $all[random_int(0, strlen($all)-1)];
        }

        // Mélanger le mot de passe
        return str_shuffle($password);
    }
}