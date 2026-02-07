<?php

use Illuminate\Support\Facades\Schedule;


//Commands
 Schedule::command('activitylog:clean')->daily();
