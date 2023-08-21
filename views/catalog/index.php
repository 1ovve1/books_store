<?php

use app\models\Books;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var Books $books */
/** @var array $sortLinks */
/** @var \yii\data\Pagination $pages */
/** @var array{genre: string, author: stirng} $filters */

$this->title = 'Catalog';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-index">
    <form class="form" method="GET">
        <div class="form-group">
            <label for="genre">Genres</label>
            <select name="genre" class="form-select my-2" aria-label="Default select example">
                <option <?= (empty($filters['genre'])) ? 'selected': '' ?>></option>
                <?php foreach(\app\models\Genres::find()->all() as $genre): ?>
                    <option
                            value="<?= $genre->value ?>"
                            <?= ($filters['genre'] === $genre->value) ? 'selected': ''?>
                    ><?= $genre->value ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="author">Authors</label>
            <select name="author" class="form-select my-2" aria-label="Default select example">
                <option <?= empty($filters['author']) ? 'selected': '' ?>></option>
                <?php foreach(\app\models\Authors::find()->all() as $author): ?>
                    <option
                            value="<?= $author->fullName() ?>"
                            <?= ($filters['author'] === $author->fullName()) ? 'selected': ''?>
                    ><?= $author->fullName() ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <input type="submit" class="btn btn-primary" value="Sort">
    </form>
    <div class="container d-flex justify-content-end">
        <div>
            <?php foreach ($sortLinks as $linkItem): ?>
                <a href="<?= $linkItem['link'] ?>"><?= $linkItem['title'] ?></a>
            <?php endforeach ?>
        </div>
    </div>

    <hr />

    <?php if ($pages->page === 0): ?>
    <div class="row py-lg-5">
        <div class="col-lg-6 col-md-8 mx-auto">
            <h1 class="fw-light">Books catalog</h1>
            <p class="lead text-muted">Something short and leading about the collection below—its contents, the creator, etc. Make it short and sweet, but not too short so folks don’t simply skip over it entirely.</p>
        </div>
    </div>
    <?php endif ?>

    <div class="container">

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">

            <?php foreach($books as $model): ?>

            <div class="col">
                <div class="card shadow-sm">
                    <img src="<?= $model->image_path ?>" class="bd-placeholder-img card-img-top" width="100%" height="225" aria-label="Placeholder: Thumbnail" />

                    <div class="card-body">
                        <p class="card-text">
                            <?= $model->title ?>
                        </p>

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <a href="<?= Url::to(['catalog/book', 'id' => $model->id]) ?>" class="btn btn-sm btn-outline-secondary">View</a>
                            </div>
                            <small class="text-muted"><i><?= 'ISBN: ' . $model->isbn ?></i></small>
                        </div>
                    </div>
                </div>
            </div>

            <?php endforeach ?>
        </div>

        <div class="d-flex w-100 justify-content-center mt-5">
            <div>
                <?=
                \yii\bootstrap5\LinkPager::widget([
                    'pagination' => $pages,
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
