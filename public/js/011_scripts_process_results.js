
/**
 * funcao de gerenciamento do resultado dos processos
 * chamada no "Document Ready"
 * @returns
 */
function processResultHandler(){
	
	{// configurations
		var hideTime = 3 * 1000; // seg
		var hideEffectTime = 1 * 1000; // seg
	}
	
	var pra = $('#process-results-area');	
	var pra_t = pra.find('a.toggle');
	var prs = pra.find('.process-result');

	//gatilho para exibir/ocultar msgs
	pra_t.click(function(){
		prs.toggle();
	});
	
	// obtem a quantidade de mensagens existentes na iteracao
	var prs_length = prs.length;
	// log(pr_l);

	if (prs_length > 0) {
		{// exibir botao troca de visualizacao
			pra.find(".toggle_btn").show();
		}
		setTimeout(function() {
			prs.find('.hide').hide(hideEffectTime);
		}, hideTime);
	}
	
}
