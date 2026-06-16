@props(['highlight' => false])

<div @class(['card'])>
    {{$slot}}
    <a {{$attributes}} class="btn">Részletek és alfeladatok</a>
</div>