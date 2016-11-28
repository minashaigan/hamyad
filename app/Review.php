<?php

namespace App;



use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;


/**

 * Class Review

 *

 * @package App

 * @property string $comment

 * @property double $teacher_rate

 * @property double $video_rate

 * @property double $cat_rate

 * @property double $pack_rate

 */

class Review extends Model

{
    use CrudTrait;
    use SoftDeletes;
    protected $table='reviews';
    protected $fillable = ['comment','teacher_rate','pack_rate','user_id','course_rate'];

    /**
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        Review::observe(new \App\Observers\UserActionsObserver);
    }

    /**
     * ManyToMany with Course Table
     * Pivot table is course_reviews table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function courses()
    {
        return $this->belongsToMany('App\Course', 'course_reviews')
            ->withTimestamps();
    }

    /**
     * ManyToMany with Section Table
     * Pivot table is section_reviews table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sections()
    {
        return $this->belongsToMany('App\Section', 'section_reviews')
            ->withTimestamps();
    }

    /**
     * ManyToMany with TV table
     * Pivot table is tv_reviews table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teachers()
    {
        return $this->belongsToMany('App\Teacher', 'teacher_reviews')
            ->withTimestamps();
    }
}