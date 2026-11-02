@php
    // $i18n: ['share', 'on', 'email', 'subject', 'body'] — localized strings.
@endphp
<div class="share-links">
    <span class="share-links__label">{{ $i18n['share'] }}</span>
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($url) }}" target="_blank" rel="noopener" title="{{ $i18n['on'] }} Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
    <a href="https://twitter.com/intent/tweet?url={{ urlencode($url) }}" target="_blank" rel="noopener" title="{{ $i18n['on'] }} Twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
    <a href="https://www.pinterest.com/pin/create/button/?url={{ urlencode($url) }}" target="_blank" rel="noopener" title="{{ $i18n['on'] }} Pinterest"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
    <a href="https://wa.me/?text={{ urlencode($url) }}" target="_blank" rel="noopener" title="{{ $i18n['on'] }} WhatsApp"><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
    <a href="mailto:?subject={{ rawurlencode($i18n['subject']) }}&body={{ rawurlencode($i18n['body'] . $url) }}" title="{{ $i18n['email'] }}"><i class="fa fa-envelope" aria-hidden="true"></i></a>
</div>
