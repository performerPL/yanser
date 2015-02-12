<?php 
if (!defined('_APP')) {
  exit;
}
?>

<!--	<link rel="stylesheet" href="js/jquery/jquery-treeview/jquery.treeview.css" />-->
<!--	<link rel="stylesheet" href="js/jquery/jquery-treeview/screen.css" />-->
<!---->
<!--	<script src="js/jquery/jquery.cookie.js" type="text/javascript"></script>-->
<!--	<script src="js/jquery/jquery-treeview/jquery.treeview.js" type="text/javascript"></script>-->
	
	<script type="text/javascript">
	jQuery(document).ready(function(){
		// inicjuje drzewko
		jQuery("#main_groups_tree").treeview({
				 collapsed: true,
				 animated: "fast",
				 unique: true	 
		});

		// dodaje obsluge klikniecia na dodaj kategorie 
		jQuery("#main_groups_tree a[name=add]").live("click",function() {
			// pobiera nadrzędny dokument
			var parentElem = jQuery(this).parent().parent();
			
			// zapisuje grupe do bazy i pobiera z serwera nowe id 
			jQuery.post("index_notice_main_groups.php?ajax=1", { func: "saveGroup" , parent_id : parentElem.attr("id")},
			function(data){
				if(data.id > 0) {	
					var brancheHtml = '<li id="' + data.id +'" ><span class="folder"><input name="edit" type="text" value="Nowa grupa" /></span><div><input name="active" type="checkbox" value="1"><a name="delete">Usun</a></div></li>';
					// tworzy nową grupe
					var branche = jQuery(brancheHtml).appendTo(parentElem);
					
					// dodaje grupe do drzewa
					jQuery("#main_groups_tree").treeview({
						add: branche
					});
				}
				else {
					jQuery('div#messages').html("Wystąpił nieoczekiwany błąd. Spróbuj jeszcze raz.");
				}
			}, "json");
		});

		// dodaje obsluge klikniecia na usun kategorie 
		jQuery("#main_groups_tree a[name=delete]").live("click",function() {
			if(!confirm("Na pewno chcesz usunąć kategorię oraz wszystkie jej podkategorie?") )
				return false;

			// pobiera nadrzędny dokument
			var parentElem = jQuery(this).parent().parent();

			// usuwa grupę i podgrupy bazy
			jQuery.post("index_notice_main_groups.php?ajax=1", { func: "removeGroup" , id : parentElem.attr("id")},
			function(data) {
				if(data.removed) {
					// usuwa grupe i podgrupy
					parentElem.remove();
				}
				else {
					jQuery('div#messages').html("Wystąpił nieoczekiwany błąd. Spróbuj jeszcze raz.");
				}
				
			}, "json"); 
			
		});

		// dodaje obsluge klikniecia na edytuj kategorie 
		jQuery("#main_groups_tree input[name=edit]").live("change",function() {
			// pobiera nadrzędny dokument li
			var parentElem = jQuery(this).parent().parent();

			// zapisuje nazwe grupy do bazy 
			jQuery.post("index_notice_main_groups.php?ajax=1", { func: "saveGroupName" , id : parentElem.attr("id"), name : this.value},
			function(data) {
				if(data.saved) {
					jQuery('div#messages').html("Zapisano nazwę.");
				}
				else {
					jQuery('div#messages').html("Wystąpił nieoczekiwany błąd. Spróbuj jeszcze raz.");
				}
			}, "json"); 
			

		});

		// dodaje obsluge klikniecia na checkbox z aktywnościa grupy 
		jQuery("#main_groups_tree input[name=active]").live("click",function() {
			// pobiera nadrzędny dokument
			var parentElem = jQuery(this).parent().parent();
			var elem = jQuery(this);
			
			// zapisuje status aktywności do bazy 
			jQuery.post("index_notice_main_groups.php?ajax=1", { func: "saveGroupActive" , id : parentElem.attr("id"), active : elem.attr("checked")},
			function(data) {
				if(data.saved) {
					// ustawia znacznik aktywności wszystkich podgrup
					if(jQuery(elem).attr("checked"))
						parentElem.find("input[name=active]").attr("checked","checked");
					else
						parentElem.find("input[name=active]").attr("checked","");
					jQuery('div#messages').html("Zapisano status aktywności");
				}
				else {
					jQuery('div#messages').html("Wystąpił nieoczekiwany błąd. Spróbuj jeszcze raz.");
				}
			}, "json"); 

		});
	});
	</script>
	
	</head>

	<ul id="main_groups_tree" class="filetree">
		<span><a name="add">Dodaj</a></span>
		<?php echo $generatedMainGroupList;?>
	</ul>
