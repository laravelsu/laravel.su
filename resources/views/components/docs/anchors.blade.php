<nav class="anchors" aria-label="Оглавление страницы">
    <ul>
        @foreach($anchors as $anchor)
            <li class="anchor-{{$anchor['level']}}">
                <a href="#{{$anchor['id']}}"
                   data-controller="scroll"
                   data-to="#{{ $anchor['id'] }}"
                >{{$anchor['text']}}</a>
            </li>
        @endforeach
    </ul>
</nav>
