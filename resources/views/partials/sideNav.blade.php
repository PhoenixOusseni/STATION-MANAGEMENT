<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sidenav shadow-right sidenav-light">
            <div class="sidenav-menu">
                <div class="nav accordion" id="accordionSidenav">

                    {{-- Dashboard --}}
                    <a class="nav-link collapsed mt-3" href="{{ route('dashboard') }}">
                        <div class="nav-link-icon"><i data-feather="home"></i></div>
                        Tableau de bord
                    </a>

                    {{-- Section Exploitation --}}
                    <div class="sidenav-menu-heading">EXPLOITATION</div>

                    {{-- Sessions de Vente --}}
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseSessions" aria-expanded="false" aria-controls="collapseSessions">
                        <div class="nav-link-icon"><i data-feather="clock"></i></div>
                        Sessions de Vente
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseSessions" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('session_ventes.index') }}">Liste des sessions</a>
                            <a class="nav-link" href="{{ route('session_ventes.create') }}">Ouvrir une session</a>
                        </nav>
                    </div>

                    {{-- Jaugeages --}}
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseJaugeages" aria-expanded="false" aria-controls="collapseJaugeages">
                        <div class="nav-link-icon"><i data-feather="activity"></i></div>
                        Jaugeages
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseJaugeages" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('jaugeages.index') }}">Registre des jaugeages</a>
                            <a class="nav-link" href="{{ route('jaugeages.create') }}">Nouveau jaugeage</a>
                        </nav>
                    </div>

                    {{-- Section Entrées/Sorties --}}
                    <div class="sidenav-menu-heading">ENTRÉES & SORTIES</div>

                    {{-- Commandes --}}
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseCommandes" aria-expanded="false" aria-controls="collapseCommandes">
                        <div class="nav-link-icon"><i data-feather="shopping-cart"></i></div>
                        Commandes
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseCommandes" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('commandes.index') }}">Liste des commandes</a>
                            <a class="nav-link" href="{{ route('commandes.create') }}">Nouvelle commande</a>
                        </nav>
                    </div>

                    {{-- Entrées --}}
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseEntrees" aria-expanded="false" aria-controls="collapseEntrees">
                        <div class="nav-link-icon"><i data-feather="arrow-down-circle"></i></div>
                        Entrées
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseEntrees" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('entrees.index') }}">Liste des entrées</a>
                            <a class="nav-link" href="{{ route('entrees.create') }}">Nouvelle entrée</a>
                        </nav>
                    </div>

                    {{-- Ventes --}}
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseVentes" aria-expanded="false" aria-controls="collapseVentes">
                        <div class="nav-link-icon"><i data-feather="dollar-sign"></i></div>
                        Ventes
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseVentes" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('ventes.index') }}">Liste des ventes</a>
                            <a class="nav-link" href="{{ route('ventes.create') }}">Nouvelle vente</a>
                        </nav>
                    </div>

                    {{-- Section Gestion Stock --}}
                    <div class="sidenav-menu-heading">GESTION DES STOCKS</div>

                    {{-- Cuves --}}
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseCuves" aria-expanded="false" aria-controls="collapseCuves">
                        <div class="nav-link-icon"><i data-feather="database"></i></div>
                        Cuves
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseCuves" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('cuves.index') }}">Liste des cuves</a>
                            <a class="nav-link" href="{{ route('cuves.create') }}">Ajouter une cuve</a>
                        </nav>
                    </div>

                    {{-- Pompes --}}
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapsePompes" aria-expanded="false" aria-controls="collapsePompes">
                        <div class="nav-link-icon"><i data-feather="tool"></i></div>
                        Pompes
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapsePompes" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('pompes.index') }}">Liste des pompes</a>
                            <a class="nav-link" href="{{ route('pompes.create') }}">Ajouter une pompe</a>
                        </nav>
                    </div>

                    {{-- Pistolets --}}
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapsePistolets" aria-expanded="false" aria-controls="collapsePistolets">
                        <div class="nav-link-icon"><i data-feather="zap"></i></div>
                        Pistolets
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapsePistolets" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('pistolets.index') }}">Liste des pistolets</a>
                            <a class="nav-link" href="{{ route('pistolets.create') }}">Ajouter un pistolet</a>
                        </nav>
                    </div>

                    {{-- Carburants --}}
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseCarburants" aria-expanded="false" aria-controls="collapseCarburants">
                        <div class="nav-link-icon"><i data-feather="droplet"></i></div>
                        Carburants
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseCarburants" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('carburants.index') }}">Liste des carburants</a>
                            <a class="nav-link" href="{{ route('carburants.create') }}">Ajouter un carburant</a>
                        </nav>
                    </div>

                    {{-- Section Administration (Admin uniquement) --}}
                    @if(auth()->user()->isAdmin() || auth()->user()->isGestionnaire())
                    <div class="sidenav-menu-heading">ADMINISTRATION</div>

                    {{-- Stations (Admin uniquement) --}}
                    @if(auth()->user()->isAdmin())
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseStations" aria-expanded="false" aria-controls="collapseStations">
                        <div class="nav-link-icon"><i data-feather="map-pin"></i></div>
                        Stations
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseStations" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('stations.index') }}">Liste des stations</a>
                            <a class="nav-link" href="{{ route('stations.create') }}">Ajouter une station</a>
                        </nav>
                    </div>
                    @endif

                    {{-- Utilisateurs --}}
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseUsers" aria-expanded="false" aria-controls="collapseUsers">
                        <div class="nav-link-icon"><i data-feather="users"></i></div>
                        Utilisateurs
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseUsers" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('users.index') }}">Liste des utilisateurs</a>
                            <a class="nav-link" href="{{ route('users.create') }}">Ajouter un utilisateur</a>
                        </nav>
                    </div>
                    @endif

                    {{-- Section Rapports --}}
                    <div class="sidenav-menu-heading">RAPPORTS</div>

                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse"
                        data-bs-target="#collapseEtats" aria-expanded="false" aria-controls="collapseEtats">
                        <div class="nav-link-icon"><i data-feather="file-text"></i></div>
                        États
                        <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseEtats" data-bs-parent="#accordionSidenav">
                        <nav class="sidenav-menu-nested nav">
                            <a class="nav-link" href="{{ route('livre_journal.index') }}">Livre Journal</a>
                        </nav>
                    </div>
                    <a class="nav-link collapsed" href="{{ route('statistiques.index') }}">
                        <div class="nav-link-icon"><i data-feather="bar-chart-2"></i></div>
                        Statistiques
                    </a>

                </div>
            </div>
            <div class="sidenav-footer" style="background: linear-gradient(135deg, #c41e3a 0%, #8b1a2e 100%);">
                <div class="sidenav-footer-content text-center">
                    <div class="sidenav-footer-subtitle text-white">Utilisateur connecté(e):</div>
                    <div class="sidenav-footer-title text-white">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</div>
                    <div class="sidenav-footer-subtitle text-white mt-1">
                        <small>{{ ucfirst(Auth::user()->role) }}</small>
                    </div>
                </div>
            </div>
        </nav>
    </div>
