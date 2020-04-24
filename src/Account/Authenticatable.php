<?php
/**
 * User: Maros Jasan
 * Date: 21.11.2019
 * Time: 11:06
 */

namespace Geodeticca\Iam\Account;

trait Authenticatable
{
    use AuthIdentifierManage, PasswordManage, RememberTokenManage;
}
