<?php

namespace App\Helpers;

class UserMessageDataExtractor
{
    protected static $patterns = [
        // Добавлено проверочное условие (?!3P) в начале выражения для поиска Bitcoin кошелька, чтобы избежать нахождения Waves кошельков
        ["name" => "BTC", "pattern" => "(?!3P)(bc1|[13])[a-zA-HJ-NP-Z0-9]{25,39}", "type" => "crypto_wallet"],
        ["name" => "BCH", "pattern" => "((bitcoincash|bchreg|bchtest):)?(q|p)[a-z0-9]{41}", "type" => "crypto_wallet"],
        ["name" => "ETH", "pattern" => "0x([A-Fa-f0-9]{64})", "type" => "crypto_wallet"],
        ["name" => "XRP", "pattern" => "r[0-9a-zA-Z]{33}", "type" => "crypto_wallet"],
        ["name" => "XMR", "pattern" => "[48][a-zA-Z|\d]{94}([a-zA-Z|\d]{11})?", "type" => "crypto_wallet"],
        ["name" => "NEO", "pattern" => "A[0-9a-zA-Z]{33}", "type" => "crypto_wallet"],
        ["name" => "DOGE", "pattern" => "D{1}[5-9A-HJ-NP-U]{1}[1-9A-HJ-NP-Za-km-z]{32}", "type" => "crypto_wallet"],
        ["name" => "DASH", "pattern" => "X[1-9A-HJ-NP-Za-km-z]{33}", "type" => "crypto_wallet"],
        ["name" => "WAVES", "pattern" => "3P([A-HJ-NP-Za-km-z\d]{33})", "type" => "crypto_wallet"],
        ["name" => "XEC", "pattern" => "(ecash:)([qpzry9x8gf2tvdw0s3jn54khce6mua7l]{42})", "type" => "crypto_wallet"],
        ["name" => "ZEC", "pattern" => "t[a-zA-Z0-9]{34}", "type" => "crypto_wallet"],

        ["name" => "ELECTRON", "pattern" => "(4026|417500|4405|4508|4844|4913|4917)\d{12}", "type" => "debit_card"],
        ["name" => "MAESTRO", "pattern" => "(?:50|5[6-9]|6[0-9])\d{10,17}", "type" => "debit_card"],
        ["name" => "DANKORT", "pattern" => "(5019|4571)\d{12}", "type" => "debit_card"],
        ["name" => "CUP", "pattern" => "(62|81)\d{14,17}", "type" => "debit_card"],
        ["name" => "VISA", "pattern" => "4\d{12}(?:\d{3})?", "type" => "debit_card"],
        ["name" => "DINERS", "pattern" => "3(?:0[0-5]|[68]\d)\d{11}", "type" => "debit_card"],
        ["name" => "MC", "pattern" => "5[1-5]\d{14}", "type" => "debit_card"],
        ["name" => "AMEX", "pattern" => "3[47]\d{13}", "type" => "debit_card"],
        ["name" => "DISCOVER", "pattern" => "6(?:011|5[0-9]{2})\d{12}", "type" => "debit_card"],
        ["name" => "JCB", "pattern" => "(?:2131|1800|35\d{3})\d{11}", "type" => "debit_card"],
        ["name" => "MIR", "pattern" => "22\d{14}", "type" => "debit_card"],

        ["name" => "Email", "pattern" => "[\p{L}\p{N}\.\-\_]+@[\p{L}\p{N}\.\-\_]+\.[\p{L}\p{N}]{2,}", "type" => "personal_data"],
        ["name" => "Username", "pattern" => "@\w+", "type" => "personal_data"],
        ["name" => "URL", "pattern" => "(http|https):\/\/([\p{L}\p{N}\.\-\_]+(\.[\p{L}\p{N}\.\-\_]+)*[\p{L}\p{N}]{2,})(:\d+)?(\/[^\s]*)?", "type" => "custom_data"]
    ];

    protected static $compiledPatterns;

    public function __construct()
    {
        if (!isset(self::$compiledPatterns)) {
            self::$compiledPatterns = array_map(function ($pattern) {
                return '/(?<!\S)' . $pattern['pattern'] . '(?=\s|$)/iu';
            }, self::$patterns);
        }
    }

    public function parse(string $message): array
    {
        $matches = [];

        foreach (self::$patterns as $i => $pattern) {
            $regex = self::$compiledPatterns[$i];

            if (preg_match_all($regex, $message, $found)) {
                foreach ($found[0] as $match) {
                    $matches[] = [
                        'type' => $pattern['type'],
                        'name' => $pattern['name'],
                        'value' => $match,
                    ];
                }
            }
        }

        return $matches;
    }
}