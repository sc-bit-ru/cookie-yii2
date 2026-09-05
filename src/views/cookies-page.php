<?php

use yii\helpers\Html;

/** @var string $operator */
/** @var string $inn */
/** @var string $ogrn */
/** @var string $address */
/** @var string $email */
/** @var string|null $phone */
/** @var string|array $privacyUrl */
/** @var string $siteUrl */
/** @var int|null $metrikaCounterId */
/** @var string $revisionDate */
/** @var array $extraCookies */
/** @var bool $hasAds */

$operatorLine = $operator;
if ($inn !== '') {
    $operatorLine .= ', ИНН ' . $inn;
}
if ($ogrn !== '') {
    $operatorLine .= ', ОГРН/ОГРНИП ' . $ogrn;
}
if ($address !== '') {
    $operatorLine .= ', ' . $address;
}
?>
<div class="cookie-yii2-page">
    <h1>Cookie-файлы</h1>

    <div class="cookie-yii2-meta">
        <p><strong>Редакция от <?= Html::encode($revisionDate) ?>.</strong> Оператор: <?= Html::encode($operatorLine) ?>.</p>
        <p>Контакты: <?= Html::a(Html::encode($email), 'mailto:' . $email) ?><?php if ($phone): ?>, <?= Html::a(Html::encode($phone), 'tel:' . preg_replace('/[^\d+]/', '', $phone)) ?><?php endif; ?>.</p>
        <p>Документ дополняет <?= Html::a('Политику обработки персональных данных', $privacyUrl, ['target' => '_blank', 'rel' => 'noopener']) ?> в части cookie. Правовая основа — Федеральный закон от 27.07.2006 № 152-ФЗ «О персональных данных».</p>
    </div>

    <h2>1. Что это за файлы</h2>
    <p>Cookie — небольшие файлы, которые сайт или счётчик записывает в браузер. По ним можно отличить один заход от другого. Если по cookie можно выделить человека или устройство, это персональные данные. Такие cookie мы не ставим, пока вы явно не согласитесь.</p>
    <p>Нужные cookie сайт включает сам: без них не откроется страница, не уйдёт форма и не запомнится ваш выбор в плашке. На них отдельное согласие не спрашиваем — без них сайт просто не работает.</p>

    <h2>2. Какие cookie стоят на сайте</h2>
    <table class="cookie-yii2-table">
        <thead>
            <tr>
                <th>Имя</th>
                <th>Зачем</th>
                <th>Срок</th>
                <th>Нужно ли согласие</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>cookieConsent</code></td>
                <td>Запоминает ваш выбор: только нужные или все cookie</td>
                <td>365 дней</td>
                <td>Нет — это сама отметка согласия</td>
            </tr>
            <tr>
                <td><code>PHPSESSID</code></td>
                <td>Сессия: вход, сообщения на экране, черновики форм</td>
                <td>Пока открыт браузер</td>
                <td>Нет</td>
            </tr>
            <tr>
                <td><code>_csrf</code></td>
                <td>Защита форм от подделки запроса</td>
                <td>Пока открыт браузер</td>
                <td>Нет</td>
            </tr>
            <tr>
                <td><code>_identity</code></td>
                <td>Вход в кабинет, если отметили «запомнить»</td>
                <td>До выхода или истечения срока</td>
                <td>Нет</td>
            </tr>
            <?php if ($metrikaCounterId): ?>
            <tr>
                <td><code>_ym_uid</code>, <code>_ym_d</code>, <code>_ym_isad</code>, <code>_ym_visorc</code></td>
                <td>Яндекс.Метрика: визиты, источники, отказы, вебвизор</td>
                <td>До 1 года</td>
                <td>Да — кнопка «Принять все»</td>
            </tr>
            <?php endif; ?>
            <?php foreach ($extraCookies as $row): ?>
            <tr>
                <td><?= isset($row['name']) ? $row['name'] : '' /* допускается HTML, например <code>_ga</code> */ ?></td>
                <td><?= Html::encode(isset($row['purpose']) ? $row['purpose'] : '') ?></td>
                <td><?= Html::encode(isset($row['ttl']) ? $row['ttl'] : '') ?></td>
                <td><?= Html::encode(isset($row['consent']) ? (string) $row['consent'] : '') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p>Точный набор служебных cookie может чуть отличаться (версия PHP, вход в кабинет).<?= $hasAds ? '' : ' Рекламных сетей на сайте нет.' ?></p>

    <h2>3. Как дать или отозвать согласие</h2>
    <p>При первом заходе внизу экрана две кнопки одной силы: «Только нужные» и «Принять все». Продолжение просмотра само по себе согласием не считается.</p>
    <p>Сменить выбор можно здесь:</p>
    <p>
        <button type="button" class="cookie-consent-btn cookie-consent-btn-secondary" data-cookie-consent="necessary">Только нужные</button>
        <button type="button" class="cookie-consent-btn cookie-consent-btn-primary" data-cookie-consent="all">Принять все</button>
    </p>
    <p id="cookieConsentStatus"></p>
    <p>Отозвать согласие на аналитику можно кнопкой «Только нужные», письмом на <?= Html::a(Html::encode($email), 'mailto:' . $email) ?> или очисткой cookie в браузере. После отзыва счётчик аналитики на следующих страницах не запускается.</p>

    <?php if ($metrikaCounterId): ?>
    <h2>4. Яндекс.Метрика</h2>
    <p>Счётчик <?= (int) $metrikaCounterId ?> (ООО «Яндекс», серверы в РФ). После согласия Метрика видит адрес страницы, источник перехода, устройство и может записывать визит вебвизором. Пароли в вебвизоре маскируются, но поля форм лучше не считать тайной: если не хотите запись визита — нажмите «Только нужные».</p>
    <p>Политика Яндекса: <a href="https://yandex.ru/legal/confidential/" target="_blank" rel="noopener">yandex.ru/legal/confidential</a>.</p>
    <?php endif; ?>

    <?php if ($siteUrl !== ''): ?>
    <p>Сайт: <?= Html::a(Html::encode($siteUrl), $siteUrl) ?>.</p>
    <?php endif; ?>
</div>
<style>
.cookie-yii2-page { max-width: 860px; margin: 0 auto; padding: 24px 16px 80px; line-height: 1.55; }
.cookie-yii2-meta { padding: 16px 20px; background: #f7f9fc; border: 1px solid #e3e8ef; border-radius: 6px; margin: 0 0 24px; }
.cookie-yii2-meta p { margin: 0 0 8px; }
.cookie-yii2-meta p:last-child { margin-bottom: 0; }
.cookie-yii2-table { width: 100%; border-collapse: collapse; margin: 16px 0 24px; font-size: 14px; }
.cookie-yii2-table th, .cookie-yii2-table td { border: 1px solid #e3e8ef; padding: 10px 12px; text-align: left; vertical-align: top; }
.cookie-yii2-table th { background: #f7f9fc; }
#cookieConsentStatus { min-height: 1.4em; }
</style>
