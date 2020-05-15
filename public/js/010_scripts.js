//###########################################################################
//########################################################## SCRIPTS em GERAL
//###########################################################################

//controle de exibicao do botao de retorno (section top)
function backButtonHandler() {
	if (document.referrer == "") {
		$('.btn-voltar').hide();
	}
}

//...