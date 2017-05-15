<?php

namespace TimFeid\SlackLaravelMail\Controllers;

use App\Http\Controllers\Controller;

class SlackMailController extends Controller
{
    public function slackMail($name)
    {
        return app('slackmail.storage')->get($name);
    }
}
