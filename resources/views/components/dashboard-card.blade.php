@props(['label', 'value', 'color'])

<div class="bg-white border shadow rounded-lg p-4">
    <div class="text-sm text-gray-500">{{ $label }}</div>
    <div class="text-2xl font-bold text-{{ $color }}-600">{{ $value }}</div>
</div>
