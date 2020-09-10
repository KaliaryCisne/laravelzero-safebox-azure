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
        try {
            $this->info("Iniciando execução do script!");

            $response = (new Cep())->getCEP("60190230/json");

            var_dump($response);

            $this->info("Script Finalizado!");
            return 0;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
