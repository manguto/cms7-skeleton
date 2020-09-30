<?php
namespace application\core;

use manguto\cms7\libraries\ServerHelp;
use manguto\cms7\libraries\Exception;
use manguto\cms7\libraries\Files;

class Page
{

    // diretorio em questao (front,back,dev)
    private $tpl_dir;

    // arquivo base principal
    private $tpl_filename;

    // configuracao de salvamento do template (desativado em sub-paginas (includes))
    private $save_cache;
    
    //especifica se o template a ser carregado eh um include de algum outro
    private $included_template;

    // controla a reutilizacao do cache
    private $parameters;

    // utilizar arquivo existente ou atualizar? (fase de testes => TRUE)
    const REFRESH_CACHE = PAGE_REFRESH_CACHE;

    // ####################################################################################################
    // ####################################################################################################
    // ####################################################################################################
    public function __construct(string $tpl_dir,bool $included_template = false, bool $tpl_cache_save = true)
    {
        $this->tpl_dir = $tpl_dir;
        $this->save_cache = $tpl_cache_save;
        $this->included_template = $included_template;
    }

    // ####################################################################################################
    public function loadTpl(string $tpl_filename, array $parameters = [])
    {
        $this->tpl_filename = ServerHelp::fixds($tpl_filename);
        $this->parameters = $parameters;

        { // criacao do arquivo de cache ou atualizacao forçada (testes)

            if (! file_exists($this->getTplFilename()) || self::REFRESH_CACHE) {
                
                { // obtencao conteudo template HTML/Pseudo-PHP
                    $html_pphp_content = $this->getTplContent();
                }
                { // substituicao do Pseudo-PHP por PHP
                    $html_php_content = $this->replacePseudoPHPContent($html_pphp_content);
                }
                { // salvamento do arquivo de cache HTML/PHP
                    $this->saveTplCache($html_php_content);
                }
            }            
        }
    }

    // ####################################################################################################

    /**
     * exibe ou retorna HTML do template
     * @param bool $toString
     * @return string
     */
    public function run(bool $toString = false)
    {
        // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
        { // executa o cache sem a impressao (run)
            ob_start();            
            extract($this->parameters);
            require_once $this->getTplFilename();            
            $return = ob_get_contents();
            ob_end_clean();
        }
        // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
        // caso definido, remove o arquivo de cache
        if ($this->save_cache == false) {
            $this->deleteTplCache();
        }
        // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
        // retorna conteudo processado ou exibe-o (imprime)
        if ($toString == true) {
            return $return;
        } else {
            print $return;
            //exit(); //removed in 2020-09-29
        }
    }

    // ####################################################################################################
    private function getTplContent()
    {
        { // caminho do arquivo de template
            $tpl_filename_path = $this->tpl_dir . $this->tpl_filename . '.html';
            $tpl_filename_path = ServerHelp::fixds($tpl_filename_path);
        }
        { // obtencao conteudo
            if (! file_exists($tpl_filename_path)) {
                throw new Exception("O template informado ('{$this->tpl_filename}') não foi encontrado no diretório solicitado ('{$this->tpl_dir}').");
            } else {
                $return = Files::getContent($tpl_filename_path);
            }
        }
        return $return;
    }

    // ####################################################################################################
    private function replacePseudoPHPContent(string $tpl_content): string
    {
        $PageReplacer = new PageReplacer($this->tpl_dir,$tpl_content,$this->parameters);
        return $PageReplacer->run();
    }

    // ####################################################################################################
    /**
     * salvamento do cache
     */
    private function saveTplCache(string $content)
    {
        // save
        Files::writeContent($this->getTplFilename(), $content);
    }

    // ####################################################################################################
    /**
     * obtem o nome do arquivo (completo) de cache para o template informado
     *
     * @return string
     */
    private function getTplFilename(): string
    {
        { // parametros
            { // diretorio do cache
                $dir = APP_TPL_CACHE_DIR;
                if($this->included_template){
                    $dir .= 'include'.DS;
                }
            }
            { // parametro anterior ao nome do arquivo
                $filename = $this->tpl_dir . $this->tpl_filename;
                $filename = str_replace('..', '', $filename);
                $filename = str_replace(APP_TPL_DIR, '', $filename);
                $filename = str_replace(DS, '_', $filename);                
            }            
            { // diferencial do template (definir parametros a serem utilizados: IP, data, hora ...)
                //$pos = '_' . date('Ymd');
                $pos = '';
            }
            { // extensao
                $ext = '.php';
            }
        }
        $return = "{$dir}{$filename}{$pos}{$ext}";
        $return = ServerHelp::fixds($return);
        //deb($return);
        return $return;
    }

    // ####################################################################################################
    /**
     * exclusao do cache
     */
    private function deleteTplCache()
    {
        // delete
        Files::delete($this->getTplFilename());
    }
    // ####################################################################################################
    // ####################################################################################################
    // ####################################################################################################
}