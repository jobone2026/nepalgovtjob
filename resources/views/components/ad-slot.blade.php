@if ($ad)
    <div class="bg-gray-100 p-4 text-center my-4">
        @if ($ad->type === 'custom' && $ad->code)
            {!! $ad->code !!}
        @elseif ($ad->type === 'adsense' && $ad->adsense_slot_ids)
            @php
                $slotIds = json_decode($ad->adsense_slot_ids, true);
                $slotId = $slotIds['desktop'] ?? $slotIds['mobile'] ?? null;
            @endphp
            @if ($slotId)
                <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-xxxxxxxxxxxxxxxx"
                    crossorigin="anonymous"></script>
                <ins class="adsbygoogle"
                    style="display:block"
                    data-ad-client="ca-pub-xxxxxxxxxxxxxxxx"
                    data-ad-slot="{{ $slotId }}"
                    data-ad-format="auto"
                    data-full-width-responsive="true"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            @endif
        @endif
    </div>
@endif
