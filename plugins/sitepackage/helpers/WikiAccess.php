<?php

namespace dvll\Sitepackage\Helpers;

use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Cms\User;

class WikiAccess
{
    public const GUEST_SESSION_KEY = 'dvll.wiki.guest';

    public static function isWikiPage(?Page $page): bool
    {
        if ($page === null) {
            return false;
        }

        return $page->template()->name() === 'wiki'
            || $page->intendedTemplate()->name() === 'wiki';
    }

    public static function guestLoginEnabled(): bool
    {
        if (Helper::getEnv('WIKI_GUEST_LOGIN_ENABLED', false) !== true) {
            return false;
        }

        return self::guestPasswordHash() !== '';
    }

    public static function currentUserCanAccess(?App $kirby = null): bool
    {
        return self::userCanAccess(($kirby ?? kirby())->user());
    }

    public static function userCanAccess(?User $user): bool
    {
        if ($user === null) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        return (bool)$user->role()->permissions()->for('pages', 'read');
    }

    public static function hasGuestAccess(?App $kirby = null): bool
    {
        return ($kirby ?? kirby())->session()->get(self::GUEST_SESSION_KEY, false) === true;
    }

    public static function activateGuestAccess(?App $kirby = null): void
    {
        ($kirby ?? kirby())->session()->set(self::GUEST_SESSION_KEY, true);
    }

    public static function clearGuestAccess(?App $kirby = null): void
    {
        ($kirby ?? kirby())->session()->remove(self::GUEST_SESSION_KEY);
    }

    public static function verifyGuestPassword(string $password): bool
    {
        $hash = self::guestPasswordHash();

        if ($hash === '' || $password === '') {
            return false;
        }

        return password_verify($password, $hash);
    }

    protected static function guestPasswordHash(): string
    {
        $hash = Helper::getEnv('WIKI_GUEST_PASSWORD_HASH', '');

        return is_string($hash) ? trim($hash) : '';
    }
}
