<?php

namespace App;

enum UserRole:string
{
    case SUPERVISOR = 'supervisor';
    case EMPLOYEE = 'employee';
}
