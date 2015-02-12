/*
 * CMS Performer
 * http://performer.pl/
 *
 * Copyright (c) 2013 Marcin Wojtkowiak
 */

function Pokaz(secNum)
{
	secNum = document.getElementById(secNum);
  if (secNum.style.display=="") {
    secNum.style.display="none";
  } else {
    secNum.style.display="";
  }
}