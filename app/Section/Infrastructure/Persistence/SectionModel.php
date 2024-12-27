<?php

namespace App\Section\Infrastructure\Persistence;

use App\Section\Domain\Models\Section;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SectionModel extends Model
{
    protected $table = 'sections';

    protected $fillable = [
        'name',
        'owner_id',
    ];

    public function lessons(): HasMany
    {
        return $this->hasMany(LessonModel::class);
    }

    public function toSection(): Section
    {
        return new Section(
            $this->id,
            $this->name,
            $this->owner_id,
            $this->lessons()->get()->map(fn($lesson) => $lesson->toLesson())->toArray()
        );
    }
}
