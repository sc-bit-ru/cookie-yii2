# cookie-yii2

Плашка cookie для Yii 2 по **152-ФЗ**. Стоит на [web-dk.ru](https://web-dk.ru): человек сам нажимает «Только нужные» или «Принять все», Яндекс.Метрика не стартует до согласия.

Это **не юридическая консультация**. Текст и кнопки закрывают обычные претензии Роскомнадзора к сайту с Метрикой. Политику ПДн и реестр оператора каждый сайт оформляет сам.

Живой пример: [web-dk.ru/cookies](https://web-dk.ru/cookies).

---

## Зачем эта плашка

В 152-ФЗ нет слова «cookie». Роскомнадзор и суды относят к персональным данным cookie-идентификатор, IP и запись визита (Метрика, вебвизор). Согласие по ст. 9 должно быть **конкретным, информированным и сознательным**.

Поэтому на сайте с аналитикой нельзя:

| Так делать нельзя | Как надо |
|---|---|
| «Продолжая использовать сайт, вы согласны…» | Человек нажал кнопку |
| Одна кнопка «OK» / «Понятно» | Две кнопки одной силы: принять и отказать |
| Счётчик Метрики в `<head>` у всех | Скрипт `mc.yandex.ru` только после «Принять все» |
| Пиксель в `<noscript>` | Убрать: он стреляет без JS и без согласия |
| Политика молчит про cookie и Метрику | Страница со списком cookie, сроками и отзывом |

Технические cookie (сессия, CSRF, вход) можно ставить без отдельного согласия: без них страница не работает. Про них всё равно пишем в политике и на `/cookies`.

Реклама, пиксели VK/Meta, Google Analytics — то же правило, что у Метрики: не грузить до «Принять все». В этом пакете из коробки подключена только Метрика.

---

## Что ставит пакет

1. **Плашка** внизу экрана: короткий текст, ссылка на политику, ссылка на `/cookies`, кнопки «Только нужные» и «Принять все».
2. **Cookie `cookieConsent`**: `all` или `necessary`, 365 дней, `SameSite=Lax`, `Secure` на HTTPS.
3. **Отложенная Метрика**: `YandexMetrikaWidget` объявляет `window.yiiCookieConsent.loadAnalytics()`, плашка вызывает её только при `all`.
4. **Страница `/cookies`**: оператор, таблица cookie, смена выбора, отзыв.

Старое значение `cookieConsent=true` (плашка «OK») **не считается** согласием — человек увидит новые кнопки ещё раз.

---

## Установка

Репозиторий пока не в Packagist. В `composer.json` сайта:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/sc-bit-ru/cookie-yii2"
        }
    ],
    "require": {
        "sc-bit/yii2-cookie-consent": "dev-master"
    }
}
```

```bash
composer update sc-bit/yii2-cookie-consent
```

Либо скопируйте папку `src/` и пропишите автозагрузку `scbit\cookieconsent\`.

---

## Подключение в layout

В конце `<body>`, **до** `endBody()` (или сразу после контента):

```php
<?php
use scbit\cookieconsent\CookieConsentWidget;
use scbit\cookieconsent\YandexMetrikaWidget;
?>

<?= CookieConsentWidget::widget([
    'privacyUrl' => ['/site/privacy'],   // или PDF
    'cookiesUrl' => ['/site/cookies'],
]) ?>

<?= YandexMetrikaWidget::widget([
    'counterId' => 12345678,             // свой номер
    'webvisor' => true,
]) ?>
```

Полный каркас: [`example/layout.php`](example/layout.php).

Уберите старый код Метрики из всех layout и view. Иначе счётчик снова поставит cookie до клика.

Свой счётчик / пиксель без виджета:

```html
<script>
window.yiiCookieConsent = window.yiiCookieConsent || {};
window.yiiCookieConsent.loadAnalytics = function () {
    if (window.yiiCookieConsent.loaded) return;
    window.yiiCookieConsent.loaded = true;
    // сюда — init Метрики, пикселя и т.п.
};
</script>
```

---

## Страница «Cookie-файлы»

```php
use scbit\cookieconsent\CookiesPageWidget;

public function actionCookies()
{
    return $this->renderContent(CookiesPageWidget::widget([
        'operator' => 'ИП Иванов Иван Иванович',
        'inn' => '000000000000',
        'ogrn' => '000000000000000',
        'address' => 'индекс, регион, город, улица',
        'email' => 'support@example.ru',
        'phone' => '8 (000) 000-00-00',
        'privacyUrl' => ['/site/privacy'],
        'siteUrl' => 'https://example.ru',
        'metrikaCounterId' => 12345678,
        'revisionDate' => '05.09.2026',
        // 'extraCookies' => [
        //     ['name' => '<code>_ga</code>', 'purpose' => 'Google Analytics', 'ttl' => '2 года', 'consent' => 'Да'],
        // ],
    ]));
}
```

Ссылку на `/cookies` добавьте в подвал рядом с политикой.

---

## Текст плашки (можно копировать)

> На сайте стоят cookie. Нужные — чтобы открывались страницы и работали формы. Аналитические (Яндекс.Метрика) — чтобы понимать, как сайтом пользуются. Подробности — в Политике конфиденциальности и в описании cookie.

Свой текст:

```php
CookieConsentWidget::widget([
    'text' => 'Мы пишем cookie. Нужные — чтобы сайт открывался. Метрика — только с вашего согласия. См. {privacy} и {cookies}.',
    'privacyUrl' => ['/site/privacy'],
    'cookiesUrl' => ['/site/cookies'],
])
```

Кнопки не прячьте и не делайте «Отклонить» серой мелкой. Это тёмный паттерн, его замечают при проверке.

---

## Проверка перед публикацией

1. В режиме инкогнито открыть главную — в Network **нет** `mc.yandex.ru` и `_ym_*`.
2. «Только нужные» — плашка закрылась, Метрика так и не ушла.
3. «Принять все» — появился `tag.js`, в cookie есть `cookieConsent=all`.
4. Обновить страницу — плашки нет, Метрика сразу есть.
5. На `/cookies` нажать «Только нужные» — `_ym_*` снялись, после перезагрузки счётчика нет.
6. Мобильная вёрстка: обе кнопки читаются, плашка не закрывает форму целиком.

---

## Без Yii

Скопируйте `src/assets/cookie-consent.js`, `src/assets/cookie-consent.css` и разметку из `src/views/banner.php`. Счётчик подключайте через `window.yiiCookieConsent.loadAnalytics`, как в `src/views/metrika.php`. Логика та же.

---

## Где уже стоит

- [web-dk.ru](https://web-dk.ru) — программа для техосмотра, фото ЕАИСТО.

Лицензия: [BSD-3-Clause](LICENSE).
