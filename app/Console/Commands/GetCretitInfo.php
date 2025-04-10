<?php

namespace App\Console\Commands;

use App\Models\Credit;
use DateTime;
use Illuminate\Console\Command;
use Serganbus\Money\Credits\Calculator;
use Serganbus\Money\Credits\CreditParams;
use Serganbus\Money\Credits\RepaymentSchedule;

class GetCretitInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-cretit-info {id}';

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
        $credit = Credit::find($this->argument('id'));
//        $started_at = $credit->started_at->addMonths(1);
        $params = new CreditParams($credit->started_at, $credit->sum, $credit->percent, $credit->months, CreditParams::DURATION_MONTH);
//        $unexpectedPayments = [
//            new UnexpectedPayment(10000000, new DateTime('2019-12-15 00:00:00'), UnexpectedPayment::LESS_PAYMENT),
//            new UnexpectedPayment(3565411, new DateTime('2020-01-13 00:00:00'), UnexpectedPayment::LESS_LOAN_PERIOD),
//            new UnexpectedPayment(15000000, new DateTime('2020-02-29 00:00:00'), UnexpectedPayment::LESS_LOAN_PERIOD),
//        ];

        $calculator = new Calculator;

    // считаем график погашений с аннуитетными платежами
    // Чтобы были дифференцированные платежи, третьим параметром указываем Calculator::TYPE_TYPE_DIFFERENTIAL
        $tableData = [];

        /** @var RepaymentSchedule $schedule График платежей */
        $schedule = $calculator->calculate($params, [], Calculator::TYPE_ANNUITY);
        foreach ($schedule as $repayment) {
            /** @var DateTime $date Дата очередного платежного периода */
            $date = $repayment->getDate()->format('d.m.Y');

            /** @var int $payment Сумма очередного платежа */
            $payment = $repayment->getPayment();

            /** @var int $percents Проценты по очередному платежу */
            $percents = $repayment->getPercents();

            /** @var int $body Тело займа по очередному платежу */
            $body = $repayment->getBody();

            /** @var int $body Остаток по займу после платежа */
            $balance = $repayment->getBalance();

//            $tableData[] = [$date, round($payment/1000, 2), round($percents/1000, 2), round($body/1000, 2), round($balance/1000, 2)];
            $tableData[] = [$date, $payment, $percents, $body, $balance];
        }
        $this->table(
            ['Дата', 'Платеж', 'Проценты', 'Тело', 'Остаток'],
            $tableData
        );
        /** @var int $total Получить сумму всех платежей по займу */
        $total = $schedule->calculateTotalPayments();

        /** @var int $overpayment Получить сумму переплаты по займу */
        $overpayment = $schedule->calculateOverpayment();

        /** @var int $requestedSum Получить сумму займа */
        $requestedSum = $schedule->getCreditParams()->getRequestedSum();

        /** @var string psk Полная стоимость кредита в процентах годовых с округлением до 3х знаков */
        $psk = $schedule->calculateTotalCost();
    }
}
