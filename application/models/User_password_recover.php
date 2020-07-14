<?php
namespace application\models;

use manguto\cms7\model\Model;
use manguto\cms7\model\ModelAttribute;
use manguto\cms7\database\repository\ModelRepository;
use manguto\cms7\model\ModelSetup;
use manguto\cms7\libraries\Safety;
use manguto\cms7\libraries\Email;
use manguto\cms7\libraries\Exception;
use application\core\View;
use manguto\cms7\libraries\ProcessResult;
use manguto\cms7\libraries\Logger;

class User_password_recover extends Model
{

    use ModelSetup;
    use ModelRepository;

    // prazo de validade da solicitacao de reset de senha (2 horas)
    const deadline = 60 * 60 * 2;

    const link_base = APP_DOMAIN . APP_URL_ROOT . 'forgot/reset';

    const code_separator = '-';

    const secret_key = __CLASS__;
    
    /**
     * senha do servidor de email secundario
     * @var boolean
     */
    public $ams_password = false;

    // ####################################################################################################
    /**
     * Função para definicao do atributos do modelo (ModelAttribute's)
     */
    private function defineAttributes()
    {
        

        // ---------------------------------------------------
        $a = new ModelAttribute('email');
        $this->SetAttribute($a);
        // ---------------------------------------------------
        $a = new ModelAttribute('user_id');
        $a->setType(ModelAttribute::TYPE_INT);
        $this->SetAttribute($a);
        // ---------------------------------------------------
        $a = new ModelAttribute('code');
        $this->SetAttribute($a);
        // ---------------------------------------------------
        $a = new ModelAttribute('link');
        $this->SetAttribute($a);
        // ---------------------------------------------------
        $a = new ModelAttribute('ip');
        $a->setType(ModelAttribute::TYPE_VARCHAR);
        $a->setValue($_SERVER["REMOTE_ADDR"]);
        $this->SetAttribute($a);
        // ---------------------------------------------------
        $a = new ModelAttribute('datetime');
        $a->setType(ModelAttribute::TYPE_TIMESTAMP);
        $a->setValue(time());
        $this->SetAttribute($a);
        // ---------------------------------------------------
        $a = new ModelAttribute('deadline');
        $a->setType(ModelAttribute::TYPE_TIMESTAMP);
        $a->setValue((time() + self::deadline));
        $this->SetAttribute($a);
        // ---------------------------------------------------
        $a = new ModelAttribute('status');
        $this->SetAttribute($a);
        // ---------------------------------------------------
    }

    // ####################################################################################################
    /**
     * inicializa o procedimento de recuperacao de senha via e-mail
     * @param string $email
     */
    static function ProcessInitialization(string $email)
    {
        Logger::proc("Solicitação de recuperação de senha inicializada para o e-mail: '$email' => ".__METHOD__);
        $upr = new User_password_recover();
        {
            $emailTestResult = $upr->checkEmail($email);
            //deb($test_result,0); deb($result);
        }
        if ($emailTestResult === true) {
            //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            $email_result = $upr->sendEmail();
            //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
            if ($email_result === true) {
                $msg = '';
                $msg .= "Prezado(a),<br/>";
                $msg .= "Foi enviada uma mensagem de e-mail para '$email', com as informações necessárias para a realização da sua solicitação.<br/>";
                $msg .= "Verifique a respectiva caixa de entrada, e siga as instruções contidas nesta mensagem.<br/>";
                $msg .= "Att,<br/>" . APP_FULL_NAME;
                ProcessResult::setSuccess($msg);
            } else {
                ProcessResult::setError($email_result);
            }
        } else {
            ProcessResult::setError($emailTestResult);
        }
    }

    // ####################################################################################################
    /**
     * testa se o email existe e define o codigo e o link de recuperacao
     * caso contrario
     * retorna falso
     *
     * @param string $email
     * @return boolean|string
     */
    public function checkEmail(string $email)
    {    
        {// ----------------------------------- test!
            Logger::info("Verificação de e-mail existente...");
            $user = User::checkEmailExist($email);
        }        
        if ($user !== false) {
            Logger::success("E-mail '$email' encontrado com sucesso! ");
            $this->setEmail($email);
            $this->setUser_id($user->getId());
            $this->generateCode();
            $this->generateLink();
            $this->save();
            return true;
        } else {
            Logger::error("E-mail não encontrado ($email).");
            $return = "Não foi possível enviar o e-mail com as instruções de recuperação de senha.<br/>";
            $return .= "Tente novamente em alguns instantes.<br/>";
            $return .= "Obrigado!<br/>";
            return $return;
        }        
    }

    // ####################################################################################################
    public function sendEmail()
    {   
        {// parametros
            $user = new User($this->getUser_id());
            $from = APP_EMAIL;
            $to = $this->getEmail();
            $subject = APP_SHORT_NAME . " - Solicitação de Redefinição de Senha ({$this->getEmail()})";
            $content = $this->getEmailContent($user);
        }
        if(!$this->ams_password){
            throw new Exception("Senha de utilização do serviço de e-mail alternativo não definida.");
        }
        return Email::Enviar($from, $to, '', '', $subject, $content,$this->ams_password);        
    }

