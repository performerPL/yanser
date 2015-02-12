<?php
function smarty_modifier_rewrite($string)
{
  return vConvert::toAscii($string);
}
