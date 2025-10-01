<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'holyName',
        'lastName',
        'name',
        'account_code',
        'email',
        'picture',
        'bio',
        'phone',
        'birthday',
        'password',
        'reissue_count',
        'address',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birthday' => 'date',
    ];


    protected static function booted()
    {
        static::deleting(function ($user) {
            $path = 'images/users/';
            $old_picture = $user->getAttributes()['picture'];

            if ($old_picture && File::exists(public_path($path . $old_picture))) {
                File::delete(public_path($path . $old_picture));
            }
        });
    }

    public function getFullNameAttribute()
    {
        return implode(' ', array_filter([$this->holyName, $this->lastName, $this->name]));
    }

    public function getSimpleNameAttribute()
    {
        return trim($this->lastName . ' ' . $this->name);
    }


    public function getPictureAttribute($value)
    {
        $path = public_path('images/users/' . $value);
        return $value && file_exists($path) ? asset('/images/users/' . $value) : asset('/images/users/default-avatar.png');
    }

    public function hasCustomPicture(): bool
    {
        $picture = $this->attributes['picture'] ?? null;

        return !empty($picture) && file_exists(public_path('images/users/' . $picture));
    }

    public function getBirthdayAttribute()
    {
        return $this->attributes['birthday']
            ? \Carbon\Carbon::parse($this->attributes['birthday'])->format('d/m/Y')
            : null;
    }

    public function studentParent()
    {
        return $this->hasOne(StudentParent::class, 'user_id');
    }

    public function religiousProfile()
    {
        return $this->hasOne(StudentReligious::class, 'user_id');
    }

    public function journeysOfVocation()
    {
        return $this->hasOne(JourneyOfVocation::class, 'user_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasPermission($permission)
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('permissions.name', $permission);
        })->exists();
    }

    public function sectors()
    {
        return $this->belongsToMany(Sector::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function submittedAttendances()
    {
        return $this->hasMany(Attendance::class, 'submit_by');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    protected $appends = ['total_score'];
    protected $calculatedScore = null;
    public function getTotalScoreAttribute()
    {
        return $this->calculatedScore ?? 0;
    }
    public function setTotalScore(int $score)
    {
        $this->calculatedScore = $score;
    }
}