    // ####################################################################################################
    private function getEmailContent($user): string
    {
        // parameters
        $parameters = [
            "user" => $user,
            "link" => $this->getLink(),
            "deadline" => round(User_password_recover::deadline / 60, 0)
        ];
        $return = View::PageExtra('email_forgot', $parameters, true);
        return $return;
    }

    // ####################################################################################################
    /**
     * Gera o link para ser utilizado quando da atualizacao da senha
     *
     * @return string
     */
    private function generateLink()
    {
        { // pars
            $link_base = User_password_recover::link_base;
            $code = $this->getCode();
        }
        $this->setLink("$link_base/$code");
    }

    // ####################################################################################################
    /**
     * Gera um codigo cifrado com base em dados da solicitacao
     *
     * @return string
     */
    private function generateCode()
    {
        // parametros do codigo
        $id = $this->getId();
        $user_id = $this->getUser_id();
        $sep = User_password_recover::code_separator;
        // *****************************************************
        $masc = $id . $sep . $user_id;
        // *****************************************************
        $code = Safety::encrypt($masc, self::secret_key);
        // *****************************************************
        // deb($masc, 0); deb($code);
        $this->setCode($code);
    }

    // ####################################################################################################
    /**
     * Verifica se o codigo informado consta no sistema, bem como a sua validade.
     * Retorna o 'registro' ou uma 'mensagem' do erro (caso tenha ocorrido).
     *
     * @param string $code
     * @return string|User_password_recover
     */
    static function checkCode__getSelf(string $code)
    {
        try {
            $masc = Safety::decrypt($code, self::secret_key);

            if (strpos($masc, User_password_recover::code_separator) !== false) {
                $code_array = explode(User_password_recover::code_separator, $masc);
                if (sizeof($code_array) == 2) {
                    $id = array_shift($code_array);
                    $user_id = array_shift($code_array);
                    $result = (new User_password_recover())->search(" \$id==$id && \$user_id==$user_id && \$code=='$code' ");
                    $User_password_recover = array_shift($result); // <<< User_password_recover
                    if ($User_password_recover !== NULL) {
                        { // ultimas verificacaoes (status e deadline)
                            $return = [];
                            $status = $User_password_recover->checkStatus();
                            if ($status !== true) {
                                $return[] = $status;
                            }
                            $deadLine = $User_password_recover->checkDeadline();
                            if ($deadLine !== true) {
                                $return[] = $deadLine;
                            }
                        }
                        if (sizeof($return) == 0) {
                            $return = $User_password_recover;
                        } else {
                            $return = trim(implode('<hr/>', $return));
                        }
                    } else {
                        $return = "Não foi encontrado nenhum registro de recuperação de senha para o código informado ('$code => $masc').<br/>";
                    }
                } else {
                    $return = "Os parâmetros do código informado ('$code => $masc') não se encontram no formato correto.<br/>";
                }
            } else {
                $return = "O código informado ('$code => $masc') não se encontra no formato adequado.<br/>";
            }
        } catch (Exception $e) {
            $return = $e->getMessage();
        }

        // verifica se houve algum erro e incrementa a mensagem
        if (is_string($return)) {
            $return .= "<br/>";
            $return .= "<br/>";
            $return .= "Entre em contate com o administrador!<br/>";
        }

        return $return;
    }

    // ####################################################################################################
    static function checkCode__updateUserPassword(string $code, string $password, string $password2)
    {
        $result = User_password_recover::checkCode__getSelf($code, false);
        if (! is_string($result)) {
            // --------------------
            $User_password_recover = $result;
            if (0)
                $User_password_recover = new User_password_recover();
            // --------------------
            if ($password != $password2) {
                $result = 'As senhas não podem ser diferentes. Tente novamente!';
            } else {
                // references load
                $User_password_recover->loadReferences(false);
                // define upr como utilizado!
                $User_password_recover->setForgotUsed();

                { // atualiza a senha do usuario
                    $user = $User_password_recover->getUser();
                    $user->setPassword($password);
                    $user->save();
                }
                $result = true;
            }
        }
        return $result;
    }

    // ####################################################################################################
    /**
     * Define o registro como utilizado!
     */
    private function setForgotUsed()
    {
        $this->setStatus('used');
        $this->save();
    }

    // ####################################################################################################
    /**
     * verifica se a solicitacao ja foi utilizada
     *
     * @return bool
     */
    private function checkStatus(): bool
    {
        if (trim($this->getStatus()) != '') {
            $return = "O link informado já foi utilizado. Caso necessário, solicite um novo link e tente novamente.<br/><br/>";
        } else {
            $return = true;
        }
        return $return;
    }

    /**
     * verifica se a solicitacao encontra-se dentro do prazo hábil
     *
     * @return bool
     */
    private function checkDeadline(): bool
    {
        {
            $timestampNow = (int) time();
            $timestampEnd = (int) $this->getdeadline();
            $showNow = date('H:i d/m/Y', $timestampNow);
            $showEnd = date('H:i d/m/Y', $timestampEnd);
        }

        if ($timestampNow > $timestampEnd) {
            $return = "O prazo limite para atualização de senha foi ultrapassado ($showEnd). <br/>Realize uma nova solicitação, e tente novamente!<br/><br/>";
        } else {
            $return = true;
        }
        return $return;
    }
    // ####################################################################################################
    // ####################################################################################################
    // ####################################################################################################
    // ####################################################################################################
}

?>
