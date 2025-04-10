<?php

namespace App\Console\Commands;

use App\Models\Credit;
use DateTime;
use Illuminate\Console\Command;
use Serganbus\Money\Credits\CreditParams;

class AddNewCredit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-new-credit {sum} {percent} {months} {started_at}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sum = $this->argument('sum');
        $percent = $this->argument('percent');
        $months = $this->argument('months');
        $started_at = $this->argument('started_at');

        Credit::query()->create([
            'sum' => $sum,
            'percent' => $percent,
            'months' => $months,
            'started_at' => $started_at,
        ]);
    }
}
