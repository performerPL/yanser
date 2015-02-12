<?php



//if(defined('mod_text.class')) die('aa');
define('mod_gallery.class', 1);

require_once 'module/Bean.class.php';

class mod_gallery extends Mod_Bean
{
	/* typ modułu */
	private $moduleType = 2;


	function update($tab)
	{
		return _db_replace('mod_gallery', array(
            'module_id' => _db_int($tab['module_id']),
            'gallery_id' => _db_int($tab['gallery_id']),
            'show_title' => _db_int($tab['show_title']),
            'show_description' => _db_int($tab['show_description']),
            'show_gallery_description' => _db_int($tab['show_gallery_description']),
            'show_target_url' => _db_int($tab['show_target_url']),
            'show_enlarge' => _db_int($tab['show_enlarge']),
            'show_enlarge_lightbox' => _db_int($tab['show_enlarge_lightbox']),
            'show_pictures_counter' => _db_int($tab['show_pictures_counter']),
            'image_type' => _db_string($tab['image_type']),
		    'show_gallery_name' => _db_int($tab['show_gallery_name']),
		));
	}

	function remove($id)
	{
		return _db_delete('mod_gallery', 'module_id=' . intval($id), 1);
	}

	function validate($tab, $T)
	{
		return $tab['gallery_id'] > 0;
	}

	function get($id)
	{
		return _db_get_one('SELECT * FROM `'.DB_PREFIX.'mod_gallery` WHERE module_id='.intval($id).' LIMIT 1');
	}

