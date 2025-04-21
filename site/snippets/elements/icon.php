<?php

$icon = $icon ?? 'arrow-right';
$class = $class ?? '';
?>

<svg class="icon fill-current <?= $class ?>" viewBox="0 0 32 32">
    <use xlink:href="#<?= $icon ?>"></use>
</svg>
