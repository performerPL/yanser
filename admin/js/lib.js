function setVisible(id,visible) {
	var el = document.getElementById(id);
	if(el!=null) {
		if(visible) {
			el.style.display = 'block';
		} else {
			el.style.display = 'none';
		}
	}
}

insertionFunc = function(el,data){
	var cl = data.visible?'item_info_det':'item_info_det off';
	var cl2 = data.visible?'item_info_nam':'item_info_nam off';
	var node = Builder.node('div',{'class':'item_info'},
	[
		
		Builder.node('div',{'class':cl2},[
			Builder.node('img',{'src':data.type_img,'border':'0','width':'20','heigth':'20','alt':data.type_label,'class':'item_info_img'}),
			' '+data.name
		]),
		//Builder.node('span',{'class':cl2},[data.name]),
		Builder.node('span',{'class':cl},
		[
			Builder.node('form', {method: 'post', action: 'edit_item.php', id: 'delete_' + data.id, style: 'display: inline'}, [
				Builder.node('input', {type: 'hidden', name: 'cmd', value: 'delete'}),
				Builder.node('input', {type: 'hidden', name: 'item_id', value: data.id}),
				Builder.node('input', {type: 'hidden', name: 'menu_id', value: data.menu_id}),
				Builder.node('a', {href: 'javascript:$("delete_' + data.id + '").submit();', onclick: 'return confirm("Czy na pewno usunac te pozycje?")'}, [
				Builder.node('img', {src: 'img/icon_item_delete_m.gif', 'border': 0, 'width': 20, 'height': 20}), ' ' + data.delete_label, '               ' 	])] ),
				Builder.node('a',{'href':'edit_item.php?item_id='+data.id+'#content', 'name':'i_'+data.id},[
				Builder.node('img',{'src':'img/icon_item_edit_m.gif','border':'0','width':'20','heigth':'20','alt':''}), ' '+data.edit_label
			])
		])
	]);
	if(data.nodes) {
		for(i=0;i<data.nodes.length;i++) {
			insertionFunc(node,data.nodes[i])
		}
	}
	el.appendChild(node);
	this.mark.innerHTML = '&nbsp;';
}

insertionFunc_file = function(el,data){
	var node;
	if(data.link_func!='') {
		node = Builder.node('div',{'class':'item_info'},
		[
			
			Builder.node('div',{'class':'item_info_nam'},[
				Builder.node('img',{'src':data.file_icon,'border':'0','width':'16','heigth':'16','alt':data.file_name}),
				' '+data.file_name
			]) ,
			//Builder.node('span',{'class':cl2},[data.name]),
			Builder.node('span',{'class':'item_info_det'},
			[
				Builder.node('a',{'href':data.link_func+'(\''+data.rel_path+'\')','name':'i_'+data.rel_path},[
					data.link_label
				])
			])
		]);
	} else {
		node = Builder.node('div',{'class':'item_info'},
		[
			
			Builder.node('div',{'class':'item_info_nam'},[
				Builder.node('img',{'src':data.file_icon,'border':'0','width':'16','heigth':'16','alt':data.file_name}),
				' '+data.file_name
			])
		]);
	}
	if(data.nodes) {
		for(i=0;i<data.nodes.length;i++) {
			insertionFunc(node,data.nodes[i])
		}
	}
	el.appendChild(node);
	this.mark.innerHTML = '&nbsp;';

}
callbackFunc_file = function(id) {
	var x = document.getElementById('repo_type');
	return 'id='+id+'&type='+x.value;
}


Ajax.Tree.Items = Ajax.Tree.create({
	types: {
		leaf: {
			leafNode: true,
			prependParentId: false,
			insertion: insertionFunc
			//sortable:true
		},
		item: {
			page: 'ajaxtreedata_item.php',
			prependParentId: false,
			insertion: insertionFunc,
			sortable:true
		}
	},
	getElement:function() {
		return this.element;
	}
});



Ajax.Tree.Files = Ajax.Tree.create({
	types: {
		dir: {
			page: 'ajaxtreedata_file.php',
			prependParentId: '/',
			callback: callbackFunc_file,
			insertion: insertionFunc_file
			//sortable:true
		},
		file: {
			leafNode: true,
			prependParentId: '/',
			callback: callbackFunc_file,
			insertion: insertionFunc_file
		}
	},
	getElement:function() {
		return this.element;
	}
});


updateOrderItem = function(el) {
	//alert(Sortable.serialize(el.id));
	var x = new Ajax.Request("edit_item_order.php", {  
            method: "post",  
            parameters: { parent_id: el.id, menu_id: $('menu_id').value, data: Sortable.serialize(el.id), show_archive: $('archive_1').checked },
	    onComplete: function(response) {if(response.responseText != 'ok') alert(response.responseText)}

        });  
	//alert(x.responseText);
	
}
updateOrderModule = function(el) {
	//alert(Sortable.serialize(el.id));
	var id = document.getElementById('article_id').value;
	var x = new Ajax.Request("edit_article_module_order.php", {  
            method: "post",  
            parameters: { parent_id: id,  data: Sortable.serialize(el.id) }
	   // ,onComplete: function(response) {alert(response.responseText)}

        });  
	//alert(x.responseText);
	
}

updateOrderPictures = function(el) {
	//alert(Sortable.serialize(el.id));
	var id = $("gallery_id").value;
	new Ajax.Request("edit_gallery_picture_order.php", {
		method: "post",
		parameters: { gallery_id: id, data: Sortable.serialize(el.id) }
	});
}

updateOrderFiles = function(el) {
	//alert(Sortable.serialize(el.id));
	var id = $("group_id").value;
	var dir = $("tajny_dir").value;
	new Ajax.Request("edit_group_file_order.php", {
		method: "post",
		parameters: { dir: dir, group_id: id, data: Sortable.serialize(el.id) }
	});
}

menuChanged = function(el, parentId, itemId) {
	var menuId = el.value;
	new Ajax.Updater("parent_id_div", "edit_item_parents.php", {
		parameters: {
			menu_id: menuId,
			parent_id: parentId,
			item_id: itemId
		}
	});
}
