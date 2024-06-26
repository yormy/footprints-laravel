<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Enums;

enum LogType: string
{
    case UNKNOWN = 'UNKNOWN';

    case MODEL_CREATED = 'MODEL_CREATED';
    case MODEL_DELETED = 'MODEL_DELETED';
    case MODEL_UPDATED = 'MODEL_UPDATED';

    case ROUTE_VISIT = 'ROUTE_VISIT';

    case AUTH_LOGIN = 'AUTH_LOGIN';
    case AUTH_LOGOUT = 'AUTH_LOGOUT';
    case AUTH_LOCKEDOUT = 'AUTH_LOCKEDOUT';

    case AUTH_FAILED = 'AUTH_FAILED';
    case AUTH_OTHER_DEVICE_LOGOUT = 'AUTH_OTHER_DEVICE_LOGOUT';

    case EXCEPTION_UNSPECIFIED = 'EXCEPTION_NOT_SPECIFIED';

    case EXCEPTION_PAGE_NOT_FOUND = 'EXCEPTION_PAGE_NOT_FOUND';
    case EXCEPTION_MODEL_NOT_FOUND = 'EXCEPTION_MODEL_NOT_FOUND';
    case EXCEPTION_TOO_MANY_REQUEST = 'EXCEPTION_TOO_MANY_REQUESTS';
}
