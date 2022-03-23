<?php


namespace App\Services;

class DateTimeNodeTransformer {
    public static function fromNodeToDateTime($node) {
        switch ($node->format) {
            case "TIMESTAMP":
                return new \DateTime("@" . $node->value);
                break;
            default:
                throw Exception("Unknown DateTime node type");
        }
    }

    public static function fromDateTimeToNode($dateTime, $mode = "TIMESTAMP") {
        return ["format" => "TIMESTAMP", "value" => 0];
    }
}