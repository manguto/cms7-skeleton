<?php
namespace application\core;

use manguto\cms7\libraries\Strings;
use manguto\cms7\libraries\Exception;

class PageReplacer
{

    const manguto_libraries_path = 'manguto\\' . APP_GIT_MANGUTO_REPOSITORY_NAME . '\\libraries\\';

    private $tpl_dir;

    private $content;

    private $parameters;

    private $matches = [];

    // ####################################################################################################
    // ####################################################################################################
    // ####################################################################################################

    /**
     * inicializa a classe de substituicao e consequente transformacao do codigo HTML do template (informado) em um codigo HTML + PHP
     *
     * @param string $tpl_dir
     * @param string $content
     * @param array $parameters
     */
    public function __construct(string $tpl_dir, string $content, array $parameters)
    {
        $this->tpl_dir = $tpl_dir;
        $this->content = $content;
        $this->parameters = $parameters;
    }

    // ####################################################################################################

    /**
     * 1-procura os padroes para substituicao
     * 2-realiza as substituicoes
     * 3-retorna uma string com codigo HTML + PHP
     *
     * @return string
     */
    public function run(): string
    {
        { // realiza eventuais ajustes no conteudo
            $this->fixContent();
        }

        { // searchs
            $this->matches = [];
            {
                $this->searchConstants();
                $this->searchVariables();
                $this->searchIncludes();
                $this->searchFunctions();
                $this->searchConditions();
                $this->searchLoop();
            }
        }
        { // replaces
            
            {// obtem o conteudo para substituicao dos padroes
                $content = $this->content;
                //deb($content);
            }
            
            // debc($this->matches,0);
            foreach ($this->matches as $search => $replaces) {
                foreach ($replaces as $replace) {
                    $content = Strings::str_replace_first($search, $replace, $content);
                }
            }
        }
        return $content;
    }

    // ####################################################################################################

    /**
     * procura o padrao informado e retorna uma lista das strings encontradas
     *
     * @param string $pattern
     * @return array
     */
    private function getPatternMatches(string $pattern): array
    {
        $return = [];
        { // pesquisa pelo padrao informado!
            $match_array = [];
            // deb($pattern,0);
            $result = preg_match_all($pattern, $this->content, $match_array);
            if ($result === false) {
                $error = error_get_last();
                $error = $error['message'];
                throw new Exception("Foi encontrado um erro no padrão da expressão regular ('$pattern'). <br/>ERROR: $error.");
            }
            // deb($match);
        }
        { // depuracao dos dados obtidos
            foreach ($match_array as $real_match_array) {
                // deb($real_match_array);
                foreach ($real_match_array as $v) {
                    $return[] = $v;
                }
            }
            // deb($return,0);
        }
        return $return;
    }

    // ####################################################################################################
    /**
     * adiciona um registro aa lista de substuituicao com as devidas verificacoes.
     *
     * @param string $search
     * @param string $replace
     */
    private function addMatch(string $search, string $replace)
    {
        $this->matches[$search][] = $replace;

        /*
         * if (! isset($this->matches[$search])) {
         * $this->matches[$search] = $replace;
         * } else {
         * if ($this->matches[$search] != $replace) {
         * //debc($this->matches,0);
         * $errorMsg = "Foi encontrado um termo para substituição ($search) com correspondentes diferenciados.<br/>$search = " . $this->matches[$search] . "<br/>$search = $replace";
         * throw new Exception($errorMsg);
         * }
         * }/*
         */
    }

    // ####################################################################################################
    /**
     * devolve o codigo informado, envolvido (iniciado e finalizado) pelas tags do PHP
     *
     * @return string
     */
    private static function phpWrap($code): string
    {
        $return = '<?php ';
        $return .= $code;
        $return .= ' ?>';
        return $return;
    }

    // ####################################################################################################

    /**
     * corrige
     *
     * @return mixed
     */
    private function fixContent()
    {   
        { // fixes
            { // Wrong Automatic Identation (CRTL + F on Eclipse)
                $this->content = str_replace('" }', '"}', $this->content);
            }
        }
    }

