<?php

/**
 * File icon for PDF files
 *
 * @param string $class CSS class to apply to the icon
 * @param string $fileType File type (e.g., 'pdf', 'docx', etc.)
 */
$class = $class ?? 'w-6 h-6';
$fileType = $fileType ?? 'PDF';
?>

<svg class="stroke-[8px] md:stroke-[6px] text-contrast <?= $class ?>" width="100%" height="100%" viewBox="0 0 500 500" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:1.5;">
    <g transform="matrix(1,0,0,1,-7960,-1578)">
    <g id="Artboard1" transform="matrix(0.920344,0,0,0.948939,633.396,80.4834)">
        <rect x="7960.73" y="1578.1" width="543.275" height="526.904" style="fill:none;" />
        <g transform="matrix(2.26361,0,0,1.83129,-18743,-1801.58)">
            <g transform="matrix(2.43952,0,0,2.92455,-5145.27,-7268.4)">
                <path d="M7019.31,3195.97L7019.31,3198.28C7019.31,3204.14 7014.56,3208.89 7008.7,3208.89L6961.83,3208.89C6955.97,3208.89 6951.22,3204.14 6951.22,3198.28L6951.22,3140.09L6968.94,3122.18L7008.7,3122.18C7014.56,3122.18 7019.31,3126.94 7019.31,3132.79L7019.31,3153.46" style="fill:none;stroke:currentColor" />
            </g>
            <g transform="matrix(3.20745,0,0,3.84517,-10539.8,-10204.8)">
                <g transform="matrix(21.5498,0,0,21.5498,7036.71,3188.53)">
                </g>
                <text x="6988.78px" y="3188.53px" style="fill:currentColor;font-size:21.55px;" class="font-style font-semibold">.<?= Str::upper($fileType) ?></text>
            </g>
        </g>
    </g>
    </g>
</svg>
