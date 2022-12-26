<?php
$svg_sprite = null;

if (file_exists(get_theme_file_path('dist/sprite.svg'))) {
    $svg_sprite = file_get_contents(get_theme_file_path('dist/sprite.svg'));
}
?>

<div id="svg_sprite" style="display: none;">
    <?= $svg_sprite ?>
</div>