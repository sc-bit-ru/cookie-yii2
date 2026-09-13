# cookie-yii2

Расширение Yii 2 для согласия на cookie по **152-ФЗ**: две кнопки одной силы, аналитика только после «Принять все».

Режимы:

- **banner** — плашка внизу, сайт сразу открыт;
- **overlay** — затемнение на весь экран, пока человек не выберет кнопку. После «Только нужные» сайт открывается, счётчик не грузится.

Закрывать сайт за отказ от аналитики нельзя: согласие тогда не свободное.

Это не юридическая консультация. Политику персональных данных и уведомление в Роскомнадзор каждый оператор оформляет сам.

---

## Зачем

В 152-ФЗ нет слова «cookie». Идентификатор в браузере, IP и запись визита (Метрика, вебвизор) суды и Роскомнадзор относят к персональным данным. Согласие по ст. 9 должно быть конкретным, информированным и сознательным.

| Так делать нельзя | Как надо |
|---|---|
| «Продолжая использовать сайт, вы согласны…» | Человек нажал кнопку |
| Одна кнопка «OK» / «Понятно» | Две кнопки одной силы: принять и отказать |
| Счётчик в `<head>` у всех | Скрипт аналитики только после «Принять все» |
| Пиксель в `<noscript>` | Убрать: срабатывает без JS и без согласия |
| Политика молчит про cookie | Страница со списком cookie, сроками и отзывом |

Сессия, CSRF и вход — технические cookie, без них страница не работает. Отдельную кнопку на них не спрашивают, но в политике и на `/cookies` их всё равно перечисляют.

Рекламные пиксели и Google Analytics — то же правило, что у Метрики: не включать до «Принять все». В пакете из коробки — виджет Яндекс.Метрики.

---

## Что входит

1. Плашка или оверлей с кнопками «Только нужные» и «Принять все». Оверлей можно включить на префиксы путей (`blog`, `catalog`).
2. Cookie `cookieConsent`: `all` или `necessary`, 365 дней, `SameSite=Lax`, на HTTPS ещё `Secure`.
3. `YandexMetrikaWidget` — `window.yiiCookieConsent.loadAnalytics()` вызывается только при `all`.
4. Страница `/cookies`: реквизиты оператора, таблица cookie, смена выбора.

Старое значение `cookieConsent=true` согласием не считается — кнопки покажутся снова.

---

## Установка

Репозиторий не в Packagist. В `composer.json` сайта:

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

Либо скопируйте `src/` и пропишите автозагрузку `scbit\cookieconsent\`.

---

## Подключение

В конце `<body>`, до `endBody()`:

```php
<?php
use scbit\cookieconsent\CookieConsentWidget;
use scbit\cookieconsent\YandexMetrikaWidget;
?>

<?= CookieConsentWidget::widget([
    'privacyUrl' => ['/site/privacy'],
    'cookiesUrl' => ['/site/cookies'],
]) ?>

<?= YandexMetrikaWidget::widget([
    'counterId' => 12345678,
    'webvisor' => true,
]) ?>
```

Примеры: [`example/layout.php`](example/layout.php), оверлей — [`example/layout-overlay.php`](example/layout-overlay.php).

```php
CookieConsentWidget::widget([
    'mode' => CookieConsentWidget::MODE_OVERLAY,
    'privacyUrl' => ['/site/privacy'],
    'cookiesUrl' => ['/site/cookies'],
]);

CookieConsentWidget::widget([
    'privacyUrl' => ['/site/privacy'],
    'cookiesUrl' => ['/site/cookies'],
    'overlayPaths' => ['blog', 'catalog'],
]);
```

Политика и `/cookies` оверлеем не закрываются — иначе нельзя прочитать условия до кнопки.

HTML страницы не вырезается. Без JavaScript оверлей скрыт. Поисковым роботам оверлей не показываем: тот же текст, экран не закрыт.

Уберите прежний код счётчика из layout. Иначе cookie аналитики встанут до клика.

Свой счётчик:

```html
<script>
window.yiiCookieConsent = window.yiiCookieConsent || {};
window.yiiCookieConsent.loadAnalytics = function () {
    if (window.yiiCookieConsent.loaded) return;
    window.yiiCookieConsent.loaded = true;
    // init счётчика
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
        'revisionDate' => date('d.m.Y'),
    ]));
}
```

Ссылку на `/cookies` добавьте в подвал рядом с политикой.

---

## Текст плашки

> На сайте стоят cookie. Нужные — чтобы открывались страницы и работали формы. Аналитические (Яндекс.Метрика) — чтобы понимать, как сайтом пользуются. Подробности — в Политике конфиденциальности и в описании cookie.

```php
CookieConsentWidget::widget([
    'text' => 'Мы пишем cookie. Нужные — чтобы сайт открывался. Метрика — только с вашего согласия. См. {privacy} и {cookies}.',
    'privacyUrl' => ['/site/privacy'],
    'cookiesUrl' => ['/site/cookies'],
]);
```

Кнопки одной силы. «Отклонить» мелким серым — тёмный паттерн.

---

## Проверка

1. Инкогнито, Network: нет `mc.yandex.ru` и `_ym_*` до клика.
2. «Только нужные» — окно закрылось, счётчика нет.
3. «Принять все» — есть `tag.js` и `cookieConsent=all`.
4. Повторный заход — окна нет, счётчик сразу есть.
5. На `/cookies` можно сменить выбор.
6. На узком экране обе кнопки читаются.
7. Overlay: обе кнопки снимают затемнение; Метрика только после «Принять все».

---

## Без Yii

Скопируйте `src/assets/cookie-consent.js`, `src/assets/cookie-consent.css` и разметку из `src/views/banner.php`. Счётчик — через `window.yiiCookieConsent.loadAnalytics`, как в `src/views/metrika.php`.

Лицензия: [BSD-3-Clause](LICENSE).
