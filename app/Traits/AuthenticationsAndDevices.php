<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Traits;

trait AuthenticationsAndDevices
{
    /**
     * Has Many Authentications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function authentications()
    {
        return $this->hasMany(Authentication::class);
    }

    /**
     * Has Many Devices.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    /**
     * Has Many Logins.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logins()
    {
        $relation = $this->authentications();
        $relation->where('type', Authentication::TYPE_LOGIN);

        return $relation;
    }

    /**
     * Has Many Failed Logins.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fails()
    {
        $relation = $this->authentications();
        $relation->where('type', Authentication::TYPE_FAILED);

        return $relation;
    }

    /**
     * Has Many Lockouts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lockouts()
    {
        $relation = $this->authentications();
        $relation->where('type', Authentication::TYPE_LOCKOUT);

        return $relation;
    }
}