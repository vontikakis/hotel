<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "availability".
 *
 * @property int $id
 * @property string|null $calendar_date
 * @property string|null $status
 * @property int|null $roomId
 *
 * @property Room $room
 */
class Availability extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'availability';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['calendar_date'], 'safe'],
            [['status'], 'string'],
            [['roomId'], 'integer'],
            [['roomId'], 'exist', 'skipOnError' => true, 'targetClass' => Room::class, 'targetAttribute' => ['roomId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'calendar_date' => 'Calendar Date',
            'status' => 'Status',
            'roomId' => 'Room ID',
        ];
    }

    /**
     * Gets query for [[Room]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoom()
    {
        return $this->hasOne(Room::class, ['id' => 'roomId']);
    }
}
