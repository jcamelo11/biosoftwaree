@extends('layouts.main')

@section('content')

<body class="about-us bg-gray-100">

    <header>
        <div class="page-header min-vh-30" style="background-image: url('{{ asset('img/centro/ganaderia.jpg') }}'); background-position-y: 49%;">
            <span class="mask bg-dark opacity-4"></span>
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 mx-auto text-white text-center">
                        <br><br><br>
                        <h3 class="text-white" id="animate-text1">Descubre las aves que habitan nuestro centro</h3>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="pb-5 position-relative mt-5 mx-n3">
        <div class="container card py-5">
            <div class="row">
                <div class="col-md-6">
                    <div class="card p-0 rounded-3 move-on-hover">
                        <img src="{{ asset('imagenes/aves/' . $ave->imagen) }}" alt="fotografia" class="img-fluid shadow rounded-3">
                    </div>
                </div>
                <div class="col-md-6 mb-md-0 mb-4">
                    <h3>{{ $ave->nombre_comun }}</h3>
                    <p>Nombre Común.</p>
                    <h5 class="text-success font-italic">{{ $ave->nombre_cientifico }}</h5>
                    <p>Nombre Científico.</p>
                    
                    <!-- Nuevo div para la información de GBIF -->
                    <div id="gbif-info" class="mt-3">
                        <h6>Información taxonómica de GBIF:</h6>
                        <div id="gbif-loading" class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <div id="gbif-content" style="display: none;"></div>
                    </div>
                    
                    <h6 class="mb-1 mt-3">Descripción.</h6>

                    <form id="search-form">
                        <input class="auto" style="display: none;" type="text" id="bird-name" value="{{ $ave->nombre_cientifico }}" name="bird-name">
                    </form>
                    
                    <div id="results"></div>

                    <script>
                        // Código existente para xeno-canto
                        const resultsDiv = document.getElementById('results');
                        const loadingSpinner = '<div class="spinner-border text-success" role="status"><span class="visually-hidden">Cargando...</span></div>';

                        async function searchBird(birdName) {
                            try {
                                resultsDiv.innerHTML = loadingSpinner;
                                
                                const url = `https://xeno-canto.org/api/2/recordings?query=${birdName}`;
                                const response = await fetch(url);
                                const data = await response.json();
                                
                                if (data.recordings?.length > 0) {
                                    const recording = data.recordings[0];
                                    const audio = document.createElement('audio');
                                    audio.setAttribute('controls', '');
                                    audio.innerHTML = `<source src="${recording.file}">`;
                                    resultsDiv.innerHTML = '';
                                    resultsDiv.appendChild(audio);
                                } else {
                                    resultsDiv.innerHTML = 'No se encontraron resultados.';
                                }
                            } catch (error) {
                                resultsDiv.innerHTML = `Error: ${error.message}`;
                            }
                        }

                        // Iniciar búsqueda inmediatamente
                        const birdName = document.getElementById('bird-name').value;
                        searchBird(encodeURIComponent(birdName));

                        // Manejar búsquedas manuales
                        document.getElementById('search-form').addEventListener('submit', (e) => {
                            e.preventDefault();
                            const birdName = document.getElementById('bird-name').value;
                            searchBird(encodeURIComponent(birdName));
                        });

                        // Nuevo código para GBIF
                        async function fetchGBIFInfo(scientificName) {
                            try {
                                const response = await fetch(`https://api.gbif.org/v1/species/search?q=${encodeURIComponent(scientificName)}&limit=1`);
                                const data = await response.json();
                                
                                if (data.results && data.results.length > 0) {
                                    const result = data.results[0];
                                    const gbifContent = document.getElementById('gbif-content');
                                    gbifContent.innerHTML = `
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><strong>Reino:</strong> ${result.kingdom || 'No disponible'}</li>
                                            <li class="list-group-item"><strong>Filo:</strong> ${result.phylum || 'No disponible'}</li>
                                            <li class="list-group-item"><strong>Clase:</strong> ${result.class || 'No disponible'}</li>
                                            <li class="list-group-item"><strong>Orden:</strong> ${result.order || 'No disponible'}</li>
                                            <li class="list-group-item"><strong>Familia:</strong> ${result.family || 'No disponible'}</li>
                                            <li class="list-group-item"><strong>Género:</strong> ${result.genus || 'No disponible'}</li>
                                        </ul>
                                    `;
                                    document.getElementById('gbif-loading').style.display = 'none';
                                    gbifContent.style.display = 'block';
                                } else {
                                    throw new Error('No se encontró información para esta especie.');
                                }
                            } catch (error) {
                                document.getElementById('gbif-loading').style.display = 'none';
                                document.getElementById('gbif-content').innerHTML = `<div class="alert alert-danger" role="alert">Error: ${error.message}</div>`;
                                document.getElementById('gbif-content').style.display = 'block';
                            }
                        }

                        // Iniciar búsqueda de GBIF inmediatamente
                        fetchGBIFInfo(birdName);
                    </script>
                </div>
            </div>
        </div>
    </section>

    @include('includes.panel.footerlan')

</body>

@endsection