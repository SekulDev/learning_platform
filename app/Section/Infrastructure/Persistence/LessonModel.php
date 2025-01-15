<?php

namespace App\Section\Infrastructure\Persistence;

use App\Group\Infrastructure\Persistence\GroupModel;
use App\Section\Domain\Models\Lesson;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LessonModel extends Model
{
    protected $table = 'lessons';

    protected $fillable = [
        'title',
        'content',
    ];

    protected $casts = [
        'content' => 'array'
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(SectionModel::class);
    }

    public function visibleGroups(): BelongsToMany
    {
        return $this->belongsToMany(GroupModel::class, 'lesson_group_visibility');
    }

    public function toLesson(): Lesson
    {
        return new Lesson(
            $this->id,
            $this->title,
            $this->content
        );
    }
}
