<?php


namespace App\Services;

class FinancialValueNodeTransformer {

    const DATABASE_VALUE_PRECISION = 4;

    public static function fromNodeToDatabaseInt($node) {

        /*
            Example:
                $node->value == 1000
                $node->decimals == 2
                self::DATABASE_VALUE_PRECISION == 4
                if value == 1000, then USD == U$ 10,00
                the correct algorithm is 1000 / 10 ^ 2 to get the financial value
                but, we need a database default constant to transform financial value to int
                The solution:
                    Make a algorithm to make a conversible precision like below
        */
        if($node->decimals == self::DATABASE_VALUE_PRECISION){
            return $node->value;
        }elseif($node->decimals > self::DATABASE_VALUE_PRECISION){ //if 2 > 4 go to else, because the node precision is under the database definition
            return $node->value / 10 ** ($node->decimals - self::DATABASE_VALUE_PRECISION);
        }else{ //Ex: Will 
            return $node->value * 10 ** (self::DATABASE_VALUE_PRECISION - $node->decimals); 
            // The number needs to be multiplied, because:
            // 10.00 when precision == 2,
            // but 10.0000 when precision == 4
            // (remove parenthesis and you get the value converted)
        }
    }
    
    public static function fromDatabaseIntToNode($int, $decimals) {
        if($node->decimals == self::DATABASE_VALUE_PRECISION){
            return $node->value;
        }elseif($node->decimals > self::DATABASE_VALUE_PRECISION){
            return $node->value / 10 ** ($node->decimals - self::DATABASE_VALUE_PRECISION);
        }else{
            return $node->value * 10 ** (self::DATABASE_VALUE_PRECISION - $node->decimals); 
        }
    }

    public static function fromNodeToDouble($node) {
        return 0;
    }

    public static function fromDateTimeToNode($dateTime, $mode = "TIMESTAMP") {
        return ["decimals" => 2, "value" => 0];
    }
}