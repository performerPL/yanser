<?php
require_once '_header.php';
require_once '../lib/gallery.php';
_sec_authorise(ACCESS_MIN_EDITOR);

$Error = array();

//$images = gallery_readdir();

$Message = '';

require_once 'tpl/list_gallery_images.html.php';
require_once '_footer.php';
