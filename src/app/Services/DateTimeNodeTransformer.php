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

    public static function fromDateTimeStringToNode($dateTime, $mode = "TIMESTAMP") {
        switch ($mode) {
            case "TIMESTAMP":
                return $dateTime == null ? null : ["format" => "TIMESTAMP", "value" => (new \DateTime($dateTime))->getTimestamp()];
                break;
            default:
                throw Exception("Unknown DateTime node type");
        }
    }

    
    public static function fromDateTimeStringToDateTime($dateTime) {
        return new \DateTime($dateTime);
    }
}