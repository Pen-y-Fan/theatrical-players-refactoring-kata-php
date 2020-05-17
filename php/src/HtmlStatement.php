<?php

declare(strict_types=1);

namespace Theatrical;

use NumberFormatter;

class HtmlStatement
{
    public function print(Invoice $invoice, array $plays): string
    {
        return $this->renderHtml((new CreateStatementData())->createStatementData($invoice, $plays));
    }

    public function renderHtml($data): string
    {
        $result = "<h1>Statement for {$data->customer}</h1>\n";
        $result .= "<table>\n";
        $result .= "<tr><th>play</th><th>seats</th><th>cost</th></tr>\n";
        foreach ($data->performances as $performance) {
                $result .= "<tr><td>{$performance->play->name}</td><td>{$performance->audience}</td>\n";
                $result .= "<td>{$this->usd($performance->amount)}</td></tr>\n";
            }
        $result .= "</table>\n";
        $result .= "<p>Amount owed is <em>{$data->totalAmount}</em></p>\n";
        $result .= "<p>You earned <em>{$data->totalVolumeCredits}</em> credits</p>\n";
        return $result;
    }

    private function usd(float $value): string
    {
        return (new NumberFormatter('en_US', NumberFormatter::CURRENCY))
            ->formatCurrency($value / 100, 'USD');
    }
}
