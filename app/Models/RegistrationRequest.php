<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RegistrationRequest extends Model
{
    public const PICTURE_PATH = 'images/registrations/';

    public const REASONS = [
        'lost' => 'Mất thẻ',
        'damaged' => 'Thẻ bị hỏng',
        'photo' => 'Đổi ảnh thẻ',
    ];

    public const STATUSES = [
        'pending' => 'Đang chờ duyệt',
        'approved' => 'Đã duyệt',
        'rejected' => 'Từ chối',
    ];

    protected $fillable = [
        'code',
        'type',
        'user_id',
        'duplicate_user_id',
        'holyName',
        'fullName',
        'birthday',
        'address',
        'phone',
        'sector_id',
        'course_id',
        'nameFather',
        'phoneFather',
        'nameMother',
        'phoneMother',
        'godParent',
        'reason',
        'contact_phone',
        'picture',
        'note',
        'status',
        'admin_note',
        'reviewed_by',
        'reviewed_at',
        'ip_address',
    ];

    protected $casts = [
        'birthday' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public static function generateCode(): string
    {
        do {
            $code = 'DK' . now()->format('ymd') . '-' . Str::upper(Str::random(4));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function duplicateUser()
    {
        return $this->belongsTo(User::class, 'duplicate_user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'new' ? 'Đăng ký mới' : 'Cấp lại thẻ';
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getReasonLabelAttribute(): ?string
    {
        return self::REASONS[$this->reason] ?? null;
    }

    public function getPictureUrlAttribute(): ?string
    {
        return $this->picture ? asset(self::PICTURE_PATH . $this->picture) : null;
    }

    /** Tên hiển thị của em trong đơn (đơn cấp lại lấy từ tài khoản). */
    public function getChildNameAttribute(): string
    {
        if ($this->type === 'reissue' && $this->user) {
            return $this->user->FullName;
        }

        return trim($this->holyName . ' ' . $this->fullName);
    }
}
