<?php
namespace application\controllers\frontend;

use manguto\cms7\application\core\Controller;
use manguto\cms7\application\core\Route;
use manguto\cms7\application\core\View;

class ControllerForgot extends Controller
{

    static function RouteMatchCheck(Route $route)
    {
        // ======================================================================================================================
        $route->get('/forgot', function () {            
            View::PageFrontend("forgot");
        });
        // ======================================================================================================================
        /*
         * $route->post('/forgot', function () {
         *
         * User_password_recover::ProcessStart(trim($_POST['email'] ?? ''));
         * Controller::HeaderLocation('/forgot');
         * });/*
         */
        // ======================================================================================================================
        /*
         * $route->get('/forgot/reset/:code', function ($code) {
         * $result = User_password_recover::checkCode__getSelf($code, false);
         * if (! is_string($result)) {
         * $result->loadReferences();
         * {
         * $pars = [
         * 'user' => $result->getUser(),
         * 'code' => $code
         * ];
         * }
         * View::PageFrontend("forgot-reset", $pars);
         * } else {
         * Alert::Error($result);
         * Controller::HeaderLocation('/login');
         * }
         * });/*
         */
        // ======================================================================================================================
        /*
         * $route->post('/forgot/reset', function () {
         * // deb($_POST);
         * $code = isset($_POST['code']) ? trim($_POST['code']) : '';
         * $password = isset($_POST['password']) ? trim($_POST['password']) : '';
         * $password2 = isset($_POST['password2']) ? trim($_POST['password2']) : '';
         * // ----------------------------------------------------------------------------------
         * $result = User_password_recover::checkCode__updateUserPassword($code, $password, $password2);
         * // ----------------------------------------------------------------------------------
         * if ($result === true) {
         * $msg = 'A senha do usuário foi atualizada com sucesso!<br/>';
         * $msg .= 'Realize o login no sistema para confirmar esta atualização!<br/>';
         * Alert::Success($msg);
         * } else {
         * Alert::Error($result);
         * }
         * Controller::HeaderLocation('/login');
         * });/*
         */
        // ======================================================================================================================
    }
}

?>