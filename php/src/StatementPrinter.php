<?php

declare(strict_types=1);

namespace Theatrical;

use NumberFormatter;

class StatementPrinter
{
    public function print(Invoice $invoice, array $plays): string
    {
        return $this->renderPlainText((new CreateStatementData())->createStatementData($invoice, $plays));
    }

    public function renderPlainText($data): string
    {
        $result = "Statement for {$data->customer}" . PHP_EOL;
        foreach ($data->performances as $performance) {
            $result .= "  {$performance->play->name}: {$this->usd($performance->amount)}";
            $result .= " ({$performance->audience} seats)" . PHP_EOL;
        }
        $result .= "Amount owed is {$this->usd($data->totalAmount)}" . PHP_EOL;
        $result .= "You earned {$data->totalVolumeCredits} credits" . PHP_EOL;
        return $result;
    }

    private function usd(float $value): string
    {
        return (new NumberFormatter('en_US', NumberFormatter::CURRENCY))
            ->formatCurrency($value / 100, 'USD');
    }
}
