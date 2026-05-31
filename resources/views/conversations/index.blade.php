<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Moje rozmowy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-4">

    <h4 class="mb-4">Moje rozmowy</h4>

    <div class="card shadow-sm">

        <div class="list-group list-group-flush">

            @foreach($conversations as $conversation)

                @php
                    $lastMessage = $conversation->messages->first();
                @endphp

                <a href="{{ route('conversations.show', $conversation->id) }}"
                   class="list-group-item list-group-item-action">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <strong>
                                {{ $conversation->listing->title }}
                            </strong>

                            <div class="text-muted small">
                                {{ $lastMessage?->content ?? 'Brak wiadomości' }}
                            </div>
                        </div>

                        <div class="text-end">
                            <small class="text-muted">
                                {{ $lastMessage?->created_at?->format('H:i') }}
                            </small>
                        </div>

                    </div>

                </a>

            @endforeach

        </div>

    </div>

</div>

</body>
</html>
