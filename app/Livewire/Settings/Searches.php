<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Searches extends Component
{
    public $keyword = '';
    public $suggestions = [];

    public function getSuggestions()
    {
        // Nếu keyword rỗng thì clear suggestions luôn
        if (trim($this->keyword) === '') {
            $this->suggestions = [];
            return;
        }

        $user = Auth::user();
        $roles = $user->roles;

        // Lấy tất cả permissions của user
        $userPermissions = $roles->flatMap(function ($role) {
            return $role->permissions;
        })->unique('name');

        $this->suggestions = $userPermissions
            ->filter(function ($permission) {
                return str_contains(strtolower($permission->display_name ?? ''), strtolower($this->keyword));
            })
            ->map(function ($permission) {
                return [
                    'route' => $permission->name ?? '#', 
                    'name' => $permission->display_name ?? '', 
                ];
            })
            ->values()
            ->toArray();
    }

    public function selectSuggestion($route)
    {
        if ($route && $route !== '#') {
            return redirect()->route($route);
        }
    }

    public function render()
    {
        return view('livewire.settings.searches');
    }
}
