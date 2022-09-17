<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Current;

class GetCurrency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:hourly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get turkey lira currency every specific time';

    /**
     * Execute the console command.
     *
     * @return double
     */
    public function handle()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.apilayer.com/exchangerates_data/latest?symbols=TRY&base=USD",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: text/plain",
                "apikey: Evh72W0yXkGYbmzz5QeWu1QcVP591qxF"
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));

        $response = curl_exec($curl);

        $data = json_decode($response);


        Currency::create([
            'TRY_currency' => $data->rates->TRY
        ]);


        curl_close($curl);
    }
}
