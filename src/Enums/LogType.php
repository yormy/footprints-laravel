<?php

    enum LogType: string
    {
        case MODEL_CREATED = 'MODEL_CREATED';
        case MODEL_DELETED = 'MODEL_DELETED';
        case MODEL_UPDATED = 'MODEL_UPDATED';

        case ROUTE = 'ROUTE';

        case AUTH_LOGIN = 'AUTH_LOGIN';
        case AUTH_LOGOUT = 'AUTH_LOGOUT';
        case AUTH_LOCKEDOUT = 'AUTH_LOCKEDOUT';

        case AUTH_FAILED = 'AUTH_FAILED';
        case AUTH_OTHER_DEVICE_LOGOUT = 'AUTH_OTHER_DEVICE_LOGOUT';

    }
