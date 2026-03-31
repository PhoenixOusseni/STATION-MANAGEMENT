<nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start"
    id="sidenavAccordion"
    style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
           border-bottom: 2px solid #c41e3a;
           min-height: 62px;
           padding: 0 1rem;">

    {{-- ─── Bouton toggle sidebar ─── --}}
    <button class="btn btn-icon order-1 order-lg-0 me-3" id="sidebarToggle"
        style="color: rgba(255,255,255,0.75); background: rgba(255,255,255,0.07);
               border: 1px solid rgba(255,255,255,0.12); border-radius: 8px;
               width: 38px; height: 38px; display:flex; align-items:center; justify-content:center;
               transition: all .2s;">
        <i data-feather="menu" style="width:18px;height:18px;"></i>
    </button>

    {{-- ─── Marque / Titre app ─── --}}
    <a class="navbar-brand d-flex align-items-center gap-2 pe-4" href="{{ route('dashboard') }}"
        style="text-decoration:none;">
        <span style="background: linear-gradient(135deg, #c41e3a, #e05a72);
                     border-radius: 8px; width:34px; height:34px;
                     display:flex; align-items:center; justify-content:center;
                     box-shadow: 0 2px 8px rgba(196,30,58,.45);">
            <i data-feather="droplet" style="width:16px;height:16px;color:#fff;"></i>
        </span>
        <span style="font-size:15px; font-weight:700; letter-spacing:.5px;
                     color:#fff; text-transform:uppercase; line-height:1;">
            Station
            <span style="color:#e05a72;">Manager</span>
        </span>
    </a>

    {{-- ─── Date & heure (centre, masqué sur mobile) ─── --}}
    <div class="d-none d-lg-flex align-items-center gap-2 ms-2"
        style="color:rgba(255,255,255,0.55); font-size:12.5px; font-weight:500;">
        <i data-feather="calendar" style="width:14px;height:14px; color:#e05a72;"></i>
        <span id="topnav-date"></span>
        <span style="margin: 0 6px; opacity:.3;">|</span>
        <i data-feather="clock" style="width:14px;height:14px; color:#e05a72;"></i>
        <span id="topnav-time" style="font-variant-numeric: tabular-nums;"></span>
    </div>

    {{-- ─── Côté droit ─── --}}
    <ul class="navbar-nav align-items-center ms-auto gap-1">

        {{-- Badge station courante --}}
        @if(Auth::user()->station)
        <li class="nav-item d-none d-md-flex align-items-center me-2">
            <span style="background: rgba(196,30,58,.15); border: 1px solid rgba(196,30,58,.35);
                         color: #e05a72; border-radius: 20px; padding: 4px 12px;
                         font-size: 11.5px; font-weight: 600; letter-spacing:.3px;">
                <i data-feather="map-pin" style="width:12px;height:12px; margin-right:4px;"></i>
                {{ Auth::user()->station->nom }}
            </span>
        </li>
        @endif

        {{-- Lien tableau de bord rapide --}}
        <li class="nav-item">
            <a href="{{ route('dashboard') }}"
               class="btn btn-icon d-flex align-items-center justify-content-center"
               title="Tableau de bord"
               style="color:rgba(255,255,255,0.6); background:rgba(255,255,255,0.06);
                      border:1px solid rgba(255,255,255,0.1); border-radius:8px;
                      width:38px; height:38px; transition:all .2s; text-decoration:none;">
                <i data-feather="home" style="width:16px;height:16px;"></i>
            </a>
        </li>

        {{-- ─── Menu utilisateur ─── --}}
        <li class="nav-item dropdown no-caret ms-1">
            <a class="dropdown-toggle d-flex align-items-center gap-2 px-2 py-1"
               id="navbarDropdownUser" href="javascript:void(0);" role="button"
               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
               style="text-decoration:none; border-radius:10px;
                      background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.12);
                      transition:all .2s; min-height:38px; cursor:pointer;">

                {{-- Avatar initiales --}}
                <span style="width:30px; height:30px; border-radius:50%; flex-shrink:0;
                             background: linear-gradient(135deg, #c41e3a, #e05a72);
                             display:flex; align-items:center; justify-content:center;
                             font-size:12px; font-weight:700; color:#fff; letter-spacing:.5px;">
                    {{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                </span>

                {{-- Nom + rôle --}}
                <span class="d-none d-md-flex flex-column" style="line-height:1.2;">
                    <span style="font-size:12.5px; font-weight:600; color:#fff;">
                        {{ Auth::user()->prenom }} {{ Auth::user()->nom }}
                    </span>
                    <span style="font-size:10.5px; color:rgba(255,255,255,0.45); text-transform:uppercase; letter-spacing:.4px;">
                        {{ ucfirst(Auth::user()->role) }}
                    </span>
                </span>

                <i data-feather="chevron-down"
                   style="width:14px;height:14px; color:rgba(255,255,255,0.4); margin-left:2px;"></i>
            </a>

            {{-- Dropdown menu --}}
            <div class="dropdown-menu dropdown-menu-end border-0 mt-2 py-0 overflow-hidden animated--fade-in-up"
                 aria-labelledby="navbarDropdownUser"
                 style="min-width:230px; border-radius:12px;
                        box-shadow: 0 8px 32px rgba(0,0,0,0.22), 0 2px 8px rgba(0,0,0,0.12);">

                {{-- En-tête du dropdown --}}
                <div class="px-4 py-3 d-flex align-items-center gap-3"
                     style="background: linear-gradient(135deg, #1a1a2e, #16213e); border-bottom:1px solid rgba(255,255,255,0.08);">
                    <span style="width:42px; height:42px; border-radius:50%; flex-shrink:0;
                                 background: linear-gradient(135deg, #c41e3a, #e05a72);
                                 display:flex; align-items:center; justify-content:center;
                                 font-size:15px; font-weight:700; color:#fff;">
                        {{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                    </span>
                    <div>
                        <div style="font-size:13px; font-weight:700; color:#fff;">
                            {{ Auth::user()->prenom }} {{ Auth::user()->nom }}
                        </div>
                        <div style="font-size:11px; color:rgba(255,255,255,0.5); text-transform:uppercase; letter-spacing:.4px;">
                            {{ ucfirst(Auth::user()->role) }}
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="py-1">
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-3"
                       href="{{ route('profile', ['id' => Auth::user()->id]) }}"
                       style="font-size:13px; font-weight:500; color:#2d3748; transition:background .15s;">
                        <span style="width:28px; height:28px; border-radius:6px; background:#f0f4ff;
                                     display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            <i data-feather="user" style="width:14px;height:14px; color:#3b82f6;"></i>
                        </span>
                        Mon profil
                    </a>

                    <div class="dropdown-divider my-1" style="border-color:#f0f0f0;"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2 px-3"
                           href="#"
                           onclick="event.preventDefault(); this.closest('form').submit();"
                           style="font-size:13px; font-weight:500; color:#c41e3a; transition:background .15s;">
                            <span style="width:28px; height:28px; border-radius:6px; background:#fef2f2;
                                         display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <i data-feather="log-out" style="width:14px;height:14px; color:#c41e3a;"></i>
                            </span>
                            Se déconnecter
                        </a>
                    </form>
                </div>
            </div>
        </li>

    </ul>
</nav>

{{-- ─── Script horloge temps réel ─── --}}
<script>
    (function () {
        const JOURS = ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'];
        const MOIS  = ['jan.','fév.','mars','avr.','mai','juin','juil.','août','sept.','oct.','nov.','déc.'];

        function pad(n) { return String(n).padStart(2, '0'); }

        function tick() {
            const now = new Date();
            const dateEl = document.getElementById('topnav-date');
            const timeEl = document.getElementById('topnav-time');
            if (dateEl) {
                dateEl.textContent = JOURS[now.getDay()] + ' ' + now.getDate() + ' ' + MOIS[now.getMonth()] + ' ' + now.getFullYear();
            }
            if (timeEl) {
                timeEl.textContent = pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
            }
        }

        tick();
        setInterval(tick, 1000);

        // Hover effect sur les boutons icon
        document.querySelectorAll('#sidenavAccordion .btn-icon, #sidenavAccordion a.btn-icon').forEach(function(el) {
            el.addEventListener('mouseenter', function() {
                this.style.background = 'rgba(196,30,58,0.2)';
                this.style.borderColor = 'rgba(196,30,58,0.4)';
                this.style.color = '#e05a72';
            });
            el.addEventListener('mouseleave', function() {
                this.style.background = 'rgba(255,255,255,0.06)';
                this.style.borderColor = 'rgba(255,255,255,0.1)';
                this.style.color = 'rgba(255,255,255,0.6)';
            });
        });
    })();
</script>
