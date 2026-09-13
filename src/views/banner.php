<?php

/** @var string $mode banner|overlay */
/** @var string $text уже с ссылками */
/** @var string $acceptLabel */
/** @var string $rejectLabel */

$isOverlay = $mode === 'overlay';
?>
<div id="cookieConsent"
     class="<?= $isOverlay ? 'cookie-consent-overlay' : 'cookie-consent-banner' ?>"
     data-cookie-consent-mode="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>"
     role="dialog"
     aria-modal="<?= $isOverlay ? 'true' : 'false' ?>"
     aria-label="Согласие на cookie"
     aria-live="polite">
    <div class="cookie-consent-inner">
        <p class="cookie-consent-text"><?= $text ?></p>
        <div class="cookie-consent-actions">
            <button type="button" class="cookie-consent-btn cookie-consent-btn-secondary" id="cookieConsentReject" data-cookie-consent="necessary"><?= htmlspecialchars($rejectLabel, ENT_QUOTES, 'UTF-8') ?></button>
            <button type="button" class="cookie-consent-btn cookie-consent-btn-primary" id="cookieConsentAccept" data-cookie-consent="all"><?= htmlspecialchars($acceptLabel, ENT_QUOTES, 'UTF-8') ?></button>
        </div>
    </div>
</div>
