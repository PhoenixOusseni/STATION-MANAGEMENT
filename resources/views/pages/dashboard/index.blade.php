@extends('layouts.master')

@section('style')
    @include('partials.style')
@endsection

@section('content')
    <main>
        <header class="page-header page-header-dark pb-10">
            <div class="container-xl px-4">
                <div class="page-header-content pt-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mt-4">
                            <h1 class="page-header-title">
                                <div class="page-header-icon text-white"><i data-feather="activity"></i></div>
                                STATION MANAGER | Tableau de bord
                            </h1>
                        </div>
                        <div class="col-12 col-xl-auto mt-4">
                            <div class="input-group input-group-joined border-0" style="width: 16.5rem">
                                <span class="input-group-text"><i class="text-primary" data-feather="calendar"></i></span>
                                <div class="form-control ps-0 pointer">
                                    {{ Carbon\Carbon::now()->format('d-m-Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div class="container-xl px-4 mt-n10">
            <div class="row">
                <div class="col-xxl-4 col-xl-12 mb-4">
                    <div class="card h-100">
                        <div class="card-body h-100 p-5">
                            <div class="row align-items-center">
                                <div class="col-xl-8 col-xxl-12">
                                    <div class="text-center text-xl-start text-xxl-center mb-4 mb-xl-0 mb-xxl-4">
                                        <h1 style="font-size:25px" class="text-dark">Bienvenue {{ Auth::user()->prenom }} !</h1>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-xxl-12 text-center"><img class="img-fluid"
                                        src="{{ asset('images/users_icon.png') }}" style="max-width: 15rem" /></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-8 col-xl-6 mb-4">
                    <div class="card card-header-actions h-100">
                        <div class="card-header" style="color: #8b1a2e">
                            Facture fournisseur en attente de règlement
                        </div>
                        <div class="card-body">
                            <div class="timeline timeline-xs">
                                <!-- Timeline Item 1-->
                                <div class="timeline-item">
                                    <div class="timeline-item-marker">
                                        <div class="timeline-item-marker-text">0001</div>
                                        <div class="timeline-item-marker-indicator bg-green"></div>
                                    </div>
                                    <div class="timeline-item-content">
                                        Facture 0001
                                        <a class="fw-bold text-dark" href="#!">#03/02/2026</a>
                                        Formation du personnel
                                        <a class="fw-bold text-dark" href="#!">#140.000 FCFA</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Example Charts for Dashboard Demo-->
            <div class="row">
                <div class="card mb-4">
                    <div class="card-header" style="color: #8b1a2e">Factures du jour</div>
                    <div class="card-body">
                        <table id="datatablesSimple">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Rérérence</th>
                                    <th>Marché</th>
                                    <th>Contribuable</th>
                                    <th>Echeance</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="d-flex justify-content-between">
                                        <a href="#">
                                            <i class="fa fa-eye text-success" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
