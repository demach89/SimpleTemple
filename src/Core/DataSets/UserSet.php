<?php

namespace Core\DataSets;

require_once __DIR__ . "/../env.php";

/**
 * Набор данных юзера
 * Class UserSet
 * @package Core\DataSets
 */
class UserSet
{
    protected string $shortUserName;
    protected string $fullUserName;
    protected string $userEmail;

    public function __construct(string $username)
    {
        $this->set($username);
    }

    /**
     * Получить данные юзера
     * @return array
     */
    public function get() : array
    {
        return  [
            'shortUserName' => $this->shortUserName,
            'fullUserName'  => $this->fullUserName,
            'userEmail'     => $this->userEmail,
        ];
    }

    /**
     * Установить параметры юзера
     * @param string $username
     * @return void
     */
    protected function set(string $username) : void
    {
        if (!array_key_exists($username, USERS)) {
            $username = 'undefined';
        }

        $this->shortUserName = USERS[$username]['shortUserName'];
        $this->fullUserName  = USERS[$username]['fullUserName'];
        $this->userEmail     = USERS[$username]['userEmail'];
    }
}