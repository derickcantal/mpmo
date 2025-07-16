<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use \Carbon\Carbon;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $connection = 'mysql';
    protected $table = 'mpmo.users';  
    protected $primaryKey = 'userid';

    protected $dates = [
        'timerecorded',
    ];
    public function gettimerecordedAttribute($dates) {
        return \Carbon\Carbon::parse($dates)->format('Y-m-d h:i:s A');
    }
    
     protected static function booted()
    {
        static::creating(function ($user) {
            // e.g. 8‑character uppercase token
            $user->referral_code = strtoupper(Str::random(8));
        });
    }

    

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'avatar',
        'referral_code',
        'fullname',
        'cwid',
        'trx_balance',
        'mpmo_balance',
        'birthdate',
        'email',
        'mobile_primary',
        'mobile_secondary',
        'homeno',
        'notes',
        'email_verified_at',
        'password',
        'accesstype',
        'role',
        'timerecorded',
        'created_by',
        'updated_by',
        'mod',
        'copied',
        'referred_by',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function transactions() 
    { 
        return $this->hasMany(transactions::class, 'userid', 'userid'); 
    }
    public function wallets()
    {
        return $this->hasMany(cwallet::class, 'userid', 'userid');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    protected $appends = [
        'cwaddress',
        'first_cwaddress',
        'trx_balance',
        'mpmo_balance',
    ];

    public function getFirstCwaddressAttribute()
    {
        return optional($this->wallets->first())->cwaddress;
    }

    public function getCwaddressAttribute()
    {
        return optional($this->wallets)->cwaddress;
    }

    public function getTrxBalanceAttribute()
    {
        return $this->wallets->sum('trx_balance');
        // return optional($this->wallets)->trx_balance;
    }

    public function getMpmoBalanceAttribute()
    {
        return $this->wallets->sum('mpmo_balance');
        // return optional($this->wallets)->mpmo_balance;
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }


}
