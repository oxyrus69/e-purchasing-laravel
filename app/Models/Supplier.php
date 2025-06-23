<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// File: app/Models/Supplier.php
class Supplier extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
}