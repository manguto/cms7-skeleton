<?php
namespace application\models;

use manguto\cms7\model\Model;
use manguto\cms7\database\ModelDatabase;
use manguto\cms7\database\repository\ModelRepository;
use manguto\cms7\model\ModelAttribute;
use manguto\cms7\model\ModelStart;

class Zzz extends Model implements ModelDatabase
{
    //metodos basicos 
    use ModelStart;
    
    //tipo de armazenamento (Repository, Mysql, MysqlPDO, etc.)
    use ModelRepository;    
    
    //registros base iniciais 
    const default = [];

    /**
     * Função para definicao do atributos do modelo (ModelAttribute's)
     */
    private function defineAttributes()
    {
        // ---------------------------------------------------
        $a = new ModelAttribute('nome');
        $this->SetAttribute($a);
        // ---------------------------------------------------
        $a = new ModelAttribute('idade');
        $a->setType(ModelAttribute::TYPE_INT);
        $this->SetAttribute($a);
        // ---------------------------------------------------
        $a = new ModelAttribute('peso');
        $a->setType(ModelAttribute::TYPE_FLOAT);
        $a->setUnit('Kg');
        $this->SetAttribute($a);
        // ---------------------------------------------------
    }
}

?>