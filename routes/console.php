<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('users:purge-unverified --days=7')->dailyAt('03:30');
