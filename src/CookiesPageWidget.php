<?php

namespace scbit\cookieconsent;

use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Публичная страница «Cookie-файлы»: список, цели, как отозвать согласие.
 */
class CookiesPageWidget extends Widget
{
    /** @var string ФИО / название оператора */
    public $operator;

    /** @var string ИНН */
    public $inn = '';

    /** @var string ОГРН / ОГРНИП */
    public $ogrn = '';

    /** @var string */
    public $address = '';

    /** @var string */
    public $email;

    /** @var string|null */
    public $phone;

    /** @var string|array */
    public $privacyUrl = ['/site/privacy'];

    /** @var string */
    public $siteUrl;

    /** @var int|null Номер счётчика Метрики */
    public $metrikaCounterId;

    /** @var string Дата редакции, например 05.09.2026 */
    public $revisionDate;

    /**
     * Дополнительные строки таблицы cookie.
     * Каждая: name, purpose, ttl, consent (bool|string)
     * @var array
     */
    public $extraCookies = [];

    /** @var bool */
    public $hasAds = false;

    public function init()
    {
        parent::init();
        if ($this->operator === null || $this->operator === '') {
            throw new InvalidConfigException('CookiesPageWidget::$operator обязателен.');
        }
        if ($this->email === null || $this->email === '') {
            throw new InvalidConfigException('CookiesPageWidget::$email обязателен.');
        }
        if ($this->revisionDate === null || $this->revisionDate === '') {
            $this->revisionDate = date('d.m.Y');
        }
    }

    public function run()
    {
        CookieConsentAsset::register($this->view);

        return $this->render('cookies-page', [
            'operator' => $this->operator,
            'inn' => $this->inn,
            'ogrn' => $this->ogrn,
            'address' => $this->address,
            'email' => $this->email,
            'phone' => $this->phone,
            'privacyUrl' => $this->privacyUrl,
            'siteUrl' => $this->siteUrl ?: '',
            'metrikaCounterId' => $this->metrikaCounterId,
            'revisionDate' => $this->revisionDate,
            'extraCookies' => $this->extraCookies,
            'hasAds' => (bool) $this->hasAds,
        ]);
    }
}
