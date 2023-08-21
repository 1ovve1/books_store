<?php

use app\models\Books;

/** @var yii\web\View $this */
/** @var Books $book */

$this->title = 'Book: ' . $book->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-book">
    <div class="container">
        <div class="row">
            <div class="col-4 justify-content-center">
                <div>
                    <img
                            class="img-fluid"
                            src="<?= $book->image_path ?>"
                            alt="<?= $book->title ?>"
                    />
                </div>
            </div>
            <div class="col-8 d-flex justify-content-center">
                <div class="w-50">
                    <h3 class="fw-medium fst-italic"><?= $book->title ?></h3>
                    <p class="fst-italic text-muted">ISBN: <?= $book->isbn ?></p>
                    <p> <?= $book->description ?> </p>
                </div>
            </div>
        </div>
    </div>

</div>
