<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use App\Traits\Logger;

abstract class Controller
{
    use ApiResponse, Logger;
}
