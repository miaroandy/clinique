@extends('templatefront')
@section('titre')
<title>Ajout recette</title>
@endsection
@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Ajouter une recette</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">Forms</li>
          <li class="breadcrumb-item active">Elements</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Remplir formulaire</h5>

              <!-- General Form Elements -->
              <form action="{{ url('/ajoutRecette') }}" method="post">
              {{ csrf_field() }}


                    <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Type recette</label>
                     <div class="col-sm-10">
                    <select  name="idtyperecette" class="form-select" aria-label="Default select example">
                      <option value="">Type recette </option>
                      @foreach ($data as $data)
                      <option value="{{ $data->idtyperecette }}" >{{$data->nom}}</option>
                      @endforeach
                      </select>

                  </div>
                  </div>
                    <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Facture</label>
                     <div class="col-sm-10">
                     <select  name="idfacturerecette" class="form-select" aria-label="Default select example">
                      <option value="">Facture </option>
                      @foreach ($data1 as $data)
                      <option value="{{ $data->idfacturerecette }}" >{{ $data->idfacturerecette }}{{ $data->idfacturerecette }}</option>
                      @endforeach
                      </select>

                  </div>
                  </div>


                  <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Nombre</label>
                        <div class="col-sm-10" >
                        <input type="number" name="nombre" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Prix</label>
                        <div class="col-sm-10" >
                        <input type="number" name="montant" class="form-control">
                        </div>
                    </div>



                  <div class="row mb-3">
                     <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                  </div>
                </div>

            </form><!-- End General Form Elements -->

            </div>
          </div>

        </div>


      </div>


    </section>

  </main><!-- End #main -->
