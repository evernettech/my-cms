@props(['id', 'name', 'required' => false])

<select {{ $attributes->merge([
    'id' => $id,
    'name' => $name,
    'required' => $required,
    'class' => 'block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm'
]) }}>
    {{ $slot }}
</select>
