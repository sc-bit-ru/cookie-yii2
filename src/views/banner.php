<?php

/** @var string $text уже с ссылками */
/** @var string $acceptLabel */
/** @var string $rejectLabel */
?>
<div id="cookieConsent" role="dialog" aria-label="Согласие на cookie" aria-live="polite">
    <div class="cookie-consent-inner">
        <p class="cookie-consent-text"><?= $text ?></p>
        <div class="cookie-consent-actions">
            <button type="button" class="cookie-consent-btn cookie-consent-btn-secondary" id="cookieConsentReject" data-cookie-consent="necessary"><?= htmlspecialchars($rejectLabel, ENT_QUOTES, 'UTF-8') ?></button>
            <button type="button" class="cookie-consent-btn cookie-consent-btn-primary" id="cookieConsentAccept" data-cookie-consent="all"><?= htmlspecialchars($acceptLabel, ENT_QUOTES, 'UTF-8') ?></button>
        </div>
    </div>
</div>
