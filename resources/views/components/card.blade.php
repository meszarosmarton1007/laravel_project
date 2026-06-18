@props(['highlight' => false])

@php
    $hasBgClass = \Illuminate\Support\STR::contains($attributes->get('class'), 'bg-');
    $defaultBg = $hasBgClass ? '' : 'bg-white';
@endphp

<div {{$attributes->merge(['class' => 'card $defaultBg'])}}>
    {{$slot}}
    <a {{$attributes}} class="btn">Részletek és alfeladatok</a>
</div>