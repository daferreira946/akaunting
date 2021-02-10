<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

trait Relationships
{
    public function countRelationships($model, $relationships)
    {
        $counter = [];

        foreach ($relationships as $relationship => $text) {
            if (!$c = $model->$relationship()->count()) {
                continue;
            }

            $text = Str::contains($text, '::') ? $text : 'general.' . $text;
            $counter[] = $c . ' ' . strtolower(trans_choice($text, ($c > 1) ? 2 : 1));
        }

        return $counter;
    }

    /**
     * Mass delete relationships with events being fired.
     *
     * @param  $model
     * @param  $relationships
     *
     * @return void
     */
    public function deleteRelationships($model, $relationships)
    {
        foreach ((array) $relationships as $relationship) {
            if (empty($model->$relationship)) {
                continue;
            }

            $items = $model->$relationship->all();

            if ($items instanceof Collection) {
                $items = $items->all();
            }

            foreach ((array) $items as $item) {
                $item->delete();
            }
        }
    }
}
