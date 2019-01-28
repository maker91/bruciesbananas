<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\validators\NumberValidator;


class Pack extends ActiveRecord
{
    public function rules()
    {
        return [
            ['size', 'required'],
            ['size', 'unique', 'message' => "Pack Size already exists."],
            [
                'size', NumberValidator::className(),
                'integerOnly' => true,
                'min' => 1,
                'tooSmall' => 'Please enter a quantity greater than zero',
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'size' => 'Pack Size'
        ];
    }
}