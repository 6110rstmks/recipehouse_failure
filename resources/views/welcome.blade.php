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
                    {{-- <div class="form-box">
                        <h4 style="margin-bottom: 20px; margin-top: 10px">RECIPE HOUSE</h4>
                        <form method="post" action="{{ route('categories.store') }}">
                            @csrf
                            <p class="control has-icons-left has-icons-right">
                                    <input class="input title-input" type="text" name="title" placeholder="enter category name">
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-utensils"></i>
                                    </span>
                            </p>
                        </form>
                    </div> --}}
                    <category-list></category-list>
                </div>
                <div class="right-container">
                    <router-view name="Max"></router-view>
                    <router-view></router-view>
                </div>
            </div>
        </div>
        @vite('resources/js/app.js')
    </body>
</html>
