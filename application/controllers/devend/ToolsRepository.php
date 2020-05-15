<?php
namespace application\controllers\devend;

use application\core\View;
use manguto\cms7\libraries\Diretorios;
use manguto\cms7\libraries\Files;
use manguto\cms7\libraries\ProcessResult;
use manguto\cms7\libraries\CSV;
use manguto\cms7\model\Model;
use manguto\cms7\model\ModelHelper;
use application\core\Route;

class ToolsRepository extends Tools
{

    static function RouteMatchCheck(Route $route)
    {
        $route->get('/dev/tools/repository', function () {
            self::PrivativeDevZone();
            
            {//inicializacao dos modelos existentes
                $model_filename_array = Diretorios::obterArquivosPastas('sis/model', false, true, false, 'php');
                //deb($model_filename_array);
                foreach ($model_filename_array as $model_filename){
                    //deb($model_filename);
                    $model_name = str_replace('.php', '', $model_filename);

                    {//INVERSAO DAS BARRAS PARA CHAMADA DA CLASSE!!!
                        $model_name = str_replace('/','\\',$model_name);
                        //deb($model_name,0);
                    }                    
                    $array = (new $model_name())->search();
                }
            }
            
            {
                $repository_filename_array = Diretorios::obterArquivosPastas('repository', false, true, false, 'csv');
                // deb($r);
            }
            {
                $repository_array = [];
                foreach ($repository_filename_array as $repository_filename) {
                    {
                        $repository_content = utf8_encode(Files::obterConteudo($repository_filename));
                    }
                    {
                        $tablename = Files::getBaseName($repository_filename, false);
                    }
                    {//especificacao das colunas                        
                        $cols = explode(chr(10), trim($repository_content));
                        $cols = array_shift($cols);
                        $cols = explode(';', trim($cols));
                        foreach ($cols as $key=>$col) {
                            if (in_array($col, Model::fundamentalAttributes) && $col != 'id') {
                                unset($cols[trim($key)]);
                            }
                        }
                        //deb($cols);
                    }
                    {
                        $rows = CSV::CSVToArray($repository_content);                                            
                        // deb($rows,0);
                        {//remocao das colunas fundamentais desnecessarias
                            foreach ($rows as $line=>$info){
                                //deb($col,0); deb($info);
                                foreach ($info as $col=>$val){
                                    if (in_array($col, Model::fundamentalAttributes) && $col != 'id') {
                                        unset($rows[$line][trim($col)]);
                                    }
                                }
                            }
                        }
                        {//adicao do registro vazio para adicao
                            $n = sizeof($rows);
                            foreach ($cols as $col){                                
                                $rows[$n][trim($col)]='';
                            }                            
                        }
                        //deb($rows,0);
                    }
                    
                    {
                        $tableContent = $rows;
                        foreach ($tableContent as $key=>$row){
                            if(trim(implode('',$row))==''){
                                unset($tableContent[$key]);
                            }
                        }
                        $db[$tablename.'_id']=$tableContent;
                    }
                    if ($tablename == 'zzz' || $tablename == 'user') {
                        continue;
                    }
                    
                    $repository_array[$tablename] = [
                        'cols' => $cols,
                        'rows' => $rows
                    ];
                }
                //deb($repository_array);
            }
            //deb($db);
            // deb($textarea_array);
            View::PageDevend('tools_repository', get_defined_vars());
        });
        
        $route->post('/dev/tools/repository/save', function () {
            self::PrivativeDevZone();
            {
                //deb($_POST,0);
                {
                    $tablename = $_POST['tablename'];
                }
                {
                    $classname = ModelHelper::getObjectClassname($tablename);
                }
                //deb($_POST);
                $registrosSalvos = 0;
                foreach ($_POST['registros'] as $register) {
                    
                    if(trim(implode('', $register))==''){
                        continue;
                    }
                    $obj = new $classname(intval($register['id']));
                    $obj->SET_DATA($register,false);
                    //deb($obj,0);
                    $obj->save();
                    //deb($obj,0);
                    $registrosSalvos++;
                }
            }            
            if($registrosSalvos>0){
                ProcessResult::setSuccess("$registrosSalvos registro(s) salvo(s) com sucesso!");
            }else{
                ProcessResult::setWarning("Nenhum registro salvo ou afetado.");
            }            
            AppHeaderLocation("/dev/tools/repository#$tablename");
        });
    }
}

?>