<?php
/**
 * Режим стены: без «Принять все» экран закрыт.
 * Контент остаётся в HTML — так индексируют роботы.
 * /cookies и политика не закрываются (виджет сам переключает их на banner).
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
    'mode' => CookieConsentWidget::MODE_WALL,
    'privacyUrl' => ['/site/privacy'],
    'cookiesUrl' => ['/site/cookies'],
    // 'freePaths' => ['licence', 'uploads/politika.pdf'],
]) ?>

<?= YandexMetrikaWidget::widget([
    'counterId' => 12345678,
    'webvisor' => true,
]) ?>

<?php $this->endBody() ?>
</body>
</html>
