<?php

/** @var string $mode banner|wall */
/** @var string $text уже с ссылками */
/** @var string $deniedText */
/** @var string $acceptLabel */
/** @var string $rejectLabel */
/** @var string $rejectValue necessary|denied */

$isWall = $mode === 'wall';
?>
<div id="cookieConsent"
     class="<?= $isWall ? 'cookie-consent-wall' : 'cookie-consent-banner' ?>"
     data-cookie-consent-mode="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>"
     role="dialog"
     aria-modal="<?= $isWall ? 'true' : 'false' ?>"
     aria-label="Согласие на cookie"
     aria-live="polite">
    <div class="cookie-consent-inner cookie-consent-choice">
        <p class="cookie-consent-text"><?= $text ?></p>
        <div class="cookie-consent-actions">
            <button type="button" class="cookie-consent-btn cookie-consent-btn-secondary" id="cookieConsentReject" data-cookie-consent="<?= htmlspecialchars($rejectValue, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($rejectLabel, ENT_QUOTES, 'UTF-8') ?></button>
            <button type="button" class="cookie-consent-btn cookie-consent-btn-primary" id="cookieConsentAccept" data-cookie-consent="all"><?= htmlspecialchars($acceptLabel, ENT_QUOTES, 'UTF-8') ?></button>
        </div>
    </div>
    <?php if ($isWall): ?>
    <div class="cookie-consent-inner cookie-consent-denied" hidden>
        <p class="cookie-consent-text"><?= $deniedText ?></p>
        <div class="cookie-consent-actions">
            <button type="button" class="cookie-consent-btn cookie-consent-btn-primary" data-cookie-consent="all"><?= htmlspecialchars($acceptLabel, ENT_QUOTES, 'UTF-8') ?></button>
        </div>
    </div>
    <?php endif; ?>
</div>
