<?php

namespace App\Services;

use OTPHP\TOTP;
use App\Models\User;
use BaconQrCode\Writer;
use App\Ldap\User as LdapUser;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use Throwable;

class UserService
{
    public function getLdapUser(User $user)
    {
        try {
            $id = preg_replace('/@cesnet\.cz$/', '', $user->uniqueid);
            return LdapUser::where('tcsPersonalID', '=', $id)->firstOrFail();
        } catch (Throwable $t) {
            abort(404);
        }
    }

    public function checkToken(User $user)
    {
        return !is_null($this->getLdapUser($user)->getFirstAttribute('tokenSeeds'));
    }

    public function getQrCode(User $user)
    {
        $otp = TOTP::create();
        $otp->setLabel("CESNET IdP ({$user->name})");

        $ldapUser = $this->getLdapUser($user);
        $ldapUser->tokenSeeds = $otp->getSecret();
        $ldapUser->save();

        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new SvgImageBackEnd(),
        );
        $writer = new Writer($renderer);
        $qrCode = $writer->writeString($otp->getProvisioningUri());

        return $qrCode;
    }

    public function disableTotp(User $user)
    {
        $ldapUser = $this->getLdapUser($user);
        $ldapUser->tokenSeeds = null;
        $ldapUser->save();
    }

    public function admin(User $user)
    {
        $user->admin = $user->admin ? false : true;
        $user->update();

        return $user->admin;
    }

    public function manager(User $user)
    {
        $user->manager = $user->manager ? false : true;
        $user->update();

        return $user->manager;
    }
}
