<?php
require_once('_header.php');
require_once('class/CodeBlock.class.php');
_sec_authorise(ACCESS_MIN_EDITOR);

$codeBlock = new CodeBlock();

$Message = '';

$Tab = $codeBlock->getList();
$Stats =  array(
	$T['form_count'] => count($Tab)
);
require_once('tpl/header.html.php');
require_once('tpl/index_code_blocks.html.php');
require_once('tpl/footer.html.php');
require_once('_footer.php');
