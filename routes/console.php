<?php

use Illuminate\Support\Facades\Schedule;


//Commands
Schedule::command('activitylog:clean')->daily();
Schedule::command('telescope:prune')->daily();
