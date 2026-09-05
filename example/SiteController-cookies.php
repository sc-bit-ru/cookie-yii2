<?php
/**
 * Фрагмент контроллера: страница /cookies
 */

use scbit\cookieconsent\CookiesPageWidget;

public function actionCookies()
{
    return $this->renderContent(CookiesPageWidget::widget([
        'operator' => 'ИП Иванов Иван Иванович',
        'inn' => '000000000000',
        'ogrn' => '000000000000000',
        'address' => '000000, регион, город, улица, дом',
        'email' => 'support@example.ru',
        'phone' => '8 (000) 000-00-00',
        'privacyUrl' => ['/site/privacy'],
        'siteUrl' => 'https://example.ru',
        'metrikaCounterId' => 12345678,
        'revisionDate' => date('d.m.Y'),
    ]));
}