    // ####################################################################################################
    // ####################################################################################################
    // ######################################################################################### CONSTANTES
    // ####################################################################################################
    // ####################################################################################################
    private function searchConstants()
    {
        { // regex pattern
            { // limites esq e dir
                $left = '{#';
                {
                    $center = '\w*';
                }
                $right = '#}';
            }
            $pattern = '/' . $left . $center . $right . '/';
            // deb($pattern);
        }
        $matches = $this->getPatternMatches($pattern);
        foreach ($matches as $rawMatch) {
            { // nome da cte
                $match = trim($rawMatch);
                $match = str_replace('{#', '', $match);
                $match = str_replace('#}', '', $match);
            }
            { // substituicao (pseudo-php => php)
                { // codigo para substituicao
                  // $replace = "echo " . self::manguto_libraries_path . "Constants::isset_get(\"$match\",true); ";
                    $replace = "echo $match;";
                    $replace = self::phpWrap($replace);
                }
                // addMatch
                $this->addMatch($rawMatch, $replace);
            }
        }
    }

    // ####################################################################################################
    // ####################################################################################################
    // ########################################################################################## VARIÁVEIS
    // ####################################################################################################
    // ####################################################################################################
    private function searchVariables()
    {
        { // regex pattern
            { // limites esq e dir
                $left = '{\$';
                {
                    $center = '[';
                    $center .= '\w'; // Any word character (letter, number, underscore)
                    $center .= "'";
                    $center .= "\$";
                    $center .= '-';
                    $center .= '>';
                    $center .= '\(';
                    $center .= '\)';
                    $center .= '\[';
                    $center .= '\]';
                    $center .= ']+'; // 1 vez ou mais
                }
                $right = '}';
            }
            $pattern = '/' . $left . $center . $right . '/';
            // deb($pattern);
        }
        $matches = $this->getPatternMatches($pattern);
        // deb($matches);
        foreach ($matches as $rawMatch) {
            { // nome da cte
                $match = trim($rawMatch);
                $match = str_replace(str_replace('\\', '', $left), '', $match);
                $match = str_replace($right, '', $match);
                // deb($varName);
            }
            { // substituicao (pseudo-php => php)
                { // replace
                  // $replace = "echo " . self::manguto_libraries_path . "Variables::isset_get(\"$match\",get_defined_vars(),true); ";
                    $replace = "echo $$match;";
                    $replace = self::phpWrap($replace);
                }
                // addMatch
                $this->addMatch($rawMatch, $replace);
            }
        }
    }

    // ####################################################################################################
    // ####################################################################################################
    // ########################################################################################### INCLUDES
    // ####################################################################################################
    // ####################################################################################################
    private function searchIncludes()
    {
        { // regex pattern
            { // limites esq e dir
                $left = '{include="';
                {
                    $center = '[';
                    $center .= '\w'; // Any word character (letter, number, underscore)
                    $center .= '\.';
                    $center .= '\/';
                    $center .= ']+'; // 1 vez ou mais
                }
                $right = '"}';
            }
            $pattern = '/' . $left . $center . $right . '/';
            // deb($pattern);
        }
        $matches = $this->getPatternMatches($pattern);
        // deb($matches,0);
        foreach ($matches as $rawMatch) {
            { // nome do template a ser incluido
                $match = trim($rawMatch);
                $match = str_replace($left, '', $match);
                $match = str_replace($right, '', $match);
            }
            { // substituicao (pseudo-php => php)
                { // replace
                    $Page = new Page($this->tpl_dir, true);
                    $Page->loadTpl($match, $this->parameters);
                    $replace = $Page->run(true);
                }
                // addMatch
                $this->addMatch($rawMatch, $replace);
            }
        }
    }

    // ####################################################################################################
    // ####################################################################################################
    // ################################################################################# FUNCOES ou METODOS
    // ####################################################################################################
    // ####################################################################################################
    private function searchFunctions()
    {
        { // regex pattern
            { // limites esq e dir
                $left = '{func="';
                {
                    $center = '[^}]+';
                }
                $right = '"}';
            }
            $pattern = '/' . $left . $center . $right . '/';
            // deb($pattern);
        }

        $matches = $this->getPatternMatches($pattern);
        // deb($matches,0);
        foreach ($matches as $rawMatch) {
            { // apara as rebarbas para obter apenas o conteudo de interesse
                $match = trim($rawMatch);
                $match = str_replace($left, '', $match);
                $match = str_replace($right, '', $match);
            }
            { // substituicao (pseudo-php => php)
                { // replace
                    $replace = "echo $match;";
                    $replace = self::phpWrap($replace);
                }
                // addMatch
                $this->addMatch($rawMatch, $replace);
            }
        }
    }

