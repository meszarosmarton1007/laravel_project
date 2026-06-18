@props(['highlight' => false])

@php
    $hasBgClass = \Illuminate\Support\STR::contains($attributes->get('class'), 'bg-');
    $defaultBg = $hasBgClass ? '' : 'bg-white';
@endphp

<a {{$attributes->merge(['class' => 'card $defaultBg'])}}>
    <div>
        {{$slot}}
    </div>
   <span class="btn mt-2 inline-block">Részletek és alfeladatok</span>
</a>