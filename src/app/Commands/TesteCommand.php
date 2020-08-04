<?php


namespace App\Commands;


use App\Services\Cep;
use LaravelZero\Framework\Commands\Command;

class TesteCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'get_address';

    public function handle()
    {
        $response = (new Cep())->getCEP("60190230/json");
        var_dump($response);
    }
}
