<?php

namespace dvll\KirbyWiki;

use Kirby\Cms\App;
use Kirby\Cms\File;
use Kirby\Cms\Page;
use Kirby\Cms\User;
use Kirby\Toolkit\Str;

class WikiAccess
{
    public const GUEST_SESSION_KEY = 'dvll.wiki.guest';
    public const PROTECTED_FILE_TEMPLATE = 'wiki-download';

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
        if (self::env('WIKI_GUEST_LOGIN_ENABLED', false) !== true) {
            return false;
        }

        return self::guestPasswordHash() !== '';
    }

    public static function currentUserCanAccess(?App $kirby = null): bool
    {
        return self::userCanAccess(($kirby ?? kirby())->user());
    }

    public static function currentVisitorCanAccessPage(?Page $page, ?App $kirby = null): bool
    {
        if (self::isWikiPage($page) === false) {
            return false;
        }

        return self::currentUserCanAccess($kirby) || self::hasGuestAccess($kirby);
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

    public static function isProtectedWikiFile(?File $file): bool
    {
        if ($file === null) {
            return false;
        }

        return $file->template() === self::PROTECTED_FILE_TEMPLATE
            && self::isWikiPage($file->page());
    }

    public static function protectedDownloadUrl(?File $file): ?string
    {
        if ($file === null) {
            return null;
        }

        if (self::isProtectedWikiFile($file) === false) {
            return $file->mediaUrl();
        }

        self::purgePublicCopies($file);

        return url('wiki-download/' . $file->uuid()->id());
    }

    public static function purgePublicCopies(?File ...$files): void
    {
        foreach ($files as $file) {
            if (self::isProtectedWikiFile($file) === true) {
                $file->unpublish();
            }
        }
    }

    public static function findProtectedWikiFile(string $fileId, ?App $kirby = null): ?File
    {
        $fileId = trim($fileId);

        if ($fileId === '') {
            return null;
        }

        $file = ($kirby ?? kirby())->file('file://' . $fileId);

        if ($file instanceof File && self::isProtectedWikiFile($file)) {
            return $file;
        }

        return null;
    }

    protected static function env(string $key, mixed $default = null): mixed
    {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        if (Str::startsWith($value, '"') && Str::endsWith($value, '"')) {
            return substr($value, 1, -1);
        }

        return $value;
    }

    protected static function guestPasswordHash(): string
    {
        $hash = self::env('WIKI_GUEST_PASSWORD_HASH', '');

        return is_string($hash) ? trim($hash) : '';
    }
}