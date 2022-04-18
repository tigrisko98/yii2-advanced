<?php

namespace frontend\views\layouts;

use Yii;

class PublicationImagesCarousel
{
    public static function renderCarousel(array $images)
    {
        ob_start(); ?>

        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel" data-interval="false">
            <div class="carousel-inner">

                <ol class="carousel-indicators">
                    <?php foreach ($images as $key => $image): ?>
                        <?php if ($key == 0): ?>
                            <li data-target="#carouselExampleIndicators" data-slide-to="<?= $key; ?>"
                                class="active"></li>
                        <?php else: ?>
                            <li data-target="#carouselExampleIndicators" data-slide-to="<?= $key; ?>"></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ol>

                <?php foreach ($images as $key => $image): ?>

                <?php if ($key == 0): ?>
                <div class="carousel-item active">
                    <?php else: ?>
                    <div class="carousel-item">
                        <?php endif; ?>
                        <img class="d-block w-100" src="<?= $image; ?>" alt="Publication image"
                             style="width: 600px; height: 600px">
                    </div>

                    <?php endforeach; ?>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button"
                   data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button"
                   data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>

        <?php return ob_get_clean();
    }

}