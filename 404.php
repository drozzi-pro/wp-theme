<?php CE::theLayout('header'); ?>

<div class="top__bg-gradient">
    <div class="top__bg-box"></div>
    <section class="page-404">
        <div class="container">
            <div class="page-404__inner">
                <h1 class="page-404__title title-h1">
                    Потерялись в космосе?
                </h1>
            </div>
        </div>
        <img class="page-404__img" src="<?= get_theme_file_uri('src/img/astronaut-404.png') ?>" alt="">
        <div class="container">
            <a class="page-404__link" href="#">
                перейти на главную
            </a>
        </div>
    </section>
</div>

<?php CE::theLayout('footer'); ?>
