<?php


namespace App\Service\Complat\Request;


interface ComplatQuery
{
    public function getMethod(): string ;
    public function getEndpoint(): string;
}