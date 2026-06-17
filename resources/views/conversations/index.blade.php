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

            <div class="col-4 border-end bg-white" style="height: 90vh; overflow-y: auto;">

                <h5 class="mb-3 p-3">Rozmowy</h5>

                @foreach ($conversations as $conversation)
                    @php
                        $lastMessage = $conversation->latestMessage;

                        $partner = $conversation->messages->where('sender_id', '!=', auth()->id())->first()?->sender;
                    @endphp

                    <a href="{{ route('conversations.show', $conversation->id) }}" class="text-decoration-none text-dark">

                        <div class="p-3 mb-2 rounded hover-bg position-relative">

                            <div class="d-flex justify-content-between align-items-center">
                                <strong style="font-size: 16px;">
                                    {{ $conversation->listing ? $conversation->listing->title : $conversation->service->title }}
                                </strong>

                                @if ($conversation->unread_count > 0)
                                    <span class="badge bg-danger rounded-pill">{{ $conversation->unread_count }}</span>
                                @endif
                            </div>

                            <div class="text-muted" style="font-size: 13px;">
                                {{ $conversation->partner?->name ?? 'Brak rozmówcy' }}
                            </div>

                            <div class="text-muted" style="font-size: 14px;">
                                {{ \Illuminate\Support\Str::limit($lastMessage?->content ?? 'Brak wiadomości', 50) }}
                            </div>

                        </div>

                    </a>
                @endforeach

            </div>

            <div class="col-8 d-flex flex-column" style="height: 90vh;">

                @if (isset($conversationActive))

                    <div class="border-bottom p-3 bg-white">
                        <h5 class="mb-0">
                            {{ $conversationActive->listing ? $conversationActive->listing->title : $conversationActive->service->title }}
                        </h5>

                        <div class="text-muted" style="font-size: 14px;">
                            {{ $conversationActive->partner?->name ?? 'Brak rozmówcy' }}
                        </div>
                    </div>

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
                                        @if ($message->sender_id == auth()->id())
                                            @if ($message->is_read)
                                                <i class="fas fa-check-double ms-1" style="color: #53bdeb;"
                                                    title="Przeczytana"></i>
                                            @else
                                                <i class="fas fa-check ms-1 text-white-50" title="Dostarczona"></i>
                                            @endif
                                        @endif
                                    </div>

                                </div>

                            </div>
                        @endforeach

                    </div>

                    <div class="border-top p-3 bg-white">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-3">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('messages.store') }}">
                            @csrf

                            <input type="hidden" name="conversation_id" value="{{ $conversationActive->id }}">

                            <div class="input-group">

                                <input type="text" name="content" class="form-control form-control-lg"
                                    placeholder="Napisz wiadomość..." value="{{ old('content') }}" required>

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
