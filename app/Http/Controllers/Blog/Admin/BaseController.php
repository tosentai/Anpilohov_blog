<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Blog\BaseController as GuestBaseController;

abstract class BaseController extends GuestBaseController
{
    /**
     * BaseController constructor
     */
    public function __construct()
    {
        // Ініціалізація загальних елементів адмінки
        // Тут можна додати, наприклад, перевірку авторизації для адмін-панелі
    }
}
