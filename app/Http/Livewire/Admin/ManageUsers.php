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
    public $filterBanned = '';

    protected $listeners = [
        'refreshUsers' => '$refresh',
    ];

    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage(); 
        
    }

    public function updatingFilterBanned()
    {
        $this->resetPage(); 
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';
    
        $users = User::where('role', 'customer')
            ->where(function ($query) use ($searchTerm) {
                $query->whereRaw('LOWER(name) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(email) LIKE ?', [$searchTerm]);
            });
            
    
        if ($this->filterBanned === 'banned') {
            $users = $users->whereNotNull('banned_until')->where('banned_until', '>', now());
        } elseif ($this->filterBanned === 'unbanned') {
            $users = $users->where(function ($query) {
                $query->whereNull('banned_until')
                      ->orWhere('banned_until', '<', now());
            });
        }
    
        $users = $users->paginate(10);
    
        return view('livewire.admin.manage-users', [
            'users' => $users->through(function ($user) {
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
