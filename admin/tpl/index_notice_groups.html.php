<?php 
if (!defined('_APP')) {
  exit;
}
?>

	<link rel="stylesheet" href="js/jquery/jquery-treeview/jquery.treeview.css" />
	<link rel="stylesheet" href="js/jquery/jquery-treeview/screen.css" />

	<script src="js/jquery/jquery.cookie.js" type="text/javascript"></script>
	<script src="js/jquery/jquery-treeview/jquery.treeview.js" type="text/javascript"></script>
	
	<script type="text/javascript" src="js/jquery/jquery-simple-tree/jquery.simple.tree.js"></script>
	<link rel="stylesheet" href="js/jquery/jquery-simple-tree/jquery.simple.tree.css" />
	
	<script type="text/javascript"><!--
	var groupsTree;
	jQuery(document).ready(function(){
		groupsTree = jQuery('#groups_tree').simpleTree({
		        autoclose: true,
		        afterClick:function(node){
		            //alert("text-"+jQuery('span:first',node).text());
		        },
		        afterDblClick:function(node){
		            //alert("text-"+jQuery('span:first',node).text());
		        },
		        afterMove:function(destination, source, pos){
//		            alert("destination-"+destination.attr('id')+" source-"+source.attr('id')+" pos-"+pos);
                   
                    var parentId = destination.attr('id');
                    
                    // nadpisuje id aktualnego parenta
                    source.attr('name',parentId);
//		        	alert(destination.find('li[name='+destination.attr('id')+']').length);
                    var data = '';
                    destination.find('li[name='+parentId+']').each(function() {
                        if(data != '')
                            data += '&';
                        data += 'item_'+parentId+'[]='+ jQuery(this).attr('id');
                    }); 
                    // wysla dane do serwera
                    jQuery.ajax({
                    	  type: "POST",
                    	  url: "index_notice_groups.php?ajax=1&func=saveGroupOrder&parentId="+parentId,
                    	  data: data,
                    	  dataType: "json",
                    	  success: function(returnData){
	                          if(returnData.saved == 1) {
	                        	  jQuery('div#messages').html("Zapisano dane");
	                          }
	                          else {
	                        	  jQuery('div#messages').html("Wystąpił nieoczekiwany błąd. Spróbuj jeszcze raz.");
	                          }
                    	  }
                    });
		        },
		        afterAjax:function()
		        {
		            //alert('Loaded');
		        },
		        animate:true,
		        docToFolderConvert:true
		    });
	    groupsTree = groupsTree.get(0);

		
		// dodaje obsluge klikniecia na dodaj kategorie 
		jQuery("#groups_tree a[name=add]").live("click",function() {
			// pobiera nadrzędny dokument
			var parentElem = jQuery(this).parent().parent();
			
			// zapisuje grupe do bazy i pobiera z serwera nowe id 
			jQuery.post("index_notice_groups.php?ajax=1", { func: "saveGroup" , parent_id : parentElem.attr("id")},
			function(data){
				if(data.id > 0) {	
					var brancheHtml = '<li><ul><li id="' + data.id +'" name="'+ parentElem.attr("id") +'" class=""><span name="spanName">Nowa grupa</span>'
	                +'<div name="editDiv" style="display:none;">'
	                +'<input name="active" type="checkbox" value="1">'
	                +'<input name="editName" type="text" value="Nowa grupa" /><a name="save">Zapisz</a> <a name="hideEdit">Ukryj<a/>'
	                +'</div>'
	                +'<div name="buttonsDiv">'
	                +'<a name="showEdit">Edytuj<a/>'
	                +'<a name="add">Dodaj</a>'
	                +'<a name="delete">Usun</a></div></li></ul></li>';
					groupsTree.addNode(brancheHtml,parentElem.attr("id"));
				}
				else {
					jQuery('div#messages').html("Wystąpił nieoczekiwany błąd. Spróbuj jeszcze raz.");
				}
			}, "json");
		});

		// dodaje obsluge klikniecia na usun kategorie 
		jQuery("#groups_tree a[name=delete]").live("click",function() {
			if(!confirm("Na pewno chcesz usunąć kategorię oraz wszystkie jej podkategorie?") )
				return false;

			// pobiera nadrzędny dokument
			var parentElem = jQuery(this).parent().parent();

			// usuwa grupę i podgrupy bazy
			jQuery.post("index_notice_groups.php?ajax=1", { func: "removeGroup" , id : parentElem.attr("id")},
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

		// pokazuje edycje 
		jQuery("#groups_tree a[name=showEdit]").live("click",function() {
			// pobiera nadrzędny dokument li
			var parentElem = jQuery(this).parent().parent();

			// pokazuje diva z edycja
			parentElem.find("div[name=editDiv]:first").show();

		});

        // ukrywa edycje
        jQuery("#groups_tree a[name=hideEdit]").live("click",function() {
            // pobiera nadrzędny dokument li
            var parentElem = jQuery(this).parent().parent();

            // pokazuje diva z edycja
            parentElem.find("div[name=editDiv]:first").hide();

        });
		
		// dodaje obsluge klikniecia na przycisk zapisz
		jQuery("#groups_tree a[name=save]").live("click",function() {
			// pobiera nadrzędny dokument
			var parentElem = jQuery(this).parent().parent();
			var elem = jQuery(this);
			var active = parentElem.find("input[name=active]:first");
			var editName = parentElem.find("input[name=editName]:first");
			var spanName = parentElem.find("span[name=spanName]:first");
			
			// zapisuje status aktywności do bazy 
			jQuery.post("index_notice_groups.php?ajax=1", { func: "saveGroupActive,saveGroupName" , id : parentElem.attr("id"), active : active.attr("checked"), name : editName.attr("value")},
			function(data) {
				if(data.saved) {
					// ustawia znacznik aktywności wszystkich podgrup
					if(active.attr("checked"))
						parentElem.find("input[name=active]").attr("checked","checked");
					else
						parentElem.find("input[name=active]").attr("checked","");

				    // uaktualnia nazwe w span
				    spanName.html(editName.attr("value"));
					
					jQuery('div#messages').html("Zapisano dane");
				}
				else {
					jQuery('div#messages').html("Wystąpił nieoczekiwany błąd. Spróbuj jeszcze raz.");
				}
			}, "json"); 

		});

	});
	--></script>
	
	</head>
	<body>
	
	<div class="history">
  	<img src="img/icon_user.gif" width="64" height="64" border="0" alt="" /> 
  	<a href="index.php" title="<?php _t('main_menu'); ?>"><?php _t('main_menu'); ?></a>
  	<?php _t('notice_groups_mgmt'); ?>
	</div>
	
	<div id="main">
			<div id="messages"></div>
	
			<div>
			Grupy globalne: <?php include "index_notice_main_groups.php"?>
			</div>
			
			<div>
			Grupy:
			<ul id="groups_tree" class="simpleTree">
				 <li id="0" class="root"><span><a name="add">Dodaj</a></span>
				<?php echo $generatedGroupList;?>
				</li>
			</ul>
			</div>
	
	</div>