    // ####################################################################################################
    // ####################################################################################################
    // ################################################################################ CONDICOES (IF/ELSE)
    // ####################################################################################################
    // ####################################################################################################
    private function searchConditions()
    {
        $this->searchConditions_if();
        $this->searchConditions_else();
        $this->searchConditions_if_end();
    }

    // ##################################################################################
    private function searchConditions_if()
    {
        { // regex pattern
            { // limites esq e dir
                $left = '{if="';
                {
                    $center = '[^}]+';
                }
                $right = '"}';
            }
            $pattern = '/' . $left . $center . $right . '/';
            // deb($pattern);
        }
        $matches = $this->getPatternMatches($pattern);
        // deb($matches);
        foreach ($matches as $rawMatch) {
            { // condicao
                $match = trim($rawMatch);
                $match = str_replace($left, '', $match);
                $match = str_replace($right, '', $match);
            }
            { // substituicao (pseudo-php => php)
                { // replace
                    $replace = " if($match){ ";
                    $replace = self::phpWrap($replace);
                }
                // addMatch
                $this->addMatch($rawMatch, $replace);
            }
        }
    }

    // ##################################################################################
    private function searchConditions_else()
    {
        { // regex
            $matches = $this->getPatternMatches('/{else}/');
        }
        foreach ($matches as $rawMatch) {
            { // condicao
                $match = trim($rawMatch);
                $match = str_replace('{else}"', '', $match);
            }
            { // substituicao (pseudo-php => php)
                { // replace
                    $replace = "}else{";
                    $replace = self::phpWrap($replace);
                }
                // addMatch
                $this->addMatch($rawMatch, $replace);
            }
        }
    }

    // ##################################################################################
    private function searchConditions_if_end()
    {
        { // regex
            $matches = $this->getPatternMatches('/{\/if}/');
        }
        foreach ($matches as $rawMatch) {
            { // condicao
                $match = trim($rawMatch);
                $match = str_replace('{/if}', '', $match);
            }
            { // substituicao (pseudo-php => php)
                { // replace
                    $replace = "}";
                    $replace = self::phpWrap($replace);
                }
                // addMatch
                $this->addMatch($rawMatch, $replace);
            }
        }
    }

    // ####################################################################################################
    // ####################################################################################################
    // ##################################################################################### LOOP (foreach)
    // ####################################################################################################
    // ####################################################################################################
    private function searchLoop()
    {
        $this->searchLoopOpen();
        $this->searchLoopClose();
    }

    // ##################################################################################

    //
    private function searchLoopOpen()
    {
        { // regex pattern
            { // limites esq e dir
                $left = '{loop="';
                {
                    $center = '[^}]+';
                }
                $right = '"}';
            }
            $pattern = '/' . $left . $center . $right . '/';
            // deb($pattern);
        }
        $matches = $this->getPatternMatches($pattern);
        // deb($matches,0);

        // percorrendo todos os loops
        foreach ($matches as $index => $rawMatch) {
            { //
                $match = trim($rawMatch);
                $match = str_replace($left, '', $match);
                $match = str_replace($right, '', $match);
            }
            { // substituicao (pseudo-php => php)
                { // replace
                    $replace = "foreach($match as \$key{$index}=>\$value{$index}){ \$key = \$key{$index}; \$value = \$value{$index}; ";
                    $replace = self::phpWrap($replace);
                }
                // addMatch
                $this->addMatch($rawMatch, $replace);
            }
        }
    }

    // ##################################################################################
    private function searchLoopClose()
    {
        { // regex
            $matches = $this->getPatternMatches('/{\/loop}/');
            // deb($matches,0);
        }
        foreach ($matches as $rawMatch) {
            { // substituicao (pseudo-php => php)
                { // replace
                    $replace = "}";
                    $replace = self::phpWrap($replace);
                }
                // addMatch
                $this->addMatch($rawMatch, $replace);
            }
        }
    }
    // ####################################################################################################
    // ####################################################################################################
    // ####################################################################################################
    // ####################################################################################################
    // ####################################################################################################
    // ####################################################################################################
}

?>