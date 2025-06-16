@extends('layout.master')

@section('content')
<div class="page">
    <div class="o-container">
        <section class="u-margin__top--10">
            <article class="article u-display--flex u-align-content--center u-flex-direction--column">
                @paper([
                    'padding'=> 4, 
                    'classList' => ['o-grid-12', 'o-grid-4@md', 'o-grid-4@lg', 'u-width--100', 'u-align-self--center'],
                    'attributeList' => [
                        'style' => 'max-width: 900px;'
                    ]
                ])
                    @typography([
                        'element' => 'h1',
                        'classList' => ['u-color__text--primary', 'u-margin__bottom--2']
                    ])
                        Kontrollkompassen
                    @endtypography

                    @if ($flash->hasMessage('message'))
                        @notice([
                            'type' => 'info',
                            'message' => [
                                'text' => $flash->getFirstMessage('message'),
                                'size' => 'sm'
                            ],
                            'icon' => [
                                'name' => 'report',
                                'size' => 'md',
                                'color' => 'white'
                            ],
                            'classList' => ['u-margin__top--2']
                        ])
                        @endnotice
                    @endif

                    @yield('article')
                @endpaper
            </article>
        </section>
    </div>
</div>
@stop