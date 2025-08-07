@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Mis Tareas</h1>
            <a href="{{ route('tasks.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Crear Nueva Tarea
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">¡Éxito!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if ($tasks->isEmpty())
            <p class="text-gray-600 text-center text-lg">No tienes tareas aún. ¡Crea una para empezar!</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($tasks as $task)
                    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 @if($task->status == 'completed') border-green-500 @elseif($task->status == 'in_progress') border-blue-500 @else border-yellow-500 @endif">
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ $task->title }}</h2>
                        <p class="text-gray-700 mb-4">{{ Str::limit($task->description, 100) }}</p>
                        <div class="text-sm text-gray-600 mb-2">
                            <span class="font-medium">Estado:</span>
                            <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold
                                @if($task->status == 'completed') bg-green-200 text-green-800
                                @elseif($task->status == 'in_progress') bg-blue-200 text-blue-800
                                @else bg-yellow-200 text-yellow-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </div>
                        @if ($task->category)
                            <div class="text-sm text-gray-600 mb-2">
                                <span class="font-medium">Categoría:</span> {{ $task->category->name }}
                            </div>
                        @endif
                        @if ($task->due_date)
                            <div class="text-sm text-gray-600 mb-4">
                                <span class="font-medium">Fecha Límite:</span> {{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y H:i') }}
                            </div>
                        @endif
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('tasks.edit', $task->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-bold py-1 px-3 rounded-md transition duration-300">Editar</a>
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta tarea?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-sm font-bold py-1 px-3 rounded-md transition duration-300">Eliminar</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection