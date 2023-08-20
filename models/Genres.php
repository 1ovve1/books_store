<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "genres".
 *
 * @property int $id
 * @property string|null $value
 *
 * @property BooksGenres[] $booksGenres
 */
class Genres extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'genres';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'value' => 'Genres',
        ];
    }

    /**
     * Gets query for [[BooksGenres]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooksGenres()
    {
        return $this->hasMany(BooksGenres::class, ['genre_id' => 'id']);
    }

    /**
     * Return pretty-viewed genre data, compatible with ActiveFormWidget checkboxList field
     *
     * @return array
     */
    public static function checkboxListData()
    {
        $genresList = [];
        foreach(Genres::find()->all() as $genreModel) {
            $genresList[$genreModel->id] = $genreModel->value;
        }

        return $genresList;
    }
}
