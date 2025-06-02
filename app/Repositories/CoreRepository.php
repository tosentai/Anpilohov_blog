<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application; // Додаємо цей use для коректної підказки типів

/**
 * Class CoreRepository.
 *
 * Репозиторій для роботи з сутністю.
 * Може видавати набори даних.
 * Не може змінювати та створювати сутності.
 */
abstract class CoreRepository
{
    /**
     * @var Model
     */
    protected $model;

    /** CoreRepository constructor */
    public function __construct()
    {
        // app() - це помічник Laravel, який вирішує залежності з контейнера сервісів.
        // Ми передаємо йому назву класу моделі, яку повертає getModelClass().
        // Таким чином, $this->model буде екземпляром конкретної моделі (наприклад, BlogCategory).
        $this->model = app($this->getModelClass());
    }

    /**
     * Отримати назву класу моделі.
     *
     * @return string
     */
    abstract protected function getModelClass(): string; // Вказуємо тип повернення string

    /**
     * Отримати "свіжий" екземпляр моделі для початку ланцюжка запитів.
     *
     * @return Model|\Illuminate\Database\Eloquent\Builder
     */
    protected function startConditions()
    {
        // clone $this->model створює новий екземпляр моделі,
        // щоб уникнути модифікації оригінального об'єкта $this->model
        // при побудові запитів.
        return clone $this->model;
    }
}
