@extends('layout.master')

@section('content')
<div class="page">
    <div class="o-container">
        <section class="u-margin__top--8">
            <article class="article u-display--flex u-align-content--center u-flex-direction--column">
                @paper([
                    'padding'=> 4, 
                    'classList' => ['o-grid-12', 'o-grid-4@md', 'o-grid-4@lg', 'u-width--100', 'u-align-self--center'],
                    'attributeList' => [
                        'style' => 'max-width: 700px;'
                    ]
                ])
                    @yield('article')
                @endpaper
            </article>
        </section>
    </div>
</div>
@stop