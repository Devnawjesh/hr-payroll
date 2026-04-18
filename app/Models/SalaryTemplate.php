<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryTemplate extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_salary_templates')
            ->withPivot(['pay_frequency', 'effective_from', 'effective_to'])
            ->withTimestamps();
    }
}
