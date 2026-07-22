<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Concerns\HasTeams;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Team|null $currentTeam
 * @property-read Collection<int, Team> $ownedTeams
 * @property-read Collection<int, Membership> $teamMemberships
 * @property-read Collection<int, Team> $teams
 */
#[Fillable(['name', 'email', 'phone', 'password', 'current_team_id', 'designation', 'company', 'about', 'profile_photo_url'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements PasskeyUser, FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, HasTeams, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable {
        // Both HasTeams and HasRoles define a `teams()` method.
        // We keep HasTeams::teams() (our BelongsToMany) and discard HasRoles::teams().
        HasTeams::teams insteadof HasRoles;
    }


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    /**
     * Determine if the user can access the Filament admin panel.
     * Only admin and instructor roles are allowed.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole(['admin', 'instructor', 'support']);
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        $initials = Str::initials($this->name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1).Str::substr($initials, -1)
            : $initials;
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the user's currently active (paid & not expired) subscription, if any.
     * An active subscription grants access to ALL courses.
     */
    public function activeSubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('ends_at', '>=', now())
            ->latest('ends_at')
            ->first();
    }

    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription() !== null;
    }

    /**
     * A user has real access to a course if they hold a paid, non-expired
     * enrollment OR an active all-courses subscription.
     */
    public function isEnrolledIn($courseId): bool
    {
        if ($this->hasActiveSubscription()) {
            return true;
        }

        return $this->enrollments()
            ->where('course_id', $courseId)
            ->where('payment_status', 'paid')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            })
            ->exists();
    }
}
