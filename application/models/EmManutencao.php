<?php
namespace application\models;

use manguto\cms7\model\Model;
use manguto\cms7\model\ModelAttribute;
use manguto\cms7\database\repository\ModelRepository;
use manguto\cms7\model\ModelSetup;

class EmManutencao extends Model
{

    use ModelSetup;
    use ModelRepository; 

    /**
     * Função para definicao do atributos do modelo (ModelAttribute's)
     */
    private function defineAttributes()
    {
        // ---------------------------------------------------
        $a = new ModelAttribute('motivo');
        $a->setType(ModelAttribute::TYPE_TEXT);
        $a->setValue('Manutenção Preventiva Padrão.');
        $this->SetAttribute($a);
        // ---------------------------------------------------
        $a = new ModelAttribute('mensagem');
        $a->setType(ModelAttribute::TYPE_TEXT);
        $a->setValue('MANUTENÇÃO em ANDAMENTO! <br/>Por favor, aguarde ou contate o administrador do sistema. <br/><br/>Atenciosamente, <br/>O Administrador');
        $this->SetAttribute($a);
        // ---------------------------------------------------
        $a = new ModelAttribute('status');
        $a->setValue('ativa');
        $this->SetAttribute($a);
        // ---------------------------------------------------
    }
    
    /**
     * Verifica se há alguma manutenção ativa e a retorna ou FALSE.
     * @return boolean|mixed
     */
    static function EmFuncionamento() {
        $manutencao_array = (new self())->search(" \$status=='ativa' ");
        if(sizeof($manutencao_array)>0){
            $manutencao = $manutencao_array;
        }else{
            $manutencao = [];
        }
        return $manutencao;
    }
}



