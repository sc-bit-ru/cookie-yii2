<?php

namespace scbit\cookieconsent;

use yii\base\Widget;
use yii\helpers\Html;

/**
 * Плашка согласия на cookie по 152-ФЗ.
 *
 * Две кнопки одной силы: «Только нужные» и «Принять все».
 * Продолжение просмотра согласием не считается.
 */
class CookieConsentWidget extends Widget
{
    /** @var string|array Ссылка на политику конфиденциальности */
    public $privacyUrl = ['/site/privacy'];

    /** @var string|array|null Страница со списком cookie; null — не показывать ссылку */
    public $cookiesUrl = ['/site/cookies'];

    /** @var string */
    public $privacyLinkText = 'Политике конфиденциальности';

    /** @var string */
    public $cookiesLinkText = 'описании cookie';

    /**
     * Текст плашки. Плейсхолдеры: {privacy} {cookies}.
     * Если null — стандартная формулировка.
     * @var string|null
     */
    public $text;

    /** @var string */
    public $acceptLabel = 'Принять все';

    /** @var string */
    public $rejectLabel = 'Только нужные';

    public function run()
    {
        CookieConsentAsset::register($this->view);

        $privacyLink = Html::a($this->privacyLinkText, $this->privacyUrl, [
            'target' => '_blank',
            'rel' => 'noopener',
        ]);
        $cookiesLink = $this->cookiesUrl
            ? Html::a($this->cookiesLinkText, $this->cookiesUrl)
            : '';

        $text = $this->text;
        if ($text === null) {
            $text = 'На сайте стоят cookie. Нужные — чтобы открывались страницы и работали формы.'
                . ' Аналитические (Яндекс.Метрика) — чтобы понимать, как сайтом пользуются.'
                . ' Подробности — в {privacy}';
            $text .= $cookiesLink ? ' и в {cookies}.' : '.';
        }

        $text = strtr($text, [
            '{privacy}' => $privacyLink,
            '{cookies}' => $cookiesLink,
        ]);

        return $this->render('banner', [
            'text' => $text,
            'acceptLabel' => $this->acceptLabel,
            'rejectLabel' => $this->rejectLabel,
        ]);
    }
}
