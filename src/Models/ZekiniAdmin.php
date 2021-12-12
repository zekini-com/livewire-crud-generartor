<?php
namespace Zekini\CrudGenerator\Models;

use Illuminate\Notifications\Notifiable;
use Zekini\CrudGenerator\Notification\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Zekini\CrudGenerator\Factory\ZekiniAdminFactory;

class ZekiniAdmin extends Authenticatable
{

    use HasFactory, Notifiable, HasRoles;


    protected static function newFactory()
    {
        return ZekiniAdminFactory::new();
    }

      /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(app(ResetPassword::class, ['token' => $token]));
    }

}