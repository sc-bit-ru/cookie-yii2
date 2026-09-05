<?php
/**
 * Пример подключения в layouts/main.php
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
]) ?>

<?= YandexMetrikaWidget::widget([
    'counterId' => 12345678,
    'webvisor' => true,
]) ?>

<?php $this->endBody() ?>
</body>
</html>
