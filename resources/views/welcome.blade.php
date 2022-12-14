{{-- // localで確認するときは、php artisan serve とnpm run devを実行してください
// 久しぶりに確認するときは忘れがち。 --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
        <title>Laravel</title>

        @vite('resources/css/app.css')
    </head>
    <body>
        <div id="app">
            <div class="container">
                <div class="left-container">
                    <category-list></category-list>
                </div>
                <div class="right-container">
                    <router-view name="Home"></router-view>
                    <router-view name="Show"></router-view>
                </div>
            </div>
        </div>
        @vite('resources/js/app.js')
    </body>
</html>
