<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

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
            [['image_path'], 'file', 'extensions' => 'png, jpg'],
            [['description'], 'string'],
            [['date'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 255],
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
            'image_path' => 'Book image',
            'isbn' => 'ISBN',
            'title' => 'Title',
            'description' => 'Description',
            'date' => 'Date',
            'genres' => 'Genres'
        ];
    }


    /**
     * Image saver accessor
     *
     * @param $value
     * @return void
     */
    public function setImagePath($value)
    {
        if ($value) {
            $path =  'upload/' . $value->baseName . '.' . $value->extension;
            $value->saveAs($path);
            $this->image_path = '/' . $path;
        }
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
            return 'Without genre';
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
     * WARNING high-level assembler bellow
     *
     * @param $genre
     * @param $author
     * @return ActiveQuery \yii\db\ActiveQuery
     */
    static function filterBooksByGenreAndAuthor($genre, $author)
    {
        $authorNameParts = explode(' ', $author);
        $books = Books::find()
            ->joinWith(['booksGenres.genre', 'author'])
            ->filterWhere(['like', 'genres.value', $genre])
            ->andFilterWhere(['like', 'authors.first_name', $authorNameParts[0] ?? ''])
            ->andFilterWhere(['like', 'authors.last_name', $authorNameParts[1] ?? ''])
            ->andFilterWhere(['like', 'authors.patronymic', $authorNameParts[2] ?? '']);

        return $books;
    }

}
