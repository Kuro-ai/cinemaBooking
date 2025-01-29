<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Carbon\Carbon;
use Livewire\WithPagination;

class ManageUsers extends Component
{
    use WithPagination;

    public $search = '';
    public $banDuration = '';
    public $selectedUserId;

    protected $listeners = [
        'refreshUsers' => '$refresh',
    ];

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';

        return view('livewire.admin.manage-users', [
            'users' => User::where('role', 'customer')
                ->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', $searchTerm)
                          ->orWhere('email', 'like', $searchTerm);
                })
                ->paginate(10)
                ->through(function ($user) {
                    $user->is_banned = $user->banned_until && $user->banned_until->isFuture();
                    return $user;
                }),
        ]);
    }

    public function banUser($userId)
    {
        $this->selectedUserId = $userId;
    }

    public function confirmBan()
    {
        $this->validate([
            'banDuration' => 'required|integer|min:1',
        ]);

        $user = User::findOrFail($this->selectedUserId);
        $user->update([
            'banned_until' => Carbon::now()->addDays((int) $this->banDuration),
        ]);

        $this->reset(['selectedUserId', 'banDuration']);
        session()->flash('success', 'User banned successfully!');
    }

    public function unbanUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->update([
            'banned_until' => null,
        ]);

        session()->flash('success', 'User unbanned successfully!');
    }   
}

