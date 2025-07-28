<div class="alianzas">
    <h1 class="over-text"><div class="linea"></div>Juntos llegamos más lejos<div class="linea"></div></h1>
    <h1>Alianzas que potencian la educación</h1>
    <p>En ClassGo creemos en el poder de la colaboración para transformar el aprendizaje. Por eso, trabajamos junto a instituciones educativas, clubes y organizaciones comprometidas con la formación académica y el desarrollo personal.</p>
    <div class="steps-alianzas">
        <!-- Alianzas Cards DESDE BD -->
        @foreach($alianzas as $alianza)
            <div class="alianzas-card">
                <img src="{{ $alianza->imagen ? asset('storage/' . $alianza->imagen) : asset('images/tutors/default.png') }}" alt="Imagen de {{ $alianza->imagen }}">
                <div class="centro">
                    <p>{{ $alianza->titulo }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div> 