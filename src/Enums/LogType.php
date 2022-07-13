<?php

    enum LogType: string
    {
        case CREATED = 'CREATED';
        case DELETED = 'DELETED';
        case UPDATED = 'UPDATED';

        case ROUTE = 'ROUTE';

        case LOGIN = 'LOGIN';
        case LOGOUT = 'LOGOUT';
        case LOCKEDOUT = 'LOCKEDOUT';

        case FAILED = 'FAILED';
        case OTHER_DEVICE_LOGOUT = 'AUTH_OTHER_DEVICE_LOGOUT';

    }
