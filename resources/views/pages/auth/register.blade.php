<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Station Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @include('pages.auth.style.register_css')
</head>

<body>
    <div class="register-container">
        <!-- Section Formulaire d'inscription -->
        <div class="register-form-section">
            <div class="register-form-wrapper">
                <div class="logo-section">
                    <div class="logo-text">Station Manager</div>
                    <div class="logo-subtext">Créez votre compte</div>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="nom" class="form-label">Nom *</label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror"
                                    id="nom" name="nom" value="{{ old('nom') }}"
                                    placeholder="Votre nom" required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="prenom" class="form-label">Prénom *</label>
                                <input type="text" class="form-control @error('prenom') is-invalid @enderror"
                                    id="prenom" name="prenom" value="{{ old('prenom') }}"
                                    placeholder="Votre prénom" required>
                                @error('prenom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="email" class="form-label">Adresse e-mail *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" placeholder="exemple@email.com" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <label for="telephone" class="form-label">Téléphone *</label>
                        <input type="tel" class="form-control @error('telephone') is-invalid @enderror" id="telephone"
                            name="telephone" value="{{ old('telephone') }}" placeholder="Votre numéro de téléphone" required>
                        @error('telephone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="password" class="form-label">Mot de passe *</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" placeholder="••••••••••" required>
                                    <span class="password-toggle" onclick="togglePassword('password', 'toggleIcon1')">
                                        <i class="bi bi-eye" id="toggleIcon1"></i>
                                    </span>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="password_confirmation" class="form-label">Confirmer mot de passe *</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" placeholder="••••••••••" required>
                                    <span class="password-toggle"
                                        onclick="togglePassword('password_confirmation', 'toggleIcon2')">
                                        <i class="bi bi-eye" id="toggleIcon2"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms" style="font-size: 14px; color: #6c757d;">
                                J'accepte les <a href="#" style="color: #c41e3a;">conditions d'utilisation</a>
                                et la <a href="#" style="color: #c41e3a;">politique de confidentialité</a>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-register">
                        S'inscrire
                    </button>
                </form>

                <div class="divider">
                    <span>Ou</span>
                </div>

                <div class="login-link">
                    Vous avez déjà un compte ? <a href="{{ route('login') }}">Se connecter</a>
                </div>
            </div>
        </div>

        <!-- Section Informations -->
        <div class="register-info-section">
            <div class="info-content">
                <div class="info-header">
                    <div class="info-badge">
                        <i class="bi bi-rocket-takeoff me-2"></i>
                        Démarrez Gratuitement
                    </div>
                    <h1 class="info-title">
                        Rejoignez les <span class="info-highlight">500+</span> gestionnaires de stations
                    </h1>
                    <p class="info-subtitle">
                        Créez votre compte gratuitement et commencez à gérer vos stations d'essence efficacement
                    </p>
                </div>

                <div class="feature-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>
                        <div class="feature-title">Configuration rapide</div>
                        <div class="feature-desc">Commencez en moins de 5 minutes</div>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-fill-check"></i>
                        </div>
                        <div class="feature-title">100% Sécurisé</div>
                        <div class="feature-desc">Vos données sont protégées</div>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-headset"></i>
                        </div>
                        <div class="feature-title">Support dédié</div>
                        <div class="feature-desc">Assistance 24/7 par téléphone</div>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-gift-fill"></i>
                        </div>
                        <div class="feature-title">Essai gratuit</div>
                        <div class="feature-desc">30 jours sans engagement</div>
                    </div>
                </div>

                <div class="benefits-section">
                    <h3 class="benefits-title">Ce que vous obtenez avec votre compte</h3>
                    <div class="benefit-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Tableau de bord complet et intuitif</span>
                    </div>
                    <div class="benefit-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Gestion multi-stations en temps réel</span>
                    </div>
                    <div class="benefit-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Rapports et statistiques détaillés</span>
                    </div>
                    <div class="benefit-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Suivi des stocks et transactions</span>
                    </div>
                    <div class="benefit-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Formation complète incluse</span>
                    </div>
                    <div class="benefit-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Mises à jour automatiques gratuites</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }
    </script>
</body>

</html>
