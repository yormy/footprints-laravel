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
    }
