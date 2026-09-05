<?php

namespace scbit\cookieconsent;

use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Яндекс.Метрика только после cookieConsent=all.
 * Счётчик не ставит cookie, пока человек не нажал «Принять все».
 */
class YandexMetrikaWidget extends Widget
{
    /** @var int */
    public $counterId;

    /** @var bool */
    public $clickmap = true;

    /** @var bool */
    public $trackLinks = true;

    /** @var bool */
    public $accurateTrackBounce = true;

    /** @var bool */
    public $webvisor = true;

    public function init()
    {
        parent::init();
        if ((int) $this->counterId <= 0) {
            throw new InvalidConfigException('YandexMetrikaWidget::$counterId обязателен.');
        }
    }

    public function run()
    {
        return $this->render('metrika', [
            'counterId' => (int) $this->counterId,
            'options' => [
                'clickmap' => (bool) $this->clickmap,
                'trackLinks' => (bool) $this->trackLinks,
                'accurateTrackBounce' => (bool) $this->accurateTrackBounce,
                'webvisor' => (bool) $this->webvisor,
            ],
        ]);
    }
}
