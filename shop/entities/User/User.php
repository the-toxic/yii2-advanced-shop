<?php
namespace shop\entities\User;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use common\rbac\Roles;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $role
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email_confirm_token
 * @property string $email
 * @property string $phone
 * @property string $phone_confirmed
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 *
 * @property Network[] $networks
 * @property WishlistItem[] $wishlistItems
 */
class User extends ActiveRecord
{
    const STATUS_WAIT = 0;
    const STATUS_BLOCKED = -1;
    const STATUS_ACTIVE = 10;

    public static function create(string $username, string $email, string $phone, string $password, string $role): self
    {
        $user = new User();
        $user->username = $username;
        $user->role = $role;
        $user->email = $email;
        $user->phone = $phone;
        $user->setPassword(!empty($password) ? $password : Yii::$app->security->generateRandomString());
        $user->created_at = time();
        $user->status = self::STATUS_ACTIVE;
        $user->auth_key = Yii::$app->security->generateRandomString();
        return $user;
    }

    public function edit(string $username, string $email, string $phone, string $role): void
    {
        $this->username = $username;
        $this->role = $role;
        $this->email = $email;
        $this->phone = $phone;
        $this->updated_at = time();
    }

    public function editProfile(string $email, string $phone): void
    {
        $this->email = $email;
        $this->phone = $phone;
        $this->updated_at = time();

        if($this->phone_confirmed && $this->isAttributeChanged('phone')) {
            $this->phone_confirmed = 0;
        }
    }

    public function requestConfirmPhone($phone) :void
    {
        if (Yii::$app->session->has('new_phone_confirm_expire')
            && Yii::$app->session->get('new_phone_confirm_expire') > time()) {
            throw new \DomainException('Код уже выслан', 421);
        }
        Yii::$app->session->set('new_phone', $phone);
        Yii::$app->session->set('new_phone_confirm_code', random_int(10000, 99999));
        Yii::$app->session->set('new_phone_confirm_expire', time() + 180);
        Yii::$app->session->set('new_phone_confirm_limit', 3);
    }

    public function confirmPhone($code) :bool
    {
        if (!Yii::$app->session->has('new_phone'))
            throw new \DomainException('Подтверждение номера не запрошено', 422);

        if (Yii::$app->session->get('new_phone_confirm_expire') < time())
            throw new \DomainException('Истек срок действия кода, запросите новый', 423);

        if (Yii::$app->session->get('new_phone_confirm_limit') <= 0)
            throw new \DomainException('Превышено количество попыток введения кода', 424);

        if ($code == Yii::$app->session->get('new_phone_confirm_code')) {
            $this->phone = Yii::$app->session->get('new_phone');
            $this->phone_confirmed = 1;
            Yii::$app->session->remove('new_phone');
            Yii::$app->session->remove('new_phone_confirm_code');
            Yii::$app->session->remove('new_phone_confirm_expire');
            Yii::$app->session->remove('new_phone_confirm_limit');
            return true;
        }

        Yii::$app->session->set('new_phone_confirm_limit', Yii::$app->session->get('new_phone_confirm_limit') - 1);

        throw new \DomainException('Некорректный код', 425);
    }

    public static function requestSignup(string $username, string $email, string $phone, string $password): self
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->phone = $phone;
        $user->setPassword($password);
        $user->created_at = time();
        $user->status = self::STATUS_WAIT;
        $user->role = Roles::ROLE_USER;
        $user->email_confirm_token = Yii::$app->security->generateRandomString();
        $user->generateAuthKey();
        return $user;
    }

    public function confirmSignup(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException('User is already active.');
        }
        $this->status = self::STATUS_ACTIVE;
        $this->email_confirm_token = null;
    }

    public static function signupByNetwork($network, $identity, $attributes): self
    {
        $user = new User();
        $user->role = Roles::ROLE_USER;
        $user->created_at = time();
        $user->status = self::STATUS_ACTIVE;
        $user->generateAuthKey();
        // При создании юзера автоматически создаем запись в таблице user_networks
        // При помощи библиотеки la-haute-societe/yii2-save-relations-behavior
        $user->networks = [Network::create($network, $identity, $attributes)];
        return $user;
    }

    public function attachNetwork($network, $identity, $attributes): void
    {
        $networks = $this->networks;

        foreach ($networks as $current) {
            if ($current->isFor($network, $identity)) {
                throw new \DomainException('Network is already attached.');
            }
        }
        $networks[] = Network::create($network, $identity, $attributes);

        $this->networks = $networks;
    }

    public function addToWishList($productId): void
    {
        $items = $this->wishlistItems;
        foreach ($items as $item) {
            if ($item->isForProduct($productId)) {
                throw new \DomainException('Item is already added.');
            }
        }
        $items[] = WishlistItem::create($productId);
        $this->wishlistItems = $items;
    }

    public function removeFromWishList($productId): void
    {
        $items = $this->wishlistItems;
        foreach ($items as $i => $item) {
            if ($item->isForProduct($productId)) {
                unset($items[$i]);
                $this->wishlistItems = $items;
                return;
            }
        }
        throw new \DomainException('Item is not found.');
    }

    public function requestPasswordReset(): void
    {
        if (!empty($this->password_reset_token) && self::isPasswordResetTokenValid($this->password_reset_token)) {
            throw new \DomainException('Password resetting is already requested.');
        }
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function resetPassword($password): void
    {
        if (empty($this->password_reset_token)) {
            throw new \DomainException('Password resetting is not requested.');
        }
        $this->setPassword($password);
        $this->password_reset_token = null;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isBlocked(): bool
    {
        return $this->status === self::STATUS_BLOCKED;
    }

    public function phoneIsConfirmed(): bool
    {
        return $this->phone_confirmed === 1;
    }

    public function getNetworks(): ActiveQuery
    {
        return $this->hasMany(Network::className(), ['user_id' => 'id']);
    }

    public function getWishlistItems(): ActiveQuery
    {
        return $this->hasMany(WishlistItem::class, ['user_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => SaveRelationsBehavior::className(),
                'relations' => ['networks', 'wishlistItems'],
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    private function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    private function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
}
