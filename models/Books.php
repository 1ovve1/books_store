<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "books".
 *
 * @property int $id
 * @property int|null $author_id
 * @property string|null $title
 * @property string|null $description
 * @property string|null $date
 * @property array|null $genres
 *
 * @property Authors $author
 * @property BooksGenres[] $booksGenres
 */
class Books extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'books';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['author_id'], 'integer'],
            [['description'], 'string'],
            [['date'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => Authors::class, 'targetAttribute' => ['author_id' => 'id']],
            [['genres'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author_id' => 'Author ID',
            'title' => 'Title',
            'description' => 'Description',
            'date' => 'Date',
            'genres' => 'Genres'
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Authors::class, ['id' => 'author_id']);
    }

    /**
     * Gets query for [[BooksGenres]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooksGenres()
    {
        return $this->hasMany(BooksGenres::class, ['book_id' => 'id']);
    }


    /**
     * Getter for genres (using BooksGenres model inside)
     *
     * @return string
     */
    public function getGenres()
    {
        $booksGenres = $this->booksGenres;

        $clearGenres = [];
        foreach ($booksGenres as $genre) {
            $clearGenres[] = $genre->genre->value;
        }

        if (empty($clearGenres)) {
            return 'Без жанра';
        } else {
            return implode(', ', $clearGenres);
        }
    }

    /**
     * Setter for genres (override BooksGenres rows by book_id)
     *
     * @param $value
     * @return void
     */
    public function setGenres($value)
    {
        BooksGenres::deleteAll(['book_id' => $this->id]);

        if (!is_array($value)) {
            return;
        }

        foreach ($value as $genre_id)
        {
            if (BooksGenres::findOne(['genre_id' => $genre_id, 'book_id' => $this->id])) {
                continue;
            }
            $bookGenre = new BooksGenres();

            $bookGenre->book_id = $this->id;
            $bookGenre->genre_id = $genre_id;

            $bookGenre->save();
        }
    }

    /**
     * Date formatter
     *
     * @param $value
     * @return void
     */
    public function setDate($value)
    {
        $this->data = \DateTime::createFromFormat("Y-m-d", $value)->format("Y-m-d");
    }

}
