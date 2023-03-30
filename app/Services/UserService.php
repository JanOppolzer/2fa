<?php

namespace App\Services;

use App\Ldap\User as LdapUser;
use App\Models\User;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use OTPHP\TOTP;
use Throwable;

class UserService
{
    public function getLdapUser(User $user)
    {
        try {
            $id = preg_replace('/@.*$/', '', $user->uniqueid);

            return LdapUser::where(config('ldap.user_id'), '=', $id)->firstOrFail();
        } catch (Throwable $t) {
            abort(404);
        }
    }

    public function checkToken(User $user)
    {
        return ! is_null($this->getLdapUser($user)->getFirstAttribute('tokenSeeds'));
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
}
