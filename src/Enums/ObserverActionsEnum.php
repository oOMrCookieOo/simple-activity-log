<?php

namespace Mrcookie\SimpleActivityLog\Enums;

enum ObserverActionsEnum : string
{

    case CREATED = 'created';
    case UPDATED = 'updated';
    case DELETED = 'deleted';
    case RESTORED = 'restored';
    case FORCE_DELETED = 'force_deleted';
}
