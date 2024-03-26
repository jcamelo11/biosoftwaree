@extends('layouts.main')

@section('content')


<body class="about-us bg-gray-100">

    <header>
        <div class="page-header min-vh-30" style="background-image: url(&quot;{{ asset('img/centro/ganaderia.jpg') }}&quot;); background-position-y: 49%">
        <span class="mask bg-dark opacity-4"></span>
        <div class="container">
        <div class="row">
        <div class="col-lg-7 mx-auto text-white text-center"><br><br><br>
        <h3 class="text-white" id="animate-text1">Descubre las aves que habitan nuestro centro</h3>
        </div>
        </div>
        </div>
        </div>
    </header>

    
        <section class="pb-5 position-relative mt-5 mx-n3">
        <div class="container card py-5">
        <div class="row ">
            <div class="col-md-6">
            <div class="card p-0 rounded-3 move-on-hover">
            <img src="{{ asset('imagenes/aves/' . $ave->imagen) }}" alt="fotografia" class="img-fluid shadow rounded-3">
            </div>
            </div>
        <div class="col-md-6 mb-md-0 mb-4">
        <h3>{{ $ave->nombre_comun }}</h3>
        <p >Nombre Común.</p>
        <h5 class="text-success font-italic">{{ $ave->nombre_cientifico }}</h3>
        <p>Nombre Científico.</p>
            
            <h6 class="mb-1">Descripción.</h6>
            <p class="font-weight-bold"><small>{{ $ave->descripcion }}</small></p>
        <form id="search-form">
        <input class="auto" style="display: none" type="text" autoclip id="bird-name" value="{{ $ave->nombre_cientifico }}" name="bird-name">
        <button class=" btn btn-success" type="submit" i class="fas fa-save"></i>&nbsp;&nbsp;escuchar</button>
        </form>
        <div id="results"></div>
        <script>
        const searchForm = document.getElementById('search-form');
        const resultsDiv = document.getElementById('results');

        searchForm.addEventListener('submit', (event) => {
          event.preventDefault();
          const birdName = encodeURIComponent(document.getElementById('bird-name').value);
          const url = `https://xeno-canto.org/api/2/recordings?query=${birdName}`;

          fetch(url)
            .then(response => response.json())
            .then(data => {
              if (data.recordings.length > 0) {
                const recording = data.recordings[0];
                resultsDiv.innerHTML = '';

                const audio = document.createElement('audio');
                audio.setAttribute('controls', '');
                const source = document.createElement('source');
                source.setAttribute('src', recording.file);
                audio.appendChild(source);
                resultsDiv.appendChild(audio);

              } else {
                resultsDiv.innerHTML = 'No se encontraron resultados.';
              }
            })
            .catch(error => {
              resultsDiv.innerHTML = `Ha ocurrido un error: ${error.message}`;
            });
        });

        // Obtén el nombre científico del ave de tu base de datos y asígnalo a la variable 'birdName'
        const birdName = '{{ $ave->nombre_cientifico }}';

        // Busca automáticamente el sonido del ave al cargar la página
        window.addEventListener('load', () => {
            searchBird(encodeURIComponent(birdName));
        });
      </script>

        <!-- <script>
        const resultsDiv = document.getElementById('results');

        function searchBird(birdName) {
            const url = `https://xeno-canto.org/api/2/recordings?query=${birdName}`;

            fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.recordings.length > 0) {
                const recording = data.recordings[0];
                resultsDiv.innerHTML = '';

                const audio = document.createElement('audio');
                audio.setAttribute('controls', '');
                const source = document.createElement('source');
                source.setAttribute('src', recording.file);
                audio.appendChild(source);
                resultsDiv.appendChild(audio);

                } else {
                resultsDiv.innerHTML = 'No se encontraron resultados.';
                }
            })
            .catch(error => {
                resultsDiv.innerHTML = `Ha ocurrido un error: ${error.message}`;
            });
        }

        // Obtén el nombre científico del ave de tu base de datos y asígnalo a la variable 'birdName'
        const birdName = '{{ $ave->nombre_cientifico }}';

        // Busca automáticamente el sonido del ave al cargar la página
        window.addEventListener('load', () => {
            searchBird(encodeURIComponent(birdName));
        });
        </script> -->
        </div>
        </div>
        </div>
        </section>
    
    @include('includes.panel.footerlan')


</body>

@endsection
