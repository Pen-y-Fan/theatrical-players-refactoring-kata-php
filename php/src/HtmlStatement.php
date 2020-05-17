<?php

declare(strict_types=1);

namespace Theatrical;


class HtmlStatement
{
    use UsdTrait;

    public function print(Invoice $invoice, array $plays): string
    {
        return $this->renderHtml((new CreateStatementData())->createStatementData($invoice, $plays));
    }

    public function renderHtml($data): string
    {
        $result = "<h1>Statement for {$data->customer}</h1>\n";
        $result .= "<table>\n";
        $result .= "<tr><th>play</th><th>seats</th><th>cost</th></tr>\n";
        array_map(function($performance) use (&$result) {
            $result .= "<tr>\n  <td>{$performance->play->name}</td>\n";
            $result .= "  <td>{$performance->audience}</td>\n";
            $result .= "  <td>{$this->usd($performance->amount)}</td>\n</tr>\n";
        }, $data->performances);
        $result .= "</table>\n";
        $result .= "<p>Amount owed is <em>{$this->usd($data->totalAmount)}</em></p>\n";
        $result .= "<p>You earned <em>{$data->totalVolumeCredits}</em> credits</p>\n";
        return $result;
    }
}
