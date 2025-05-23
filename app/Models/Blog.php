<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use SoftDeletes, HasFactory;

    protected $dates = ['deleted_at'];
    protected $fillable = ['title', 'content', 'seo_title', 'seo_keywords', 'seo_description', 'subdomain_id'];

}
