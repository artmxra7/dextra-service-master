<?php

if (! function_exists('base64ToPng') ) {
    function base64ToPng ($base64, $title, $dir) {
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64));
        file_put_contents('storage' . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $title . '.png', $data);
        return $title . '.png';
    }
}
