
/**
 * funcao de gerenciamento a exibicao dos alertas registrados
 * @returns
 */
function AlertHandler() {

	var alert_area = $('#alert-area');
	var alert_area_btn = alert_area.find('a.toggle');	
	var alert_list = alert_area.find('.alert');
	var alert_hide_list = alert_area.find('.alert.hide');

	if (alert_list.length > 0) {

		{// configurations
			var hideTime = 3 * 1000; // seg			
		}

		{// botao de troca de visualizacao
			{//exibi-lo
				alert_area_btn.show();	
			}
			{// gatilho para exibir/ocultar msgs
				alert_area_btn.click(function() {
					alert_hide_list.toggle();
				});
			}	
		}		

		{// oculta msgs 'ocultaveis' (não .hide) apos determinado tempo
			setTimeout(function() {
				alert_hide_list.hide();
			}, hideTime);
		}

	}
}
