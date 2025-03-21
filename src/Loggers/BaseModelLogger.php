<?php

namespace Mrcookie\SimpleActivityLog\Loggers;

use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Mrcookie\SimpleActivityLog\Enums\ObserverActionsEnum;
use Spatie\Activitylog\ActivityLogger;
use Spatie\Activitylog\ActivityLogStatus;

abstract class BaseModelLogger
{
    abstract protected function getLogName(): string;

    protected function getCauserIdentifier(?Authenticatable $user): string
    {
        if (blank($user) || $user instanceof GenericUser) {
            return 'Anonymous';
        }

        return $user->{config('simple-activity-log.causer_identifier', 'name')} ?? $user->getAuthIdentifier();
    }

    protected function getModelName(Model $model): string
    {
        return Str::of(class_basename($model))->headline();
    }

    protected function activityLogger(?string $logName = null): ActivityLogger
    {
        $defaultLogName = $this->getLogName();

        $logStatus = app(ActivityLogStatus::class);

        return app(ActivityLogger::class)
            ->useLog($logName ?? $defaultLogName)
            ->setLogStatus($logStatus);
    }

    protected function getLoggableAttributes(Model $model, mixed $values = []): array
    {
        if (! is_array($values)) {
            return [];
        }

        if (count($model->getVisible()) > 0) {
            $values = array_intersect_key($values, array_flip($model->getVisible()));
        }

        if (count($model->getHidden()) > 0) {
            $values = array_diff_key($values, array_flip($model->getHidden()));
        }

        return $values;
    }

    protected function log(Model $model, string $event, ?string $description = null, mixed $attributes = null): void
    {
        $description ??= $this->getModelName($model).' '.$event;

        if (auth()->check()) {
            $description .= ' by '.$this->getCauserIdentifier(auth()->user());
        }

        $this->activityLogger()
            ->event($event)
            ->performedOn($model)
            ->withProperties($this->getLoggableAttributes($model, $attributes))
            ->log($description);
    }

    public function created(Model $model): void
    {
        $this->log($model, ObserverActionsEnum::CREATED->value, attributes: $model->getAttributes());
    }

    public function updated(Model $model): void
    {
        $changes = $model->getChanges();

        if (count($changes) === 1 && array_key_exists('remember_token', $changes)) {
            return;
        }

        $this->log($model, ObserverActionsEnum::UPDATED->value, attributes: $changes);
    }

    public function deleted(Model $model): void
    {
        $this->log($model, ObserverActionsEnum::DELETED->value);
    }

    public function restored(Model $model): void
    {
        $this->log($model, ObserverActionsEnum::RESTORED->value);
    }

    public function forceDeleted(Model $model): void
    {
        $this->log($model, ObserverActionsEnum::FORCE_DELETED->value);
    }
}
