<?php if($ad): ?>
    <div class="bg-gray-100 p-4 text-center my-4">
        <?php if($ad->type === 'custom' && $ad->code): ?>
            <?php echo $ad->code; ?>

        <?php elseif($ad->type === 'adsense' && $ad->adsense_slot_ids): ?>
            <?php
                $slotIds = json_decode($ad->adsense_slot_ids, true);
                $slotId = $slotIds['desktop'] ?? $slotIds['mobile'] ?? null;
            ?>
            <?php if($slotId): ?>
                <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-xxxxxxxxxxxxxxxx"
                    crossorigin="anonymous"></script>
                <ins class="adsbygoogle"
                    style="display:block"
                    data-ad-client="ca-pub-xxxxxxxxxxxxxxxx"
                    data-ad-slot="<?php echo e($slotId); ?>"
                    data-ad-format="auto"
                    data-full-width-responsive="true"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            <?php endif; ?>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\job\loksewaalert-portal\resources\views/components/ad-slot.blade.php ENDPATH**/ ?>