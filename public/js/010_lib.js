//###########################################################################
//########################################################## FUNCOES EM GERAL
//###########################################################################

//exibicao de alguma mensagem (desenvolvimento)
function log(msg, separator) {
	$('#log').append(msg + (separator == null ? '<br />' : separator));
}

// ###########################################################################
// controle de exibicao do botao de retorno (section top)
function backButtonHandler() {
	if (document.referrer == "") {
		$('nav #user-menu a.back').hide();
	}
}

// #########################################################################################################

// copia conteudo da tag informada
function CopyToClipboard(tagId) {
	var range = document.createRange();
	range.selectNode(document.getElementById(tagId));
	window.getSelection().removeAllRanges(); // clear current selection
	window.getSelection().addRange(range); // to select text
	document.execCommand("copy");
	window.getSelection().removeAllRanges();// to deselect
	console.log("Text has been copied!");
}
// ###########################################################################
// ###########################################################################
// ###########################################################################
