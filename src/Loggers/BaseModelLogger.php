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
    public function created(Model $model): void
    {
        $this->log($model, ObserverActionsEnum::CREATED->value, attributes: $model->getAttributes());
    }

    protected function log(Model $model, string $event, ?string $description = null, mixed $attributes = null): void
    {
        if (! $this->shouldLogEvent($model, $event)) {
            return;
        }

        if (is_null($description)) {
            $description = $this->getModelName($model).' '.$event;
        }

        if (auth()->check()) {
            $description .= ' by '.$this->getCauserIdentifier(auth()->user());
        }

        $this->activityLogger()
            ->event($event)
            ->performedOn($model)
            ->withProperties($this->getLoggableAttributes($model, $attributes))
            ->log($description);
    }

    protected function shouldLogEvent(Model $model, string $event): bool
    {
        $modelClass = get_class($model);
        $eventsToLog = config("simple-activity-log.events.{$modelClass}", []);

        if (empty($eventsToLog)) {
            return true;
        }

        return in_array($event, $eventsToLog);
    }

    protected function getModelName(Model $model): string
    {
        return Str::of(class_basename($model))->headline();
    }

    protected function getCauserIdentifier(?Authenticatable $user): string
    {
        if (blank($user) || $user instanceof GenericUser) {
            return 'Anonymous';
        }

        return $user->{config('simple-activity-log.causer_identifier', 'name')} ?? $user->getAuthIdentifier();
    }

    protected function activityLogger(?string $logName = null): ActivityLogger
    {
        $defaultLogName = $this->getLogName();

        $logStatus = app(ActivityLogStatus::class);

        return app(ActivityLogger::class)
            ->useLog($logName ?? $defaultLogName)
            ->setLogStatus($logStatus);
    }

    abstract protected function getLogName(): string;

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

    public function updated(Model $model): void
    {
        $changes = $model->getChanges();

        // For modes that have a remember_token field
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
