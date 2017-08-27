<?php

namespace SocialiteProviders\XenForo;

use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider implements ProviderInterface
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'XENFORO';

    const ADMIN_ROLE = 3;

    /**
     * {@inheritdoc}
     */
    protected $scopes = ['read'];

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(env('XENFORO_SOCIALITE_HOST') . 'oauth/authorize', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return env('XENFORO_SOCIALITE_HOST') . 'oauth/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(env('XENFORO_SOCIALITE_HOST') . 'users/me?oauth_token='. $token);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'       => $user['user']['user_id'],
            'nickname' => $user['user']['username'],
            'name'     => $user['user']['username'],
            'email'    => $user['user']['user_email'],
            'avatar'    => $user['user']['links']['avatar'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code'
        ]);
    }
}
