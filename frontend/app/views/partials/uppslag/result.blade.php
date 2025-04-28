<div class="u-display--flex u-flex-direction--column u-flex--gridgap">

    @includeIf('notices.' . $action)

    @typography(['element' => 'p', 'classList' => ['u-margin__top--0']])

    Din fil är klar för nedladdning.<br />
    @link(['href' => $link->getDownloadUrl(), 'classList' => ['u-no-decoration', 'u-color__text--darker']])
    @icon(['icon' => 'view_list', 'size' => 'inherit'])
    @endicon
    {{ $link->getFileName() }} ({{ $link->getFileSize() }}kb)
    @endlink
    @endtypography

    @button([
    'text' => 'Tillbaka till formuläret',
    'href' => '/uppslag',
    'color' => 'default',
    'style' => 'filled',
    'classList' => [
    'u-width--100',
    ]
    ])
    @endbutton
</div>