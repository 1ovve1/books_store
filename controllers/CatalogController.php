<?php

namespace app\controllers;

use app\models\Books;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BooksController implements the CRUD actions for Books model.
 */
class CatalogController extends Controller
{
    const SORTS = [
        'date' => 'By date',
        'title' => 'By title'
    ];

    /**
     * Lists all Books models.
     *
     * @param mixed $sortBy
     * @param mixed $sortType
     * @param string $genre
     * @param string $author
     * @return string
     */
    public function actionIndex($sortBy = 'date',
                                $sortType = SORT_ASC,
                                $genre = '',
                                $author = '')
    {
        $books = Books::filterBooksByGenreAndAuthor($genre, $author);

        $books = $this->resolveSortBy($books, $sortBy, $sortType);

        $pages = new Pagination(['totalCount' => $books->count(), 'pageSize' => 2]);

        $books = $books->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        $sortLinks = $this->generateSortLinks($sortBy, $sortType);

        return $this->render('index', [
            'books' => $books,
            'pages' => $pages,
            'sortLinks' => $sortLinks,
            'filters' => [
                'genre' => $genre,
                'author' => $author,
            ]
        ]);
    }

    /**
     * Displays a single Books model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionBook($id)
    {
        return $this->render('book', [
            'book' => $this->findModel($id),
        ]);
    }

    /**
     * Return books collection with sortBy resolving
     *
     * @param $query
     * @param $sortBy
     * @param $sortType
     * @return ActiveQuery
     */
    protected function resolveSortBy($query, $sortBy, $sortType) {
        return match($sortBy) {
            'date' => $query->orderBy(['date' => (int)$sortType]),
            'title' => $query->orderBy(['title' => (int)$sortType]),
            default => $query,
        };
    }



    /**
     * Finds the Books model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Books the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Books::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $sortBy
     * @param $sortType
     * @return array<array{
     *     title: string,
     *     link: string,
     *     active: bool,
     *     type: int,
     * }>
     */
    protected function generateSortLinks($sortBy, $sortType)
    {
        $sortLinks = [];

        foreach (self::SORTS as $sort => $title) {
            $linkItem = [ 'title' => $title ];


            if ($sort === $sortBy) {
                $linkItem['active'] = true;

                if ((int)$sortType === SORT_ASC) {
                    $linkItem['type'] = SORT_DESC;
                    $linkItem['link'] = Url::current(['sortBy' => $sort, 'sortType' => SORT_DESC]);
                } else {
                    $linkItem['type'] = SORT_ASC;
                    $linkItem['link'] = Url::current(['sortBy' => $sort, 'sortType' => SORT_ASC]);

                }

            } else {
                $linkItem['active'] = false;
                $linkItem['link'] = Url::current(['sortBy' => $sort]);
                $linkItem['type'] = SORT_ASC;
            }

            $sortLinks[] = $linkItem;
        }

        return $sortLinks;
    }
}