	function front($module, $Item)
	{
		// uaktualnia dane o module (dla ustawień ręcznych)
		$module = $this->getModuleContent($module['module_id'],$module);
		
		// pobiera dane modułu z bazy
		$data = $this->get($module['module_id']);
		if (!$data) {
			return;
		}
		$moduleID = $module['module_id'];
		// pobiera styl
		$style = $module['module_style'];

		// pobiera dane galerii
		$gallery = gallery_get($data['gallery_id']);
		if (!$gallery) {
			return;
		}
		$voting = $gallery['show_voting'];

		// pobiera liste zdjec
		$pictures = gallery_images_list($data['gallery_id']);

		/* tytuł modułu
		 if ($module['show_module_title']) {
		 echo '<div class="">' . $module['module_name'] . '</div>';
		 }
		 */
    if ($module['show_module_title']) {
      echo '<div class="mod_gallery_name mod_name">' . $module['module_name'] . '</div>';
    }
		// nazwa galerii
		if ($module['show_gallery_name']) {
			echo '<div class="mod_gallery_name">' . $gallery['gallery_name'] . '</div>';
		}



		// opis galerii
		if ($data['show_gallery_description']) {
			echo '<div class="mod_gallery_desc">' . $gallery['gallery_description'] . '</div>';
		}

		// sprawdza czy istnieja obrazki


		//styl 0 = 'zdjęcie po lewej - opis po prawej';
		//styl 1 = 'zdjęcie po prawej - opis po lewej';
		//styl 2 = 'zdjecia w 2 kolumnach';
		//styl 3 = 'zdjęcia w 3 kolumnach';
		//styl 4 = 'Zdjęcia w 4 kolumnach';
		//styl 5 = 'Zdjęcia w wierszu (zawijane) foto+tytuł';
		//styl 6 = 'przewijanie miniatur';
      $scroll = $data[show_enlarge];
      $size =  $data[image_type];
      if ($size == 'gallery/big/') {
         $size = '1';
      } else if ($size == 'gallery/middle/') {
         $size = '2';
      } else  {
         $size = '3';
      } 
            
		if (count($pictures) > 0) {
			global $GL_CONF;
			$cfg = $GL_CONF["IMAGES_FILES"];
			switch ($style) {
				case 0: //jedno pod drugim
				$galleryID = $gallery['gallery_id'];
				   echo '<div class="width_site width_'.$galleryID.'_'.$moduleID.'"><div class="inside_content">';
				   echo '<div class="mod_gallery_scroll_1 mod_gallery_scroll">';
					echo '<ul class="mod_gallery mod_gallery_1 gallery_'.$galleryID.'_'.$moduleID.'" id="gallery_'.$galleryID.'_'.$moduleID.'">';

					foreach ($pictures as $picture) {
						echo '<li class="gallery_0 box box-1-1"><div class="margin"><div class="inside">';
						$this->getImageHtml($data,$picture,$cfg,$galleryID,$moduleID);
						echo '</div></div></li>';
					}
					echo '</ul><div class="space"></div>';
					echo '</div></div></div>';
					break;
					
				case 1:  // 2 kolumny
				
				$galleryID = $gallery['gallery_id'];
				   echo '<div class="width_site width_'.$galleryID.'_'.$moduleID.'"><div class="inside_content">';
				   echo '<div class="mod_gallery_scroll_2 mod_gallery_scroll">';
					echo '<ul class="mod_gallery mod_gallery_1 gallery_'.$galleryID.'_'.$moduleID.'" id="gallery_'.$galleryID.'_'.$moduleID.'">';

					foreach ($pictures as $picture) {
						echo '<li class="gallery_0 box '.($scroll == 0?' box-1-2 ':' box-1-1 ').'"><div class="margin"><div class="inside">';
						$this->getImageHtml($data,$picture,$cfg,$galleryID,$moduleID);
						echo '</div></div></li>';
					}
					echo '</ul><div class="space"></div>';
					echo '</div></div></div>';
					if (!empty($data[show_enlarge])) {
				  ?>
               <script>
               jQuery(document).ready(function($) {

               	$('#<? echo 'gallery_'.$galleryID.'_'.$moduleID; ?>').owlCarousel({
              <?php if ($data['show_pictures_counter'] == 1) {
                 echo 'autoPlay: 4000,';
               }
               ?>
	                  autoplayTimeout:2000,
               		center: false,
               		items: 2,
    
                        itemsDesktop : [1000,2], //5 items between 1000px and 901px
                        itemsDesktopSmall : [720,2], // betweem 900px and 601px
                        itemsTablet: [480,1], //2 items between 600 and 0
                        itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option

               		loop:true,
               navigation : true,
               	});

               });

               </script>
				  <?
				  }
					break;


				case 2:  //w 3 kolumnach
				$galleryID = $gallery['gallery_id'];
				   echo '<div class="width_site width_'.$galleryID.'_'.$moduleID.'"><div class="inside_content">';
				   echo '<div class="mod_gallery_scroll_3 mod_gallery_scroll">';
					echo '<ul class="mod_gallery mod_gallery_2 gallery_'.$galleryID.'_'.$moduleID.'" id="gallery_'.$galleryID.'_'.$moduleID.'">';

					foreach ($pictures as $picture) {
						echo '<li class="gallery_0 box '.($scroll == 0?' box-1-3 ':' box-1-1 ').'"><div class="margin"><div class="inside">';
						$this->getImageHtml($data,$picture,$cfg,$galleryID,$moduleID);
						echo '</div></div></li>';
					}
					echo '</ul><div class="space"></div>';
					echo '</div></div>';
					if (!empty($data[show_enlarge])) {
				  ?>
               <script>
               jQuery(document).ready(function($) {

               	$('#<? echo 'gallery_'.$galleryID.'_'.$moduleID; ?>').owlCarousel({
              <?php if ($data['show_pictures_counter'] == 1) {
                 echo 'autoPlay: 4000,';
               }
               ?>
	                  autoplayTimeout:2000,
               		center: false,
               		items: 3,
               		
                        itemsDesktop : [1000,2], //5 items between 1000px and 901px
                        itemsDesktopSmall : [720,2], // betweem 900px and 601px
                        itemsTablet: [480,1], //2 items between 600 and 0
                        itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option

               		
               		loop:true,
               navigation : true,
               	});

               });

               </script>
				  <?
				  }
					break;
					
				case 3:  // 4 kolumny
				$galleryID = $gallery['gallery_id'];
				   echo '<div class="width_site width_'.$galleryID.'_'.$moduleID.'"><div class="inside_content">';
				   echo '<div class="mod_gallery_scroll_4 mod_gallery_scroll">';
					echo '<ul class="mod_gallery mod_gallery_3 gallery_'.$galleryID.'_'.$moduleID.'" id="gallery_'.$galleryID.'_'.$moduleID.'">';

					foreach ($pictures as $picture) {
						echo '<li class="gallery_0 box '.($scroll == 0?' box-1-4 ':' box-1-1 ').'"><div class="margin"><div class="inside">';
						$this->getImageHtml($data,$picture,$cfg,$galleryID,$moduleID);
						echo '</div></div></li>';
					}
					echo '</ul><div class="space"></div>';
					echo '</div></div>';
					if (!empty($data[show_enlarge])) {
				  ?>
               <script>
               jQuery(document).ready(function($) {

               	$('#<? echo 'gallery_'.$galleryID.'_'.$moduleID; ?>').owlCarousel({
              <?php if ($data['show_pictures_counter'] == 1) {
                 echo 'autoPlay: 4000,';
               }
               ?>
	                  autoplayTimeout:2000,
               		center: false,
               		items: 4,
               		
                        itemsDesktop : [1000,2], //5 items between 1000px and 901px
                        itemsDesktopSmall : [720,2], // betweem 900px and 601px
                        itemsTablet: [480,1], //2 items between 600 and 0
                        itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option

               		
               		loop:true,
               navigation : true,
               	});

               });

               </script>
				  <?
				  }
					break;

					
				case 4:  // w 6 kolumnach
				$galleryID = $gallery['gallery_id'];
				   echo '<div class="width_site width_'.$galleryID.'_'.$moduleID.'"><div class="inside_content">';
				   echo '<div class="mod_gallery_scroll_6 mod_gallery_scroll">';
					echo '<ul class="mod_gallery mod_gallery_4 gallery_'.$galleryID.'_'.$moduleID.'" id="gallery_'.$galleryID.'_'.$moduleID.'">';
	
					foreach ($pictures as $picture) {
						echo '<li class="gallery_0 box '.($scroll == 0?' box-1-6 ':' box-1-1 ').'"><div class="margin"><div class="inside">';
						$this->getImageHtml($data,$picture,$cfg,$galleryID,$moduleID);
						echo '</div></div></li>';
					}
					echo '</ul><div class="space"></div>';
					echo '</div></div>';
					if (!empty($data[show_enlarge])) {
				  ?>
               <script>
               jQuery(document).ready(function($) {

               	$('#<? echo 'gallery_'.$galleryID.'_'.$moduleID; ?>').owlCarousel({
              <?php if ($data['show_pictures_counter'] == 1) {
                 echo 'autoPlay: 4000,';
               }
               ?>
	                  autoplayTimeout:2000,
               		center: false,
               		items: 6,
               		
                        itemsDesktop : [1000,2], //5 items between 1000px and 901px
                        itemsDesktopSmall : [720,2], // betweem 900px and 601px
                        itemsTablet: [480,1], //2 items between 600 and 0
                        itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option

               		
               		loop:true,
               navigation : true,
               	});

               });

               </script>
				  <?
				  }
					break;

				case 5: // w 8 kolumnach
				$galleryID = $gallery['gallery_id'];
				   echo '<div class="width_site width_'.$galleryID.'_'.$moduleID.'"><div class="inside_content">';
				   echo '<div class="mod_gallery_scroll_8 mod_gallery_scroll">';
					echo '<ul class="mod_gallery mod_gallery_5 gallery_'.$galleryID.'_'.$moduleID.'" id="gallery_'.$galleryID.'_'.$moduleID.'">';

					foreach ($pictures as $picture) {
						echo '<li class="gallery_0 box '.($scroll == 0?' box-1-8 ':' box-1-1 ').'"><div class="margin"><div class="inside">';
						$this->getImageHtml($data,$picture,$cfg,$galleryID,$moduleID);
						echo '</div></div></li>';
					}
					echo '</ul><div class="space"></div>';
					echo '</div></div>';
					if (!empty($data[show_enlarge])) {
				  ?>
               <script>
               jQuery(document).ready(function($) {

               	$('#<? echo 'gallery_'.$galleryID.'_'.$moduleID; ?>').owlCarousel({
              <?php if ($data['show_pictures_counter'] == 1) {
                 echo 'autoPlay: 4000,';
               }
               ?>
	                  autoplayTimeout:2000,
               		center: false,
               		items: 8,
               		
                        itemsDesktop : [1000,2], //5 items between 1000px and 901px
                        itemsDesktopSmall : [720,2], // betweem 900px and 601px
                        itemsTablet: [480,1], //2 items between 600 and 0
                        itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option

               		
               		loop:true,
               navigation : true,
               	});

               });

               </script>
				  <?
				  }
					break;

					
					
					//styl 6 = 1/4 strony';
				case 6:
				$galleryID = $gallery['gallery_id'];
				   //echo '<div class="width_site width_'.$galleryID.'_'.$moduleID.'"><div class="inside_content">';
				   echo '<div class="mod_gallery_scroll_1_4 mod_gallery_scroll">';
					echo '<ul class="mod_gallery mod_gallery_6 gallery_'.$galleryID.'_'.$moduleID.'" id="gallery_'.$galleryID.'_'.$moduleID.'">';

					foreach ($pictures as $picture) {
						echo '<li class="gallery_0 box '.($scroll == 0?' box-1-1 ':' box-1-1 ').'"><div class="margin"><div class="inside">';
						$this->getImageHtml($data,$picture,$cfg,$galleryID,$moduleID);
						echo '</div></div></li>';
					}
					echo '</ul>';
					echo '</div>';
					if (!empty($data[show_enlarge])) {
					if ($size==1) {
                  $size = 2;
					}
				  ?>
               <script>
               jQuery(document).ready(function($) {
               	$('#<? echo 'gallery_'.$galleryID.'_'.$moduleID; ?>').owlCarousel({
              <?php if ($data['show_pictures_counter'] == 1) {
                 echo 'autoPlay: 4000,';
               }
               ?>
	                  autoplayTimeout:2000,
               		center: false,
               		items: <?=$size-1;?>,
               		
        <? if ($size > 1) { ?>
           itemsDesktop : [1000,2], //5 items between 1000px and 901px
           itemsDesktopSmall : [720,2], // betweem 900px and 601px
           itemsTablet: [480,1], //2 items between 600 and 0
           itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option
        <? } ?>
               		
               		loop:true,
               navigation : true,
               	});
               });
               </script>
				  <?
				  }
					break;
	
					//styl7 = 1/3 strony';
				case 7:
					$galleryID = $gallery['gallery_id'];
					//echo '<div class="width_site width_'.$galleryID.'_'.$moduleID.'"><div class="inside_content">';
				   echo '<div class="mod_gallery_scroll_1_3 mod_gallery_scroll">';
					echo '<ul class="mod_gallery mod_gallery_7 gallery_'.$galleryID.'_'.$moduleID.'" id="gallery_'.$galleryID.'_'.$moduleID.'">';
					// pokazuj licznik zdjęc w galerii
					if(!empty($data[show_pictures_counter])) {
						echo 'ilość zdjęć: <b>'.count($pictures).'</b>';
					}
					foreach ($pictures as $picture) {
						echo '<li class="gallery_0 box '.($scroll == 0?' box-1-1 ':' box-1-1 ').'"><div class="margin"><div class="inside">';
						$this->getImageHtml($data,$picture,$cfg,$galleryID,$moduleID);
						echo '</div></div></li>';
					}
					echo '</ul>';
					echo '</div>';
					if (!empty($data[show_enlarge])) {
					if ($size == 1) {
                  $size = 2;
					}
				  ?>
               <script>
               jQuery(document).ready(function($) {
               	$('#<? echo 'gallery_'.$galleryID.'_'.$moduleID; ?>').owlCarousel({
              <?php if ($data['show_pictures_counter'] == 1) {
                 echo 'autoPlay: 4000,';
               }
               ?>
	                  autoplayTimeout:2000,
               		center: false,
               		items: <?=$size-1;?>,
        <? if ($size > 1) { ?>
           itemsDesktop : [1000,2], //5 items between 1000px and 901px
           itemsDesktopSmall : [720,2], // betweem 900px and 601px
           itemsTablet: [480,1], //2 items between 600 and 0
           itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option
        <? } ?>
               		
               		loop:true,
               navigation : true,
               	});
               });
               </script>
				  <?
				  }
					break;
					
					
					
					//styl 8 = 1/2 strony';
				case 8:
				$galleryID = $gallery['gallery_id'];
				  //echo '<div class="width_site width_'.$galleryID.'_'.$moduleID.'"><div class="inside_content">';
				   echo '<div class="mod_gallery_scroll_1_2 mod_gallery_scroll">';
					echo '<ul class="mod_gallery mod_gallery_8 gallery_'.$galleryID.'_'.$moduleID.'" id="gallery_'.$galleryID.'_'.$moduleID.'">';
					// pokazuj licznik zdjęc w galerii
					if(!empty($data[show_pictures_counter])) {
						echo 'ilość zdjęć: <b>'.count($pictures).'</b>';
					}
					foreach ($pictures as $picture) {
						echo '<li class="gallery_0 box '.($scroll == 0?' box-1-2 ':' box-1-1 ').'"><div class="margin"><div class="inside">';
						$this->getImageHtml($data,$picture,$cfg,$galleryID,$moduleID);
						echo '</div></div></li>';
					}
					echo '</ul>';
					echo '</div>';
					if (!empty($data[show_enlarge])) {
				  ?>
               <script>
               jQuery(document).ready(function($) {
               	$('#<? echo 'gallery_'.$galleryID.'_'.$moduleID; ?>').owlCarousel({
              <?php if ($data['show_pictures_counter'] == 1) {
                 echo 'autoPlay: 4000,';
               }
               ?>
	                  autoplayTimeout:2000,
               		center: false,
               		items: <?=$size;?>,
        <? if ($size > 1) { ?>
           itemsDesktop : [1000,2], //5 items between 1000px and 901px
           itemsDesktopSmall : [720,2], // betweem 900px and 601px
           itemsTablet: [480,1], //2 items between 600 and 0
           itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option
        <? } ?>
               		
               		loop:true,
               navigation : true,
               	});
               });
               </script>
				  <?
				  }
					break;
					
					
					
					//styl 9 = 2/3 strony';
				case 9:

					$galleryID = $gallery['gallery_id'];
					//echo '<div class="width_site width_'.$galleryID.'_'.$moduleID.'"><div class="inside_content">';
				   echo '<div class="mod_gallery_scroll_2_3 mod_gallery_scroll">';
					echo '<ul class="mod_gallery mod_gallery_9 gallery_'.$galleryID.'_'.$moduleID.'" id="gallery_'.$galleryID.'_'.$moduleID.'">';
					// pokazuj licznik zdjęc w galerii
					if(!empty($data[show_pictures_counter])) {
						echo 'ilość zdjęć: <b>'.count($pictures).'</b>';
					}

					foreach ($pictures as $picture) {
						echo '<li class="gallery_0 box '.($scroll == 0?' box-1-3 ':' box-1-1 ').'"><div class="margin"><div class="inside">';
						$this->getImageHtml($data,$picture,$cfg,$galleryID,$moduleID);
						echo '</div></div></li>';
					}
					echo '</ul>';
					echo '</div>';
					if (!empty($data[show_enlarge])) { //czy ma byc z przewijaniem
				  ?>

               
               <script>
               $(document).ready(function() {
                 $("#<? echo 'gallery_'.$galleryID.'_'.$moduleID; ?>").owlCarousel({
              <?php if ($data['show_pictures_counter'] == 1) {
                 echo 'autoPlay: 4000,';
               }
               ?>
                   
                   
        <? if ($size == 1) { ?>
            singleItem : true,
        <?  } else  { ?>
           items : <?=$size;?>, 
           itemsDesktop : [1000,2], //5 items between 1000px and 901px
           itemsDesktopSmall : [720,2], // betweem 900px and 601px
           itemsTablet: [480,1], //2 items between 600 and 0
           itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option
        <? } ?>
                   
                   center: false,
                   autoplay:true,
           	     autoplayTimeout:3000,
                   loop:true,
               navigation : true,
                   
                 });

               });
               </script>
               
				  <?
				  }
					break;
					

					//styl 10 = 3/4 strony';
				case 10:
					$galleryID = $gallery['gallery_id'];
					//echo '<div class="width_site width_'.$galleryID.'_'.$moduleID.'"><div class="inside_content">';
				   echo '<div class="mod_gallery_scroll_3_4 mod_gallery_scroll">';
					echo '<ul class="mod_gallery mod_gallery_0 gallery_'.$galleryID.'_'.$moduleID.'" id="gallery_'.$galleryID.'_'.$moduleID.'">';
					// pokazuj licznik zdjęc w galerii
					if(!empty($data[show_pictures_counter])) {
						echo 'ilość zdjęć: <b>'.count($pictures).'</b>';
					}

					foreach ($pictures as $picture) {
						echo '<li class="gallery_0 box '.($scroll == 0?' box-1-3 ':' box-1-1 ').'"><div class="margin"><div class="inside">';
						$this->getImageHtml($data,$picture,$cfg,$galleryID,$moduleID);
						echo '</div></div></li>';
					}
					echo '</ul>';
					echo '</div>';
					if (!empty($data[show_enlarge])) {
				  ?>
               <script>
               jQuery(document).ready(function($) {
               	$('#<? echo 'gallery_'.$galleryID.'_'.$moduleID; ?>').owlCarousel({
              <?php if ($data['show_pictures_counter'] == 1) {
                 echo 'autoPlay: 4000,';
               }
               ?>
	                  autoplayTimeout:2000,
               		center: false,
               		items: <?=$size;?>,
               navigation : true,
        <? if ($size > 1) { ?>
           itemsDesktop : [1000,2], //5 items between 1000px and 901px
           itemsDesktopSmall : [720,2], // betweem 900px and 601px
           itemsTablet: [480,1], //2 items between 600 and 0
           itemsMobile : false, // itemsMobile disabled - inherit from itemsTablet option
        <? } ?>
               		
               		loop:true,
               		nav:true,
               	});
               });
               </script>
				  <?
				  }
					break;
					

				//styl 11 = 'Slider 1;
				case 11:
	           $galleryID = $gallery['gallery_id'];
				   echo '<div class="width_site width_'.$galleryID.'_'.$moduleID.'"><div class="inside_content">';
				   echo '<div class="mod_gallery_banner_'.$size.' mod_gallery_banner">';
					echo '<ul class="mod_gallery mod_gallery_11 gallery_'.$galleryID.'_'.$moduleID.'" id="gallery_'.$galleryID.'_'.$moduleID.'">';
					foreach ($pictures as $picture) {
						echo '<li class="gallery_0 box  box-1-1 "><div class="margin"><div class="inside">';
						$this->getImageHtml($data,$picture,$cfg,$galleryID,$moduleID);
						echo '</div></div></li>';
					}				
					echo '</ul>';
					echo '<a class="btn prev"><</a><a class="btn next">></a><a class="btn play">Autoplay</a><a class="btn stop">Stop</a>';
					echo '</div><div class="space"></div></div></div>';
				  ?>
               <script>
               $(document).ready(function() {
                     var owl = $("#<? echo 'gallery_'.$galleryID.'_'.$moduleID; ?>");
                     owl.owlCarousel({
                           //transitionStyle : "fade",
                           navigation : false,
                           singleItem : true,
                           <?php if ($data['show_pictures_counter'] == 1) { echo 'autoPlay: 4000,'; } ?>
                     });
                     // Custom Navigation Events
                     $(".next").click(function(){ owl.trigger('owl.next'); })
                     $(".prev").click(function(){ owl.trigger('owl.prev'); })
                     $(".play").click(function(){ owl.trigger('owl.play',1000); })  //owl.play event accept autoPlay speed as second parameter
                     $(".stop").click(function(){ owl.trigger('owl.stop'); })
                })
               </script>
				  <?

					break;
				
				
				//styl 12 = 'Slider 2 round carusel - obrotowy slider';
				case 12:
	           $galleryID = $gallery['gallery_id'];
				   echo '<div class="width_site width_'.$galleryID.'_'.$moduleID.'"><div class="inside_content">';
				   echo '<div class="mod_gallery_banner_'.$size.' mod_gallery_banner">';
					echo '<ul class="mod_gallery mod_gallery_12 gallery_'.$galleryID.'_'.$moduleID.'" id="gallery_'.$galleryID.'_'.$moduleID.'">';
					foreach ($pictures as $picture) {
						echo '<li class="gallery_0 box  box-1-1 "><div class="margin"><div class="inside">';
						$this->getImageHtml($data,$picture,$cfg,$galleryID,$moduleID);
						echo '</div></div></li>';
					}				
					echo '</ul>';
					echo '<a class="btn prev"><</a><a class="btn next">></a><a class="btn play">Autoplay</a><a class="btn stop">Stop</a>';
					echo '</div><div class="space"></div></div></div>';
				  ?>
               <script>
               $(document).ready(function() {
                     var owl = $("#<? echo 'gallery_'.$galleryID.'_'.$moduleID; ?>");
                     owl.owlCarousel({
                           transitionStyle : "backSlide",
                           navigation : false,
                           singleItem : true,
                           <?php if ($data['show_pictures_counter'] == 1) { echo 'autoPlay: 4000,'; } ?>
                     });
                     // Custom Navigation Events
                     $(".next").click(function(){ owl.trigger('owl.next'); })
                     $(".prev").click(function(){ owl.trigger('owl.prev'); })
                     $(".play").click(function(){ owl.trigger('owl.play',1000); })  //owl.play event accept autoPlay speed as second parameter
                     $(".stop").click(function(){ owl.trigger('owl.stop'); })
                })
               </script>
				  <?
					break;
				
				
				//styl 13 = 'Slider 3 przewijanie z miniaturkami pod bannererm';
				case 13:
	           $galleryID = $gallery['gallery_id'];
				   echo '<div class="width_site width_'.$galleryID.'_'.$moduleID.'"><div class="inside_content">';
				   echo '<div class="mod_gallery_banner_'.$size.' mod_gallery_banner">';
					echo '<ul class="mod_gallery mod_gallery_13 gallery_'.$galleryID.'_'.$moduleID.'" id="gallery_'.$galleryID.'_'.$moduleID.'">';
					foreach ($pictures as $picture) {
						echo '<li class="gallery_0 box  box-1-1 "><div class="margin"><div class="inside">';
						$this->getImageHtml($data,$picture,$cfg,$galleryID,$moduleID);
						echo '</div></div></li>';
					}				
					echo '</ul>';
					echo '<a class="btn prev"><</a><a class="btn next">></a><a class="btn play">Autoplay</a><a class="btn stop">Stop</a>';
					echo '</div><div class="space"></div></div></div>';
				  ?>
               <script>
               $(document).ready(function() {
                     var owl = $("#<? echo 'gallery_'.$galleryID.'_'.$moduleID; ?>");
                     owl.owlCarousel({
                           transitionStyle : "goDown",
                           navigation : false,
                           singleItem : true,
                           <?php if ($data['show_pictures_counter'] == 1) { echo 'autoPlay: 4000,'; } ?>
                     });
                     // Custom Navigation Events
                     $(".next").click(function(){ owl.trigger('owl.next'); })
                     $(".prev").click(function(){ owl.trigger('owl.prev'); })
                     $(".play").click(function(){ owl.trigger('owl.play',1000); })  //owl.play event accept autoPlay speed as second parameter
                     $(".stop").click(function(){ owl.trigger('owl.stop'); })
                })
               </script>
				  <?
					break;
				
				
				
				//styl 14 = 'Slider 1 przewijanie z miniaturkami pod bannererm';
				case 14:
	           $galleryID = $gallery['gallery_id'];
				   echo '<div class="width_site width_'.$galleryID.'_'.$moduleID.'"><div class="inside_content">';
				   echo '<div class="mod_gallery_banner_'.$size.' mod_gallery_banner">';
					echo '<ul class="mod_gallery mod_gallery_14 gallery_'.$galleryID.'_'.$moduleID.'" id="gallery_'.$galleryID.'_'.$moduleID.'">';
					foreach ($pictures as $picture) {
						echo '<li class="gallery_0 box  box-1-1 "><div class="margin"><div class="inside">';
						$this->getImageHtml($data,$picture,$cfg,$galleryID,$moduleID);
						echo '</div></div></li>';
					}				
					echo '</ul>';
					echo '<a class="btn prev"><</a><a class="btn next">></a><a class="btn play">Autoplay</a><a class="btn stop">Stop</a>';
					echo '</div><div class="space"></div></div></div>';
				  ?>
               <script>
               $(document).ready(function() {
                     var owl = $("#<? echo 'gallery_'.$galleryID.'_'.$moduleID; ?>");
                     owl.owlCarousel({
                           transitionStyle : "fade",
                           navigation : false,
                           singleItem : true,
                           <?php if ($data['show_pictures_counter'] == 1) { echo 'autoPlay: 4000,'; } ?>
                     });
                     // Custom Navigation Events
                     $(".next").click(function(){ owl.trigger('owl.next'); })
                     $(".prev").click(function(){ owl.trigger('owl.prev'); })
                     $(".play").click(function(){ owl.trigger('owl.play',1000); })  //owl.play event accept autoPlay speed as second parameter
                     $(".stop").click(function(){ owl.trigger('owl.stop'); })
                })
               </script>
				  <?
					break;
				
				
				//styl 15 = 'Slider 6 przewijanie z miniaturkami pod bannererm';
				case 15:
	           $galleryID = $gallery['gallery_id'];
	            echo '<div class="width_site width_'.$galleryID.'_'.$moduleID.'"><div class="inside_content">';
				   echo '<div class="mod_gallery_banner_'.$size.' mod_gallery_banner">';
					echo '<ul class="mod_gallery mod_gallery_0" id="gallery_'.$galleryID.'_'.$moduleID.'">';
					foreach ($pictures as $picture) {
						echo '<li class="gallery_0 box '.($scroll == 0?' box-1-3 ':' box-1-1 ').'"><div class="margin"><div class="inside">';
						$this->getImageHtml($data,$picture,$cfg,$galleryID,$moduleID);
						echo '</div></div></li>';
					}
					echo '</ul>';
					echo '
                     <a class="btn prev"><</a>
                     <a class="btn next">></a>
                     <a class="btn play">Autoplay</a>
                     <a class="btn stop">Stop</a>
                     ';
					echo '</div><div class="space"></div></div></div>';
				  
					if (!empty($data[show_enlarge])) {
				  ?>
               <script>
               jQuery(document).ready(function($) {
               	$('#<? echo 'gallery_'.$galleryID.'_'.$moduleID; ?>').owlCarousel({
               navigation : false,
               singleItem : true,
               	});
               });
               </script>
				  <?
				  }
					break;
				
			}
		}

	}

