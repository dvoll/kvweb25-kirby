<?php
/**
 * Reusable close button for event dialog
 */
?>
<button autofocus
        class="self-end btn btn--ghost hover:bg-tertiary hover:text-baseline"
        @click="closeModal()">
    Schließen<?= snippet('elements/icon', ['icon' => 'external']) ?>
</button>
