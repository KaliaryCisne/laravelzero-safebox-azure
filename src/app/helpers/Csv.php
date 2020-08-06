<?php


namespace App\helpers;


use http\Exception\RuntimeException;
use Illuminate\Support\Facades\File;

class Csv
{
    public static function create($data, $pathToFile)
    {
        try {
            $data = json_decode($data, true);
            //cabeçalho
            $headers = array_keys($data[1]);
            $headers = implode(",", $headers) . PHP_EOL;
            File::put($pathToFile, $headers);

            //corpo
            $count = 0;
            $rows = count($data);
            foreach ($data as $row) {
                $count += 1;
                $str = implode(",", $row) . PHP_EOL;
                File::append($pathToFile, $str);
            }
            return $count;
        } catch (\Exception $e) {
            echo "error: " . $e . "\n\n";
            throw new RuntimeException();
        }
    }
}
