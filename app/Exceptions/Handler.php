<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Обработка ошибок при поиске модели по ID
        $this->renderable(function (ModelNotFoundException $e, $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                $model = basename(str_replace('\\', '/', $e->getModel()));
                
                return response()->json([
                    'message' => "Ресурс {$model} не найден",
                    'error' => 'resource_not_found',
                    'error_code' => 404001
                ], 404);
            }
        });

        // Обработка ошибок Route::resource при невалидных ID
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                // Проверяем вложенное исключение
                $previousException = $e->getPrevious();
                if ($previousException instanceof ModelNotFoundException) {
                    $model = basename(str_replace('\\', '/', $previousException->getModel()));
                    
                    return response()->json([
                        'message' => "Ресурс {$model} не найден",
                        'error' => 'resource_not_found',
                        'error_code' => 404001
                    ], 404);
                }
                
                return response()->json([
                    'message' => 'Запрашиваемый путь не найден',
                    'error' => 'route_not_found',
                    'error_code' => 404002
                ], 404);
            }
        });

        // Обработка ошибок базы данных (включая ошибки типов PostgreSQL)
        $this->renderable(function (QueryException $e, $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                $errorCode = $e->getCode();
                $errorMessage = $e->getMessage();
                
                // Ошибка типа "invalid input syntax for type bigint" (строка вместо числа)
                if (str_contains($errorMessage, 'SQLSTATE[22P02]') || str_contains($errorMessage, 'invalid input syntax')) {
                    return response()->json([
                        'message' => 'Некорректный формат данных',
                        'error' => 'invalid_data_format',
                        'error_code' => 400001,
                        'details' => 'Передано значение неверного типа данных'
                    ], 400);
                }
                
                // Ошибка нарушения FK
                if (str_contains($errorMessage, 'SQLSTATE[23503]') || str_contains($errorMessage, 'foreign key constraint')) {
                    return response()->json([
                        'message' => 'Нарушение ссылочной целостности',
                        'error' => 'foreign_key_violation',
                        'error_code' => 400002,
                        'details' => 'Невозможно выполнить операцию, так как это нарушает ссылочную целостность'
                    ], 400);
                }
                
                // Ошибка дубликата уникального значения
                if (str_contains($errorMessage, 'SQLSTATE[23505]') || str_contains($errorMessage, 'duplicate key')) {
                    return response()->json([
                        'message' => 'Дублирование уникального значения',
                        'error' => 'duplicate_entry',
                        'error_code' => 409001,
                        'details' => 'Запись с таким значением уже существует'
                    ], 409);
                }
                
                // Прочие ошибки базы данных
                return response()->json([
                    'message' => 'Ошибка операции с базой данных',
                    'error' => 'database_error',
                    'error_code' => 500001
                ], 500);
            }
        });

        // Обработка ошибок валидации
        $this->renderable(function (ValidationException $e, $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Ошибка валидации данных',
                    'error' => 'validation_failed',
                    'error_code' => 422001,
                    'errors' => $e->errors()
                ], 422);
            }
        });

        // Общий обработчик для остальных исключений в API
        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                if (config('app.debug')) {
                    return response()->json([
                        'message' => $e->getMessage(),
                        'error' => 'server_error',
                        'error_code' => 500000,
                        'exception' => get_class($e),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => collect($e->getTrace())->map(function ($trace) {
                            return [
                                'file' => $trace['file'] ?? null,
                                'line' => $trace['line'] ?? null,
                                'function' => $trace['function'] ?? null,
                                'class' => $trace['class'] ?? null,
                            ];
                        })->take(10)->toArray()
                    ], 500);
                }
                
                return response()->json([
                    'message' => 'Внутренняя ошибка сервера',
                    'error' => 'server_error',
                    'error_code' => 500000
                ], 500);
            }
        });
    }
} 