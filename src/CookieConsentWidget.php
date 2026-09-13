<?php

namespace scbit\cookieconsent;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Плашка или стена согласия на cookie по 152-ФЗ.
 *
 * banner — сайт открыт, Метрика только после «Принять все».
 * wall — без «Принять все» экран закрыт. Слабее по ст. 9 152-ФЗ (согласие не вполне свободное).
 * Политика и /cookies в режиме wall остаются читаемыми.
 */
class CookieConsentWidget extends Widget
{
    const MODE_BANNER = 'banner';
    const MODE_WALL = 'wall';

    /** @var string banner|wall */
    public $mode = self::MODE_BANNER;

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
     * Если null — стандартная формулировка для выбранного режима.
     * @var string|null
     */
    public $text;

    /** @var string|null Текст после отказа в режиме wall */
    public $deniedText;

    /** @var string */
    public $acceptLabel = 'Принять все';

    /** @var string|null В wall по умолчанию «Отказаться» */
    public $rejectLabel;

    /**
     * Пути, которые стена не закрывает (чтобы прочитать условия до согласия).
     * Примеры: cookies, site/privacy, politika.pdf
     * @var string[]
     */
    public $freePaths = [];

    public function run()
    {
        CookieConsentAsset::register($this->view);

        $mode = $this->resolveMode();
        $isWall = $mode === self::MODE_WALL;

        $privacyLink = Html::a($this->privacyLinkText, $this->privacyUrl, [
            'target' => '_blank',
            'rel' => 'noopener',
        ]);
        $cookiesLink = $this->cookiesUrl
            ? Html::a($this->cookiesLinkText, $this->cookiesUrl)
            : '';

        $rejectLabel = $this->rejectLabel;
        if ($rejectLabel === null || $rejectLabel === '') {
            $rejectLabel = $isWall ? 'Отказаться' : 'Только нужные';
        }

        $text = $this->text;
        if ($text === null) {
            if ($isWall) {
                $text = 'Чтобы открыть сайт, нужно согласие на cookie, в том числе аналитические (Яндекс.Метрика).'
                    . ' Без согласия страницы не показываем. Подробности — в {privacy}';
                $text .= $cookiesLink ? ' и в {cookies}.' : '.';
            } else {
                $text = 'На сайте стоят cookie. Нужные — чтобы открывались страницы и работали формы.'
                    . ' Аналитические (Яндекс.Метрика) — чтобы понимать, как сайтом пользуются.'
                    . ' Подробности — в {privacy}';
                $text .= $cookiesLink ? ' и в {cookies}.' : '.';
            }
        }

        $deniedText = $this->deniedText;
        if ($deniedText === null) {
            $deniedText = 'Вы отказались. Без согласия на cookie сайт не показываем. Можно принять все или закрыть вкладку.';
        }

        $text = strtr($text, [
            '{privacy}' => $privacyLink,
            '{cookies}' => $cookiesLink,
        ]);
        $deniedText = strtr($deniedText, [
            '{privacy}' => $privacyLink,
            '{cookies}' => $cookiesLink,
        ]);

        return $this->render('banner', [
            'mode' => $mode,
            'text' => $text,
            'deniedText' => $deniedText,
            'acceptLabel' => $this->acceptLabel,
            'rejectLabel' => $rejectLabel,
            'rejectValue' => $isWall ? 'denied' : 'necessary',
        ]);
    }

    /**
     * @return string
     */
    protected function resolveMode()
    {
        $mode = $this->mode === self::MODE_WALL ? self::MODE_WALL : self::MODE_BANNER;
        if ($mode === self::MODE_WALL && $this->isFreePage()) {
            return self::MODE_BANNER;
        }

        return $mode;
    }

    /**
     * Политика и описание cookie должны читаться до кнопки «Принять все».
     *
     * @return bool
     */
    protected function isFreePage()
    {
        $path = trim((string) Yii::$app->request->pathInfo, '/');
        $urlPath = trim((string) parse_url(Yii::$app->request->url, PHP_URL_PATH), '/');

        $free = array_merge(
            ['cookies', 'site/cookies', 'privacy', 'site/privacy'],
            $this->freePaths
        );
        if ($this->cookiesUrl) {
            $free[] = $this->urlToPath($this->cookiesUrl);
        }
        if ($this->privacyUrl) {
            $free[] = $this->urlToPath($this->privacyUrl);
        }

        foreach ($free as $item) {
            $item = trim((string) $item, '/');
            if ($item === '') {
                continue;
            }
            if (strcasecmp($path, $item) === 0 || strcasecmp($urlPath, $item) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string|array $url
     * @return string
     */
    protected function urlToPath($url)
    {
        try {
            $resolved = Url::to($url);
        } catch (\Exception $e) {
            return '';
        }

        return trim((string) parse_url($resolved, PHP_URL_PATH), '/');
    }
}
