<?php
/**
 * Оверлей на раздел: /oto, /blog и т.п.
 * Обе кнопки открывают сайт. Метрика — только после «Принять все».
 */

use scbit\cookieconsent\CookieConsentWidget;
use scbit\cookieconsent\YandexMetrikaWidget;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= $this->title ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?= $content ?>

<?= CookieConsentWidget::widget([
    'privacyUrl' => ['/site/privacy'],
    'cookiesUrl' => ['/site/cookies'],
    // На главной — плашка. На каталоге и в блоге — оверлей на весь экран.
    'overlayPaths' => ['oto', 'blog'],
]) ?>

<?= YandexMetrikaWidget::widget([
    'counterId' => 12345678,
]) ?>

<?php $this->endBody() ?>
</body>
</html>
