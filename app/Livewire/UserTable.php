<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class UserTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'asc';

    protected $paginationTheme = 'tailwind'; // for Tailwind-styled pagination
    protected $updatesQueryString = ['search', 'sortField', 'sortDirection', 'perPage'];


    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];


    public function updatingSearch()
    {
        $this->resetPage(); // reset to page 1 on new search
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->perPage = 10;
    }

    public function render()
    {
       $users = User::with('roles') // eager load roles
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orderBy('id', 'desc')
                ->paginate($this->perPage);


        return view('livewire.user-table', [
            'users' => $users,
            'perPage' => $this->perPage,
        ]);
    }
}
