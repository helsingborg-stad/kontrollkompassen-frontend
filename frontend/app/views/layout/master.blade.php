<!doctype html>
<html lang="sv">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <title>Helsingborg Stad - Kontrollkompassen</title>
    @if($assets)
        @foreach($assets as $asset)
            @if($asset['type'] === 'js')
                <script src="/assets/dist/{{ $asset['file'] }}"></script>
            @elseif($asset['type'] === 'css')
                <link rel='stylesheet' id='{{  $asset['id'] }}' href='/assets/dist/{{ $asset['file'] }}' media='all'>
            @endif
        @endforeach
    @endif

    <style type="text/css">
        @font-face {
          font-family: 'Material Icons';
          font-style: normal;
          font-weight: 400;
          src: url('/assets/fonts/material-icons/862dfb9f58cbd545e33ee554d41c6ec8.woff') format('woff');
        }
        .material-icons {
          font-family: 'Material Icons';
          font-weight: normal;
          font-style: normal;
          font-size: 24px;
          line-height: 1;
          letter-spacing: normal;
          text-transform: none;
          display: inline-block;
          white-space: nowrap;
          word-wrap: normal;
          direction: ltr;
          text-rendering: optimizeLegibility;
          -webkit-font-smoothing: antialiased;
        }
        @include('themes.helsingborg')
    </style>
</head>
<body class="no-js">
    {{-- App not built correctly message. --}}
    @if(!$assets) 
        @include('notices.assets')
    @endif

    @if($isAuthenticated)
        <div class="c-logout u-padding__y--2 u-padding__x--3">
            @avatar([
                'name' => $formattedUser->firstname . " " . $formattedUser->lastname,
                'size' => 'sm'
            ])
            @endavatar
            <div>
                <div class="c-logout__name">
                    {{ $formattedUser->firstname }} {{ $formattedUser->lastname }}
                </div>
                <div class="c-logout__logout">
                    @typography(["variant" => "meta", "classList" => ["u-margin__top--0"]])
                        @link(['href' => '/logout', 'classList' => ['u-no-decoration', 'u-color__text--darker']])
                            @icon(['icon' => 'logout', 'size' => 'inherit'])
                            @endicon
                            Logga ut
                        @endbutton
                    @endtypography
                </div>
            </div>
        </div>
    @endif

    {{-- Main content --}}
    @yield('content')

    @if($debugResponse)
        @paper(["attributeList" => ["style"=> 'max-width: 900px; margin: 5em auto;'], "classList" => ['u-width--100', 'u-text-align--center', 'u-padding--4', 'u-margin__top--4']])
            <pre style="overflow: auto; text-align: left; margin: 0; max-height: 500px;">
                {{ $debugResponse }}
            </pre>
        @endpaper()
    @endif

    <footer class="u-display--flex u-align-content--center u-flex-direction--column">
        @logotype([
            'src'=> "/assets/img/logotype-grey.svg",
            'alt' => "Logotype for Helsingborg Stad",
            'attributeList' => [
                'style' => 'opacity: .4;'
            ],
            'classList' => [
                'u-align-self--center',
                'u-padding--4',
                'u-padding__bottom--2'
            ]
        ])
        @endlogotype

        @typography([
            'element' => 'span',
            'variant' => 'meta',
            'classList' => [
                'u-align-self--center',
                'u-padding--0',
                'u-color__text--light',
                'u-text-align--center'
            ]
        ])
            När du använder tjänsten accepterar du <a href="https://helsingborg.se/toppmeny/om-webbplatsen/sa-har-behandlar-vi-dina-personuppgifter/" target="_blank">Helsingborg Stads datapolicy</a>.
        @endtypography
    </footer>
</body>
</html>
