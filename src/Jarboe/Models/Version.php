<?php

namespace Yaro\Jarboe\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $version_id
 * @property string $versionable_id
 * @property string $versionable_type
 * @property int|null $user_id
 * @property string|null $auth_guard
 * @property string $model_data
 * @property string|null $reason
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read $responsible_user
 * @property Model $versionable
 */
class Version extends Model
{
    /**
     * @var string
     */
    public $table = 'versions';

    /**
     * @var string
     */
    protected $primaryKey = 'version_id';

    private $responsibleUser = false;

    /**
     * Sets up the relation
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function versionable()
    {
        return $this->morphTo();
    }

    /**
     * Return the user responsible for this version
     * @return mixed
     */
    public function getResponsibleUserAttribute()
    {
        if ($this->responsibleUser !== false) {
            return $this->responsibleUser;
        }

        $this->responsibleUser = null;

        $isAuthGuardExists = !is_null(config('auth.guards.'. $this->auth_guard));
        $hasUserTrace = !is_null($this->user_id);
        if ($hasUserTrace && $isAuthGuardExists) {
            $this->responsibleUser = auth()->guard($this->auth_guard)->getProvider()->retrieveById($this->user_id);
        }

        return $this->responsibleUser;
    }

    /**
     * Return the versioned model
     * @return Model
     */
    public function getModel()
    {
        $modelData = $this->model_data;
        if (is_resource($this->model_data)) {
            $modelData = stream_get_contents($this->model_data, -1, 0);
        }

        $model = new $this->versionable_type();
        $model->unguard();
        $model->fill(unserialize($modelData));
        $model->exists = true;
        $model->reguard();
        return $model;
    }


    /**
     * Revert to the stored model version make it the current version
     *
     * @return Model
     */
    public function revert()
    {
        $model = $this->getModel();
        unset($model->{$model->getCreatedAtColumn()});
        unset($model->{$model->getUpdatedAtColumn()});
        if (method_exists($model, 'getDeletedAtColumn')) {
            unset($model->{$model->getDeletedAtColumn()});
        }
        $model->save();

        return $model;
    }

    /**
     * Diff the attributes of this version model against another version.
     * If no version is provided, it will be diffed against the current version.
     *
     * @param Version|null $againstVersion
     * @return array
     */
    public function diff(Version $againstVersion = null)
    {
        $model = $this->getModel();
        $diff = $againstVersion ? $againstVersion->getModel() : $this->versionable()->withTrashed()->first()->currentVersion()->getModel();

        $diffArray = array_diff_assoc($diff->getAttributes(), $model->getAttributes());

        if (isset($diffArray[$model->getCreatedAtColumn()])) {
            unset($diffArray[$model->getCreatedAtColumn()]);
        }
        if (isset($diffArray[$model->getUpdatedAtColumn()])) {
            unset($diffArray[$model->getUpdatedAtColumn()]);
        }
        if (method_exists($model, 'getDeletedAtColumn') && isset($diffArray[$model->getDeletedAtColumn()])) {
            unset($diffArray[$model->getDeletedAtColumn()]);
        }

        return $diffArray;
    }
}
