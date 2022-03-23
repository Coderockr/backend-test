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
            return intval($node->value);
        }elseif($node->decimals > self::DATABASE_VALUE_PRECISION){ //if 2 > 4 go to else, because the node precision is under the database definition
            return intval($node->value / 10 ** ($node->decimals - self::DATABASE_VALUE_PRECISION));
        }else{ //Ex: Will 
            return intval($node->value * 10 ** (self::DATABASE_VALUE_PRECISION - $node->decimals)); 
            // The number needs to be multiplied, because:
            // 10.00 when precision == 2,
            // but 10.0000 when precision == 4
            // (remove parenthesis and you get the value converted)
        }
    }
    
    public static function fromDatabaseIntToNode($int, $decimals = 2) {
        if($decimals == self::DATABASE_VALUE_PRECISION){
            return ["decimals" => $decimals, "value" => $int];
        }elseif($decimals > self::DATABASE_VALUE_PRECISION){
            // decimals == 2
            // DATABASE_VALUE_PRECISION == 4
            // US$ 10.00 == 100000 on database
            // US$ 10.00 == 1000 on node
            // 100000 / 10 ** (2 - 4)
            return ["decimals" => $decimals, "value" => intval($int / 10 ** (self::DATABASE_VALUE_PRECISION - $decimals))];
        }else{
            // decimals == 6
            // DATABASE_VALUE_PRECISION == 4
            // US$ 10.00 == 100000 on database
            // US$ 10.00 == 10000000 on node
            // 100000 * 10 ** (6 - 4)
            return ["decimals" => $decimals, "value" => intval($int * 10 ** ($decimals - self::DATABASE_VALUE_PRECISION))];
        }
    }

    public static function fromNodeToDouble($node) {
        return 0;
    }
}