<!DOCTYPE html>
<html lang="pl">
  <head>
  ok
      <meta charset="utf-8">
      <title><?php echo ($Item->getMTitle() != '' ? $Item->getMTitle() : $Item->getLongName()); ?> </title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="<?php echo $Item->getMetaDescription(); ?>" />
      <meta name="keywords" content="<?php echo $Item->getMetaKeywords(); ?> " />
      <meta name="robots" content="index,follow" />
      <meta name="author" content=""/>
      <meta name="distribution" content="global" />
      <meta http-equiv="content-language" content="pl" />

<style title="currentstyle" type="text/css" media="all">@import url(css/css_cms.css); </style>
<style title="currentstyle" type="text/css" media="all">@import url(css/css_site.css); </style>
<style title="currentstyle" type="text/css" media="all">@import url(css/css_cms_rwd.css); </style>
<style title="currentstyle" type="text/css" media="all">@import url(css/jquery.lightbox-0.5.css); </style>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.lightbox-0.5.js"></script>
<script type="text/javascript" src="js/website.js"></script>

    <link href="NIE/assets/css/bootstrapTheme.css" rel="stylesheet">
    <link href="NIE/assets/css/custom.css" rel="stylesheet">

    <!-- Owl Carousel Assets -->
    <link href="../owl-carousel/owl.carousel.css" rel="stylesheet">
    <link href="../owl-carousel/owl.theme.css" rel="stylesheet">
    <link href="../owl-carousel/owl.transitions.css" rel="stylesheet">
    
      <script src="../owl-carousel/owl.carousel.js"></script>
      
<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,800italic,400,600,700,800,300&amp;subset=latin-ext,latin' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,6000&amp;subset=latin-ext,latin' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Kalam:400,700,300&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
      
<link href='http://fonts.googleapis.com/css?family=Playfair+Display:400,700,900,400italic&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Buenard:400,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
      
</head>
<body class="art_<?=$Item->getID();?>" >

<header>
<div class="inside">
   <div class="content">
	<!-- menu -->	
	<div class="logo"><a href="http://www.yanser.performer.pl"></a></div> 
	<nav class="menu">
			<?php $menu_1->printSimple($Item); ?>
	</nav>
	</div>
</div>
</header><!-- content END -->	
<? if($Item->getAddon(0) == 'kleo') { ?>
<section class="start_kleo">
   <div class="logo"></div>
   <div class="kleo_baza kleo_box"><a href="/4" title="Kleo kolekcja bazowa"><p class="btn1">kolekcja bazowa</p></a></div>
   <div class="kleo_sezon kleo_box"><a href="/24" title="Kleo kolekcja jesień-zima"><p class="btn1">jesień-zima 2014/2015</p></a></div>
</section>
<? } else { ?>
  <div id="TopBanner" class="TopBanner">
  <div class="banner banner1"><p><span>L'etude</span> <br/> bielizna w której można się zakochać.</p></div>
  <div class="banner banner2"><p><span>Kleo</span> <br/> bielizna modna i nowoczesna.</p></div>
  <div class="banner banner3"><p><span>L'etude</span> <br/> bielizna w której można się zakochać.</p></div>
  <div class="banner banner4"><p><span>Kleo</span> <br/> bielizna modna i nowoczesna.</p></div>
</div> 
<? } ?>



	
	
	
	
<article>		
      <div class="width_site width_h1"><div class="inside_content"><div class="box box-1-1">
		<div class="margin"><div class="inside">
		    <?php	echo '<h1>'.$Item->getLongName().'</h1>'; ?>
		</div></div>
		</div><div class="space"></div>
		</div></div>
		
		<?php $Item->getContent();	?>
</article>


<footer>
   <div class="width_site width_footer"><div class="inside_content">
         <div class="margin"><div class="inside">
               <div class="box box-1-4">  
               <p class="name">Formularze</p>
               <a href="http://www.yanser.performer.pl/9,0,kontakt-yanser-polska.html">Praca</a>
               <a href="http://www.yanser.performer.pl/9,0,kontakt-yanser-polska.html">Opinie klientów</a>
               </div> 
               <div class="box box-1-4">
               <p class="name">Katalogi</p>
               <a class="pdf" href="http://www.yanser.performer.pl/IncFiles/Legs_Yanser_katalog.pdf" target="_blank">Rajstopy Legs</a>
               <a  class="pdf" href="http://www.yanser.performer.pl/IncFiles/Steps_2015.pdf" target="_blank">Legs Steps</a>
               <a href="http://www.yanser.performer.pl/18,0,katalogi.html">więcej</a>
               </div> 
               <div class="box box-1-4">
               <p class="name">Kontakt</p>
               
               <p>Yanser PL Sp. z o.o.<br/>

               ul. Kopanina 54/56<br/>
               60-105 Poznań<br/>
               POLSKA </p>
               </div> 
               <div class="box box-1-4">
               <p class="name">Sekretariat</p>
               
               <p>
               Tel.: +48 61 661 62 35<br/>
               Kom.: +48 533 908 001
               e-mail: yanserpl@yanser.com</p>
               </div> 
               <p class="madeby">&copy 2014 made by <a href="http://www.performer.pl" title="strony www, multimedia, druk">performer.pl</a></p> 
               
         </div></div>   
   </div></div>  
</footer>

    <script src="../assets/js/bootstrap-collapse.js"></script>
    <script src="../assets/js/bootstrap-transition.js"></script>
    <script src="../assets/js/bootstrap-tab.js"></script>

    <script src="../assets/js/google-code-prettify/prettify.js"></script>
	  <script src="../assets/js/application.js"></script>
</body>
</html>

