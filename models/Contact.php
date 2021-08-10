<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contact".
 *
 * @property int $id
 * @property string $name
 * @property string $user_id
 * @property string $sur_name
 * @property string $phone_number
 * @property string $email
 * @property string|null $text_message
 */
class Contact extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contact';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'sur_name', 'phone_number', 'email','user_id'], 'required'],
            [['name', 'sur_name', 'email', 'text_message'], 'string', 'max' => 255],
            [['phone_number'], 'string', 'max' => 32],
//            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'sur_name' => 'Sur Name',
            'phone_number' => 'Phone Number',
            'email' => 'Email',
            'text_message' => 'Text Message',
        ];
    }
}