	/* zaokraglanie gwiazdek - 0-0.25 - brak, 0,25 - 0,75 - pol, 0,75 - cala */
	function voting($gallery, $picture_file, $outer = true)
	{
		if (!$gallery['show_voting']) {
			return;
		}
		$max = gallery_vote_max();
		$tab = gallery_vote_get($picture_file);
		if ($outer) {
			echo '<div class="voting" id="voting_' . htmlspecialchars($picture_file) . '">';
		}
		$canvote = gallery_can_vote($picture_file);
		$score = $tab['rank_count'] ? $tab['rank_sum'] / $tab['rank_count'] : 0;
		$point = 1;
		while ($score >= 0.75) {
			echo '<img src="images/vote0.png"';
			if ($canvote) {
				echo ' title="Ocen na ' . $point . '"';
				echo ' onclick="vote(\'' . htmlspecialchars($picture_file) . '\', ' . $point . ', ' . $gallery['gallery_id'] . ')"';
				echo ' style="cursor: pointer;" ';
			}
			echo '/>';
			$score--;
			$max--;
			$point++;
		}
		if ($score >= 0.25 && $score < 0.75) {
			echo '<img src="images/vote05.png"';
			if ($canvote) {
				echo ' title="Ocen na ' . $point . '"';
				echo ' onclick="vote(\'' . htmlspecialchars($picture_file) . '\', ' . $point . ', ' . $gallery['gallery_id'] . ')"';
				echo ' style="cursor: pointer;" ';
			}
			echo '/>';
			$score--;
			$max--;
			$point++;
		}
		while ($max > 0) {
			echo '<img src="images/vote1.png"';
			if ($canvote) {
				echo ' title="Ocen na ' . $point . '"';
				echo ' onclick="vote(\'' . htmlspecialchars($picture_file) . '\', ' . $point . ', ' . $gallery['gallery_id'] . ')"';
				echo ' style="cursor: pointer;" ';
			}
			echo '/>';
			$max--;
			$point++;
		}
		$score = $tab['rank_count'] ? $tab['rank_sum'] / $tab['rank_count'] : 0;
		$score = ((int) ($score * 10)) / 10;
		echo '&nbsp;' . $score . '/'. gallery_vote_max();
		if ($canvote) {
			echo '&nbsp;<span title="Glosuj klikajac na gwiazdki">ZAGLOSUJ!</span>';
		}
		if ($outer) {
			echo '</div>';
		}
	}


