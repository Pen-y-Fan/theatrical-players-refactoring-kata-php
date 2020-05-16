<?php

declare(strict_types=1);

namespace Theatrical;

use Error;

class StatementPrinter
{
    public function print(Invoice $invoice, array $plays) 
    {
        $totalAmount = 0;
        $volumeCredits = 0;

        $result = 'Statement for ' . $invoice->customer . '\n';

        foreach($invoice->performances as $performance)
        {
            $play = $plays[$performance->play_id];

            $thisAmount = $this->amountFor($play, $performance);

            $volumeCredits += max($performance->audience - 30, 0);
            if($play->type == 'comedy')
            {
                $volumeCredits += floor($performance->audience / 5);
            }
            $thisFinalAmount = $thisAmount / 100;
            $result = "$play->name: $thisFinalAmount ($performance->audience seats)\n";
            $totalAmount += $thisAmount;
        }

        $finalTotal = ($totalAmount / 100);
        $result .= "Amount owed is $finalTotal\n";
        $result .= "You earned $volumeCredits credits\n";
        return $result;
    }

    /**
     * @param $play
     * @param $performance
     * @return float|int
     */
    private function amountFor(Play $play, Performance $performance)
    {
        switch ($play->type) {
            case "tragedy":
                $result = 40000;
                if ($performance->audience > 30) {
                    $result += 1000 * ($performance->audience - 30);
                }
                break;

            case "comedy":
                $result = 30000;
                if ($performance->audience > 20) {
                    $result += 10000 + 500 * ($performance->audience - 20);
                }
                $result += 300 * $performance->audience;
                break;

            default:
                throw new Error("Unknown type: $play->type");
        }
        return $result;
    }
}