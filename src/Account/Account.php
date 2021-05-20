<?php
/**
 * User: Maros Jasan
 * Date: 30.11.2016
 * Time: 14:58
 */

namespace Geodeticca\Iam\Account;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

use Geodeticca\Iam\User\PolicyManagement;
use Geodeticca\Iam\User\Authority;
use Geodeticca\Iam\User\HasPolicy;
use Geodeticca\Iam\Group\HasGroups;
use Geodeticca\Iam\Organization\HasOrganizations;
use Geodeticca\Iam\App\HasApps;

class Account implements AuthenticatableContract, PolicyManagement, \JsonSerializable
{
    use Authenticatable, HasGroups, HasOrganizations, HasApps, HasPolicy;

    /**
     * @var int
     */
    public $user_id;

    /**
     * @var string
     */
    public $forename;

    /**
     * @var string
     */
    public $surname;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var string
     */
    public $authority = Authority::AUTHORITY_REGULAR;

    /**
     * @var array
     */
    public $access = [];

    /**
     * @return string
     */
    public function getName()
    {
        return trim(ucfirst(mb_strtolower($this->forename)) . ' ' . ucfirst(mb_strtolower($this->surname)));
    }

    /**
     * @param array $data
     * @return $this
     */
    public function hydrate(array $data)
    {
        if (array_key_exists('user_id', $data)) {
            $this->user_id = (int)$data['user_id'];
        }
        if (array_key_exists('forename', $data)) {
            $this->forename = $data['forename'];
        }
        if (array_key_exists('surname', $data)) {
            $this->surname = $data['surname'];
        }
        if (array_key_exists('email', $data)) {
            $this->email = $data['email'] ?? null;
        }
        if (array_key_exists('login', $data)) {
            $this->login = $data['login'] ?? null;
        }
        if (array_key_exists('phone', $data)) {
            $this->phone = $data['phone'] ?? null;
        }
        if (array_key_exists('remember_token', $data)) {
            $this->remember_token = $data['remember_token'];
        }
        if (array_key_exists('authority', $data)) {
            $this->authority = $data['authority'];
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'user_id' => $this->user_id,
            'forename' => $this->forename,
            'surname' => $this->surname,
            'email' => $this->email,
            'login' => $this->login,
            'phone' => $this->phone,
            'remember_token' => $this->remember_token,
            'authority' => $this->authority,
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $data = $this->toArray();

        unset($data['password']);
        unset($data['remember_token']);

        return array_merge($data, [
            'name' => $this->getName(),
            'groups' => $this->getGroups(),
            'organizations' => $this->getOrganizations(),
            'apps' => $this->getApps(),
            'policy' => $this->getPolicy(),
            'access' => $this->getAccess(),
        ]);
    }

    /**
     * @return array
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * @param string $scope
     * @param int $permission
     * @return $this
     */
    public function setAccess($scope, $permission)
    {
        if ($permission >= 0 && $permission <= 7) {
            $this->access[$scope] = (int)$permission;
        }

        return $this;
    }

    /**
     * @param string $scope
     * @param int $permission
     * @return $this
     */
    public function addAccess($scope, $permission)
    {
        if ($permission >= 0 && $permission <= 7) {
            $access = $this->access[$scope] ?? 0;

            $this->access[$scope] = (int)$access | (int)$permission;
        }

        return $this;
    }

    /**
     * @param string $scope
     * @param int $action
     * @return int
     */
    public function hasAccess($scope, $action)
    {
        if ($this->isAdmin()) {
            return 7;
        }

        if ($this->hasAdminPolicy()) {
            return 7;
        }

        $access = $this->access[$scope] ?? 0;

        return (int)$access & (int)$action;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->authority === Authority::AUTHORITY_ADMIN;
    }

    /**
     * @return bool
     */
    public function isNotAdmin()
    {
        return !$this->isAdmin();
    }

    /**
     * @return bool
     */
    public function isSystemUser()
    {
        return $this->authority === Authority::AUTHORITY_SYSTEM;
    }

    /**
     * @return bool
     */
    public function isNotSystemUser()
    {
        return !$this->isSystemUser();
    }

    /**
     * @return bool
     */
    public function isRegular()
    {
        return $this->authority === Authority::AUTHORITY_REGULAR;
    }

    /**
     * @return bool
     */
    public function isNotRegular()
    {
        return !$this->isRegular();
    }

    /**
     * @return bool
     */
    public function hasAdminRights()
    {
        return $this->isAdmin() || $this->hasAdminPolicy();
    }

    /**
     * @return bool
     */
    public function hasNoAdminRights()
    {
        return !$this->hasAdminRights();
    }

    /**
     * @param array $data
     * @return \Geodeticca\Iam\Account\Account
     */
    public static function createFromJwt(array $data)
    {
        $account = new self();
        $account->hydrate($data);

        if (array_key_exists('groups', $data)) {
            $account->setGroups((array)$data['groups']);
        }

        if (array_key_exists('organizations', $data)) {
            $account->setOrganizations((array)$data['organizations']);
        }

        if (array_key_exists('apps', $data)) {
            $account->setApps((array)$data['apps']);
        }

        if (array_key_exists('policy', $data)) {
            $account->policy = (array)$data['policy'];
        }

        if (array_key_exists('access', $data)) {
            $account->access = (array)$data['access'];
        }

        return $account;
    }
}
