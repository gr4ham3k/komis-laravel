@extends('layouts.app')

@push('styles')
    <style>
        body {
            font-size: 16px;
            background: #eef1f4;
        }

        .hover-bg:hover {
            background: #f1f3f5;
            cursor: pointer;
        }

        .chat-message {
            max-width: 60%;
            font-size: 15px;
        }
    </style>
@endpush

@section('content')

    <div class="container-fluid py-3">

        <div class="row">

            <!-- LEWA STRONA (rozmowy) -->
            <div class="col-4 border-end bg-white" style="height: 90vh; overflow-y: auto;">

                <h5 class="mb-3 p-3">Rozmowy</h5>

                @foreach ($conversations as $conversation)
                    @php
                        $lastMessage = $conversation->messages->first();
                    @endphp

                    <a href="{{ route('conversations.show', $conversation->id) }}" class="text-decoration-none text-dark">

                        <div class="p-3 mb-2 rounded hover-bg">

                            <strong style="font-size: 16px;">
                                {{ $conversation->listing->title }}
                            </strong>

                            <div class="text-muted" style="font-size: 14px;">
                                {{ $lastMessage?->content ?? 'Brak wiadomości' }}
                            </div>

                        </div>

                    </a>
                @endforeach

            </div>

            <!-- PRAWA STRONA (czat) -->
            <div class="col-8 d-flex flex-column" style="height: 90vh;">

                @if (isset($conversationActive))

                    <!-- HEADER -->
                    <div class="border-bottom p-3 bg-white">
                        <h5 class="mb-0">
                            {{ $conversationActive->listing->title }}
                        </h5>
                    </div>

                    <!-- WIADOMOŚCI -->
                    <div class="flex-grow-1 p-3 overflow-auto" id="chat-box" style="background: #f8f9fa;">

                        @foreach ($conversationActive->messages as $message)
                            <div
                                class="d-flex mb-3
                            {{ $message->sender_id == auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">

                                <div
                                    class="p-2 rounded chat-message text-break
                                {{ $message->sender_id == auth()->id() ? 'bg-primary text-white' : 'bg-white border shadow-sm' }}">

                                    <div style="font-size: 13px; opacity: 0.8;">
                                        {{ $message->sender->name }}
                                    </div>

                                    <div style="font-size: 16px;">
                                        {{ $message->content }}
                                    </div>

                                    <div class="text-end" style="font-size: 12px; opacity: 0.7;">
                                        {{ $message->created_at->format('H:i') }}
                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>

                    <!-- INPUT -->
                    <div class="border-top p-3 bg-white">

                        <form method="POST" action="{{ route('messages.store') }}">
                            @csrf

                            <input type="hidden" name="conversation_id" value="{{ $conversationActive->id }}">

                            <div class="input-group">

                                <input type="text" name="content" class="form-control form-control-lg"
                                    placeholder="Napisz wiadomość..." required>

                                <button class="btn btn-primary btn-lg">
                                    Wyślij
                                </button>

                            </div>

                        </form>

                    </div>
                @else
                    <div class="d-flex align-items-center justify-content-center flex-grow-1 text-muted">
                        Wybierz rozmowę
                    </div>

                @endif

            </div>

        </div>

    </div>

@endsection
