<?php

declare(strict_types=1);

namespace Theatrical;

class StatementPrinter
{
    use UsdTrait;

    public function print(Invoice $invoice, array $plays): string
    {
        return $this->renderPlainText((new CreateStatementData())->createStatementData($invoice, $plays));
    }

    public function renderPlainText($data): string
    {
        $result = "Statement for {$data->customer}" . PHP_EOL;
        array_map(function ($performance) use (&$result): void {
            $result .= "  {$performance->play->name}: {$this->usd($performance->amount)}";
            $result .= " ({$performance->audience} seats)" . PHP_EOL;
        }, $data->performances);
        $result .= "Amount owed is {$this->usd($data->totalAmount)}" . PHP_EOL;
        $result .= "You earned {$data->totalVolumeCredits} credits" . PHP_EOL;
        return $result;
    }
}
