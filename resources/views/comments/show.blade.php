<x-comment :comment="$comment">
    <x-slot:content>
        <div class="message" data-controller="prism">{!! $comment->prettyComment() !!}</div>
    </x-slot:content>
</x-comment>


