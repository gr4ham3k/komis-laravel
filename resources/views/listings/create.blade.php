<form method="POST" action="/listings">
    @csrf

    <input class="form-control mb-2" name="title" placeholder="Tytuł">

    <input class="form-control mb-2" name="price" placeholder="Cena">

    <input class="form-control mb-2" name="city" placeholder="Miasto">

    <input class="form-control mb-2" name="year" placeholder="Rok">

    <input class="form-control mb-2" name="mileage" placeholder="Przebieg">

    <textarea class="form-control mb-2" name="description" placeholder="Opis"></textarea>

    <button class="btn btn-primary w-100">Dodaj ogłoszenie</button>
</form>