	private function getImageHtml($data,$picture,$cfg,$galleryID,$moduleID) {
		// kod obrazka
		if ((!$picture['picture_title']) && (!$picture['picture_description'])) {
			$alt = ALT_TEXT;
			$title = TITLE_TEXT;
		} else {
			$alt = $picture['picture_title'].' '.$picture['picture_description'];
			$title = $picture['picture_title'];
		}
		
		$imgHtml =  '<img   src="' . htmlspecialchars($cfg["IMAGE_BASE_URL"] . $data[image_type] . $picture['picture_file']) . '" alt="'.$alt.'" />';

		

		
		// link zewnetrzny
		if(!empty($picture[picture_target_url]) && ($data['show_target_url'] == 1)) {
			echo '<div class="inside"><a href="'. htmlspecialchars($picture[picture_target_url]) .'" target="_'. htmlspecialchars($picture[picture_target]) .'"  title="'.$title.'">';
			echo $imgHtml;
			echo '</a></div>';
		}
		// pokazuj powiększenia jako lightbox

		
		else if(!empty($data[show_enlarge_lightbox]))   {

			echo '<script type="text/javascript">';
			echo '$(function() {';
			echo '	$(\'#gallery_'.$galleryID.'_'.$moduleID.' a\').lightBox();';
			//echo '	$(\'.zoom_'.$galleryID.'_'.$moduleID.' a\').lightBox();';
			echo '});';
			echo '</script>';
    		//echo '<a href="'.htmlspecialchars($cfg["IMAGE_BASE_URL"] . $cfg['IMAGE_DIR_3'] . $picture['picture_file']).'" title="'.$alt.'">';
			echo $imgHtml;
			//echo '</a>';
			echo '<a class="zoom zoom_'.$galleryID.'_'.$moduleID.'" href="'.htmlspecialchars($cfg["IMAGE_BASE_URL"] . $cfg['IMAGE_DIR_3'] . $picture['picture_file']).'" title="'.$alt.'"></a>';

		} else {
       

			echo $imgHtml;

         
		}
		

		
		if ($data['show_title']) {
			echo '<div class="mod_gallery_title">' . (!empty($picture['picture_title']) ? $title : $picture['picture_title']) . '</div>';
		}
		if ($data['show_description']) {
			echo '<div class="mod_gallery_desc">' . $picture['picture_description'] . '</div>';
		}
		$this->voting($gallery, $picture['picture_file']);
	}
	
	


	
	
	
	
	
	

	

	
	
	
	
	
	
	

}
