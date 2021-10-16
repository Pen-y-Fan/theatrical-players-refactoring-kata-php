<?php

declare(strict_types=1);

namespace Theatrical;

final class StatementPrinter
{
    use UsdTrait;

    public function print(Invoice $invoice, array $plays): string
    {
        return $this->renderPlainText((new CreateStatementData())->createStatementData($invoice, $plays));
    }

    public function renderPlainText(CreateStatementData $data): string
    {
        $result = sprintf('Statement for %s', $data->customer) . PHP_EOL;
        array_map(function ($performance) use (&$result): void {
            $result .= sprintf('  %s: %s', $performance->play->name, $this->usd($performance->amount));
            $result .= sprintf(' (%s seats)', $performance->audience) . PHP_EOL;
        }, $data->performances);
        $result .= sprintf('Amount owed is %s', $this->usd($data->totalAmount)) . PHP_EOL;
        $result .= sprintf('You earned %s credits', $data->totalVolumeCredits) . PHP_EOL;
        return $result;
    }
}
