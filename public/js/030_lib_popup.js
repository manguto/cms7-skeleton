//##########################################################################################
//########################################################################### POPUP CONTROLE
//##########################################################################################

//2020-03-10 | obtem conteudo de ajuda e disponibiliza em um pop-up
function popup(data){	
	$('#popup .content').html(data);
}

//2020-03-10 | obtem conteudo de ajuda e disponibiliza em um pop-up
function ajuda(identificador){
	$.post('/transportes_onibus/ajuda/'+identificador,function(data){
		popup(data);
	});
}
//verifica possiveis links para ajuda em popup
function ajudaPopupsVerificacao(){
	$('a.ajuda').each(function(){
		var a = $(this);
		var href = a.attr('href');
		var identificador = href.replace("#", "");		
		var title = a.prop('title');
		//changes
		if(title==''){
			a.attr('title','Clique para ver algumas instruções e/ou ajuda.');
		}
		a.attr('href','javascript:void(0);');
		a.attr('data-toggle','modal');
		a.attr('data-target','#popup');
		a.attr('data-id',identificador);
		a.click(function(){
			ajuda(identificador);
			//log(identificador);
		});
	});
}
