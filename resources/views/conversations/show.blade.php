<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Chat</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-4">

    <div class="card shadow-sm">

        <div class="card-header bg-white">
            <h5 class="mb-0">
                {{ $conversation->listing->title }}
            </h5>

            <small class="text-muted">
                Rozmowa #{{ $conversation->id }}
            </small>
        </div>

        <div id="chat-box" class="card-body" style="height: 450px; overflow-y: auto;">

            @foreach($conversation->messages as $message)

                @if($message->sender_id == 2)

                    <div class="d-flex justify-content-end mb-3">

                        <div class="bg-primary text-white p-2 rounded" style="max-width: 70%;">

                            <small class="text-light d-block mb-1">
                                {{ $message->sender->name }}
                            </small>

                            {{ $message->content }}

                            <div class="text-end">
                                <small class="text-light">
                                    {{ $message->created_at->format('H:i') }}
                                </small>
                            </div>

                        </div>
                    </div>

                @else

                    <div class="d-flex justify-content-start mb-3">

                        <div class="bg-light border p-2 rounded" style="max-width: 70%;">

                            <small class="text-muted d-block mb-1">
                                {{ $message->sender->name }}
                            </small>

                            {{ $message->content }}

                            <div>
                                <small class="text-muted">
                                    {{ $message->created_at->format('H:i') }}
                                </small>
                            </div>

                        </div>

                    </div>

                @endif

            @endforeach

        </div>

        <div class="card-footer bg-white">

            <form method="POST" action="{{ route('messages.store') }}">
                @csrf

                <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">

                <div class="input-group">

                    <input type="text"
                           name="content"
                           class="form-control"
                           placeholder="Napisz wiadomość..."
                           required>

                    <button class="btn btn-primary">
                        Wyślij
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</body>
</html>
