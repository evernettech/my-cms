<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Blog;

class TrashBlogTable extends Component
{
    public function render()
    {
        return view('livewire.trash-blog-table',[
            'blogs' => Blog::onlyTrashed()->get(),
        ]);
    }
}
