<?php

namespace backoffice\models;

use common\models\BackofficeUser;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    /**
     * @var string|null
     */
    public ?string $username = null;
    /**
     * @var string|null
     */
    public ?string $password = null;
    /**
     * @var bool
     */
    public bool $rememberMe = true;

    /**
     * @var BackofficeUser|null
     */
    private ?BackofficeUser $_user = null;


    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array|null $params the additional name-value pairs given in the rule
     */
    public function validatePassword(string $attribute, ?array $params = null): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return BackofficeUser|null
     */
    protected function getUser(): ?BackofficeUser
    {
        if ($this->_user === null) {
            $this->_user = BackofficeUser::findByUsername($this->username);
        }

        return $this->_user;
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login(): bool
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }
}
