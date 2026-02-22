<?php
namespace App\koketsu\Config;

class Mail{
    public static function get(){
        return [
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'username'=> 'thurfelix10@gmail.com',
            'password'=> 'juzm wwmz ivwg apzp',
            'encryption'=> 'tls',
            'from_address'=> 'noreply@koketsu.com',
            'from_name'=> 'koketsu',
            
        ];
    }
}