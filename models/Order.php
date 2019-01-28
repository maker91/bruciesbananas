<?php

namespace app\models;

use yii\base\Model;
use yii\validators\NumberValidator;


function _combinePackResults($result, $num_packs, $pack_size)
{
    $result["total_packs"] += $num_packs;
    $result["total_bananas"] += $num_packs * $pack_size;

    if ($num_packs > 0) {
        if (!isset($result["packs"]["$pack_size"]))
            $result["packs"]["$pack_size"] = 0;

        $result["packs"]["$pack_size"] += $num_packs;
    }

    return $result;
}


function _calculatePackSizes($quantity, $available_packs, $index)
{
    // If there are no more available sizes, make up the remaining quantity with
    // as many of the smallest pack size as we can.
    if ($index >= count($available_packs)) {
        $pack = end($available_packs);
        $num_packs = 1 + intdiv($quantity, $pack->size);

        return [
            "packs" => [
                "$pack->size" => $num_packs
            ],
            "total_packs" => $num_packs,
            "total_bananas" => $num_packs * $pack->size,
        ];
    }

    $pack = $available_packs[$index];

    // At each level of recursion (each successive pack size) there are three options:

    // 1) Use as many of this pack as possible and make up the remainder with smaller pack sizes.
    //    If there is no remainder then we are guaranteed that this is the best of the three options
    $num_packs = intdiv($quantity, $pack->size);
    $remainder = $quantity - $pack->size * $num_packs;

    if ($remainder == 0)
        return [
            "packs" => [
                "$pack->size" => $num_packs
            ],
            "total_packs" => $num_packs,
            "total_bananas" => $num_packs * $pack->size,
        ];

    $result_a = _combinePackResults(
        _calculatePackSizes($remainder, $available_packs, $index + 1),
        $num_packs, $pack->size
    );

    // 2) Use zero of this pack size and make up the remaining order quantity with smaller pack sizes.
    $result_b = _calculatePackSizes($quantity, $available_packs, $index + 1);

    // 3) Make up the order quantity entirely out of the current pack size.
    $result_c = _combinePackResults([
        "packs" => [
            "$pack->size" => 1
        ],
        "total_packs" => 1,
        "total_bananas" => $pack->size
    ], $num_packs, $pack->size);

    // Choose the options with the lowest total number of bananas
    // if any are equal, choose the one with the lowest total number of packs.
    $results = [$result_a, $result_b, $result_c];
    usort($results, function ($a, $b) {
        if ($a["total_bananas"] == $b["total_bananas"])
            return $a["total_packs"] <=> $b["total_packs"];

        return $a["total_bananas"] <=> $b["total_bananas"];
    });

    return $results[0];
}


class Order extends Model
{
    public $quantity;

    public function rules()
    {
        return [
            ['quantity', 'required'],
            [
                'quantity', NumberValidator::className(),
                'integerOnly' => true,
                'min' => 1,
                'tooSmall' => 'Please enter a quantity greater than zero'
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
          'quantity' => "Order Quantity",
        ];
    }

    public function calculatePackSizes($available_packs)
    {
        // recursively breakdown the order quantity into the best possible
        // combination of pack sizes, where best means:
        //    1. fewest number of bananas exceeding the order quantity
        //    2. fewest number of packs
        return _calculatePackSizes($this->quantity, $available_packs, 0);
    }
}