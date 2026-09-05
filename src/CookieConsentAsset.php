<?php

namespace scbit\cookieconsent;

use yii\web\AssetBundle;

class CookieConsentAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/assets';

    public $css = [
        'cookie-consent.css',
    ];

    public $js = [
        'cookie-consent.js',
    ];
}
