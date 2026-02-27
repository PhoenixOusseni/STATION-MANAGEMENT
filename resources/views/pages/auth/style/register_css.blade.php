<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        height: 100vh;
        margin: 0;
        overflow: hidden;
    }

    .register-container {
        height: 100vh;
        display: flex;
    }

    .register-form-section {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        padding: 40px;
        overflow-y: auto;
    }

    .register-form-section::-webkit-scrollbar {
        width: 8px;
    }

    .register-form-section::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .register-form-section::-webkit-scrollbar-thumb {
        background: #c41e3a;
        border-radius: 5px;
    }

    .register-form-section::-webkit-scrollbar-thumb:hover {
        background: #8b1a2e;
    }

    .register-info-section {
        flex: 1;
        background: linear-gradient(135deg, #c41e3a 0%, #8b1a2e 100%);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        padding: 40px 50px;
        position: relative;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .register-info-section::-webkit-scrollbar {
        width: 8px;
    }

    .register-info-section::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
    }

    .register-info-section::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 5px;
    }

    .register-info-section::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.5);
    }

    .register-info-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: pulse 15s infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }
    }

    .register-form-wrapper {
        width: 100%;
        max-width: 550px;
        background: white;
        padding: 20px 30px;
        border-radius: 5px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        margin: 5px 0;
    }

    .logo-section {
        text-align: center;
        margin-bottom: 15px;
    }

    .logo-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #c41e3a 0%, #ff6b6b 100%);
        border-radius: 5px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        box-shadow: 0 5px 20px rgba(196, 30, 58, 0.3);
    }

    .logo-icon i {
        font-size: 35px;
        color: white;
    }

    .logo-text {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 3px;
    }

    .logo-subtext {
        color: #6c757d;
        font-size: 13px;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 4px;
        font-size: 13px;
    }

    .form-control {
        padding: 8px 12px;
        border: 2px solid #e9ecef;
        border-radius: 5px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #c41e3a;
        box-shadow: 0 0 0 0.2rem rgba(196, 30, 58, 0.1);
    }

    .form-control::placeholder {
        color: #adb5bd;
    }

    .password-toggle {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6c757d;
        font-size: 18px;
    }

    .password-toggle:hover {
        color: #c41e3a;
    }

    .btn-register {
        width: 100%;
        padding: 10px;
        background: linear-gradient(135deg, #c41e3a 0%, #8b1a2e 100%);
        border: none;
        border-radius: 5px;
        color: white;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.3s ease;
        margin-top: 3px;
    }

    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(196, 30, 58, 0.4);
    }

    .divider {
        text-align: center;
        margin: 12px 0;
        position: relative;
    }

    .divider::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        width: 100%;
        height: 1px;
        background: #dee2e6;
    }

    .divider span {
        background: white;
        padding: 0 15px;
        position: relative;
        color: #6c757d;
        font-size: 14px;
    }

    .login-link {
        text-align: center;
        margin-top: 20px;
        color: #6c757d;
        font-size: 14px;
    }

    .login-link a {
        color: #c41e3a;
        text-decoration: none;
        font-weight: 600;
    }

    .login-link a:hover {
        text-decoration: underline;
    }

    .info-content {
        position: relative;
        z-index: 1;
    }

    .info-header {
        margin-bottom: 35px;
    }

    .info-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 6px 18px;
        border-radius: 5px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 20px;
        backdrop-filter: blur(10px);
    }

    .info-title {
        font-size: 36px;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 15px;
    }

    .info-highlight {
        color: #ffd700;
    }

    .info-subtitle {
        font-size: 16px;
        opacity: 0.9;
        margin-bottom: 30px;
    }

    .feature-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .feature-card {
        background: rgba(255, 255, 255, 0.1);
        padding: 20px;
        border-radius: 5px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .feature-card:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-5px);
    }

    .feature-icon {
        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }

    .feature-icon i {
        font-size: 22px;
    }

    .feature-title {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .feature-desc {
        font-size: 12px;
        opacity: 0.85;
        line-height: 1.4;
    }

    .benefits-section {
        margin-top: 35px;
        margin-bottom: 30px;
    }

    .benefits-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 18px;
    }

    .benefit-item {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        font-size: 14px;
    }

    .benefit-item i {
        margin-right: 10px;
        font-size: 16px;
        color: #ffd700;
    }

    @media (max-width: 992px) {
        .register-container {
            flex-direction: column;
        }

        .register-info-section {
            display: none;
        }

        .register-form-section {
            flex: none;
            height: 100vh;
        }
    }

    .alert {
        border-radius: 5px;
        border: none;
        padding: 14px 18px;
    }

    .row-cols {
        margin-bottom: 0;
    }

    .col-md-6 {
        margin-bottom: 15px;
    }

    .password-strength {
        font-size: 12px;
        margin-top: 5px;
    }

    .strength-weak {
        color: #dc3545;
    }

    .strength-medium {
        color: #ffc107;
    }

    .strength-strong {
        color: #28a745;
    }
</style>
