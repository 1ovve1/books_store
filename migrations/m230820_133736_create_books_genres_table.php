<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book_genres}}`.
 */
class m230820_133736_create_books_genres_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%books_genres}}', [
            'id' => $this->primaryKey(),

            'book_id' => $this->integer(),
            'genre_id' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk-books_genres-book_id',
            'books_genres',
            'book_id',
            'books',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-books_genres-genre_id',
            'books_genres',
            'genre_id',
            'genres',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%book_genres}}');
    }
}
