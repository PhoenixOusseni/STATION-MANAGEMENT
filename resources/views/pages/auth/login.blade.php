<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Station Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @include('pages.auth.style.login_css')
</head>

<body>
    <div class="login-container">
        <!-- Section Formulaire de connexion -->
        <div class="login-form-section">
            <div class="login-form-wrapper">
                <div class="logo-section">
                    <div class="logo-icon">
                        <i class="bi bi-fuel-pump-fill"></i>
                    </div>
                    <div class="logo-text">STATION MANAGEMENT</div>
                    <div class="logo-subtext">Connectez-vous à votre compte</div>
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

                <form action="{{ route('connexion') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="login" class="form-label">Login *</label>
                        <input type="text" class="form-control @error('login') is-invalid @enderror" id="login"
                            name="login" value="{{ old('login') }}" placeholder="Votre login" required>
                        @error('login')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe *</label>
                        <div class="position-relative">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="••••••••••" required>
                            <span class="password-toggle" onclick="togglePassword('password', 'toggleIcon')">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </span>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember" style="font-size: 14px; color: #6c757d;">
                                Se souvenir de moi
                            </label>
                        </div>
                        <a href="#" class="forgot-password">Mot de passe oublié ?</a>
                    </div>

                    <button type="submit" class="btn btn-login">
                        Se connecter
                    </button>
                </form>

                <div class="divider">
                    <span>Ou</span>
                </div>

                <div class="register-link">
                    Vous n'avez pas de compte ? <a href="{{ route('add_users') }}">S'inscrire</a>
                </div>
            </div>
        </div>

        <!-- Section Informations -->
        <div class="login-info-section">
            <div class="info-content">
                <div class="info-header">
                    <div class="info-badge">
                        <i class="bi bi-shield-check me-2"></i>
                        Plateforme Sécurisée
                    </div>
                    <h1 class="info-title">
                        Plateforme de gestion <span class="info-highlight">tout-en-un</span> pour stations-service
                    </h1>
                    <p class="info-subtitle">
                        Gérez efficacement vos stations d'essence avec notre solution complète et professionnelle
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
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

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
