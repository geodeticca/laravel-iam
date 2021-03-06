<?php
/**
 * User: Maros Jasan
 * Date: 30.11.2016
 * Time: 14:58
 */

namespace Geodeticca\Iam\Account;

use Illuminate\Contracts\Auth\Authenticatable;

class Account implements \JsonSerializable, Authenticatable
{
    use AuthIdentifierManage, RememberTokenManage;

    /**
     * @var int
     */
    public $user_id;

    /**
     * @var int
     */
    public $group_id;

    /**
     * @var int
     */
    public $organization_id;

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
    public $authority = AccountAuthority::AUTHORITY_REGULAR;

    /**
     * @var array
     */
    public $access = [];

    /**
     * @var array
     */
    public $policy = [];

    /**
     * @param array $data
     * @return $this
     */
    public function hydrate(array $data)
    {
        if (array_key_exists('user_id', $data)) {
            $this->user_id = (int)$data['user_id'];
        }
        if (array_key_exists('group_id', $data)) {
            $this->group_id = (int)$data['group_id'];
        }
        if (array_key_exists('organization_id', $data)) {
            $this->organization_id = (int)$data['organization_id'];
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
        return array_merge($this->toArray(), [
            'group_id' => $this->group_id,
            'organization_id' => $this->organization_id,
            'name' => $this->getName(),
            'access' => $this->getAccess(),
            'policy' => $this->getPolicy(),
        ]);
    }

    /**
     * @param string $scope
     * @param int $permission
     * @return $this
     */
    public function addAccess($scope, $permission)
    {
        if ($permission > 0 && $permission <= 7) {
            $this->access[$scope] = (int)$permission;
        }

        return $this;
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
     * @param int $action
     * @return int
     */
    public function hasAccess($scope, $action)
    {
        if ($this->isAdmin()) {
            return 7;
        }

        $permission = $this->access[$scope] ?? 0;

        return $permission & $action;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->authority === AccountAuthority::AUTHORITY_ADMIN;
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
        return $this->authority === AccountAuthority::AUTHORITY_SYSTEM;
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
        return $this->authority === AccountAuthority::AUTHORITY_REGULAR;
    }

    /**
     * @return bool
     */
    public function isNotRegular()
    {
        return !$this->isRegular();
    }

    /**
     * @param string $group
     * @param string $authority
     * @return $this
     */
    public function addPolicy($group, $authority)
    {
        $this->policy[$group] = $authority;

        return $this;
    }

    /**
     * @return array
     */
    public function getPolicy()
    {
        return $this->policy;
    }

    /**
     * @return bool
     */
    public function hasAdminPolicy()
    {
        return in_array(AccountAuthority::AUTHORITY_ADMIN, $this->policy);
    }

    /**
     * @return bool
     */
    public function hasNoAdminPolicy()
    {
        return !$this->hasAdminPolicy();
    }

    /**
     * @return bool
     */
    public function hasManagerPolicy()
    {
        return in_array(AccountAuthority::AUTHORITY_MANAGER, $this->policy);
    }

    /**
     * @return bool
     */
    public function hasNoManagerPolicy()
    {
        return !$this->hasManagerPolicy();
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
     * @return string
     */
    public function getName()
    {
        return trim(ucfirst(mb_strtolower($this->forename)) . ' ' . ucfirst(mb_strtolower($this->surname)));
    }

    /**
     * @param array $data
     * @return \Geodeticca\Iam\Account\Account
     */
    public static function createFromJwt(array $data)
    {
        $account = new self();
        $account->hydrate($data);

        if (array_key_exists('access', $data)) {
            $account->access = (array)$data['access'];
        }

        if (array_key_exists('policy', $data)) {
            $account->policy = (array)$data['policy'];
        }

        return $account;
    }

    /**
     * @return bool
     */
    public function belongsToGroup()
    {
        return !is_null($this->group_id);
    }

    /**
     * @return bool
     */
    public function belongsToOrganization()
    {
        return !is_null($this->organization_id);
    }

    /**
     * Required for Laravel's Authenticatable interface compatibility
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return '';
    }
}
