<?php

header('Content-Type: application/json');

$methodType = $_SERVER['REQUEST_METHOD'];
if ($methodType === 'GET') {
    parse_str($_SERVER['QUERY_STRING'], $queries);

    // Validate if set and not empty
    if (!isset($queries['number']) || empty(trim($queries['number']))) {
        echo json_encode(array("message" => ['number' => 'Required']));
        http_response_code(422);
        exit();
    }

    $number = $queries['number'];
    // Validate if numeric
    if (!is_numeric($number) || strpos($number, '-')) {
        echo json_encode(array("message" => ['number' => 'Invalid number']));
        http_response_code(422);
        exit();
    }

    $word = "";
    $wholeNumber = $number;
    $decimalNumber = "0";

    // Check if have decimal point
    if (strpos($number, '.')) {
        $wholeNumber =  substr_replace($number, "", strpos($number, '.'));
        $decimalNumber =  substr($number, (strpos($number, ".")) + 1) ?? 0;
    }

    $word .=  numberToWord($wholeNumber) . ' DOLLARS';
    $word .= $decimalNumber ?  ' AND ' . numberToWord($decimalNumber) . ' CENTS' : "";

    echo json_encode(array("value" => $word));
} else {
    http_response_code(400);
    echo json_encode(array("message" => "$methodType method is not supported"));
    exit();
}


function singleDigit($number)
{
    $singleDigit = [
        '0' => '',
        '1' => 'ONE',
        '2' => 'TWO',
        '3' => 'THREE',
        '4' => 'FOUR',
        '5' => 'FIVE',
        '6' => 'SIX',
        '7' => 'SEVEN',
        '8' => 'EIGHT',
        '9' => 'NINE'
    ];

    return $singleDigit[$number];
}


function doubleDigit($number)
{
    $twoDigitWithZeroSuffix = [
        '2' => 'TWENTY',
        '3' => 'THIRTY',
        '4' => 'FORTY',
        '5' => 'FIFTY',
        '6' => 'SIXTY',
        '7' => 'SEVENTY',
        '8' => 'EIGHTY',
        '9' => 'NINETY'
    ];

    $word = '';

    switch ($number[0]) {
        case 0:
            $word = doubleDigitNumber($number[1]);
            break;
        case 1:
            switch ($number[1]) {
                case 0:
                    $word = "TEN";
                    break;
                case 1:
                    $word = "ELEVEN";
                    break;
                case 2:
                    $word = "TWELVE";
                    break;
                case 3:
                    $word = "THIRTEEN";
                    break;
                case 4:
                    $word = "FOURTEEN";
                    break;
                case 5:
                    $word = "FIFTEEN";
                    break;
                case 6:
                    $word = "SIXTEEN";
                    break;
                case 7:
                    $word = "SEVENTEEN";
                    break;
                case 8:
                    $word = "EIGHTEEN";
                    break;
                case 9:
                    $word = "NINETEEN";
                    break;
            }
            break;
        case 2:
            $word = $twoDigitWithZeroSuffix[2] . doubleDigitNumber($number[1]);
            break;
        case 3:
            $word = $twoDigitWithZeroSuffix[3] . doubleDigitNumber($number[1]);
            break;
        case 4:
            $word = $twoDigitWithZeroSuffix[4] . doubleDigitNumber($number[1]);
            break;
        case 5:
            $word = $twoDigitWithZeroSuffix[5] . doubleDigitNumber($number[1]);
            break;
        case 6:
            $word = $twoDigitWithZeroSuffix[6] . doubleDigitNumber($number[1]);
            break;
        case 7:
            $word = $twoDigitWithZeroSuffix[7] . doubleDigitNumber($number[1]);
            break;
        case 8:
            $word = $twoDigitWithZeroSuffix[8] . doubleDigitNumber($number[1]);
            break;
        case 9:
            $word = $twoDigitWithZeroSuffix[9] . doubleDigitNumber($number[1]);
            break;
    }
    return $word;
}

function doubleDigitNumber($number)
{
    $word = $number == 0 ? "" : "-" . singleDigit($number);;
    return $word;
}

function unitDigit($numberlen)
{
    switch ($numberlen) {
        case 3:
        case 6:
        case 9:
        case 12:
            $word = "HUNDRED";
            break;
        case 4:
        case 5:
            $word = "THOUSAND";
            break;
        case 7:
        case 8:
            $word = "MILLION";
            break;
        case 10:
        case 11:
            $word = "BILLION";
            break;
    }
    return $word;
}

function numberToWord($number)
{
    $numberLength = strlen($number);
    if ($numberLength == 1) {
        return singleDigit($number);
    } elseif ($numberLength == 2) {
        return doubleDigit($number);
    } else {
        $word = "";
        switch ($numberLength) {
            case 5:
            case 8:
            case 11:
                if ($number[0] > 0) {
                    $unitDigit = unitDigit($numberLength, $number[0]);
                    $word = doubleDigit($number[0] . $number[1]) . " " . $unitDigit . " ";
                    return $word . " " . numberToWord(substr($number, 2));
                } else {
                    return $word . " " . numberToWord(substr($number, 1));
                }
                break;
            default:
                if ($number[0] > 0) {
                    $unitDigit = unitDigit($numberLength, $number[0]);
                    $word = singleDigit($number[0]) . " " . $unitDigit . " ";
                }
                return $word . " " . numberToWord(substr($number, 1));
        }
    }
}
