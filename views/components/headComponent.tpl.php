<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ APP_SECTION }} - {{ APP_NAME }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        :root {
            /* Paleta de colores */
            --color-fondo-principal: {{ COLOR_FONDO_PRINCIPAL }};
            --color-texto-principal: {{ COLOR_TEXTO_PRINCIPAL }};
            --color-texto-secundario: {{ COLOR_TEXTO_SECUNDARIO }};
            --color-acento-principal: {{ COLOR_ACENTO_PRINCIPAL }};
            --color-acento-secundario: {{ COLOR_ACENTO_SECUNDARIO }};
            --color-boton-principal-fondo: var(--color-acento-principal);
            --color-boton-principal-texto: {{ COLOR_BOTON_PRINCIPAL_TEXTO }};
            --color-boton-secundario-fondo: var(--color-acento-secundario);
            --color-boton-secundario-texto: {{ COLOR_BOTON_SECUNDARIO_TEXTO }};
            --color-header-fondo: {{ COLOR_HEADER_FONDO }};
            --color-footer-fondo: {{ COLOR_FOOTER_FONDO }};
            --color-footer-texto: {{ COLOR_FOOTER_TEXTO }};
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--color-fondo-principal);
            color: var(--color-texto-principal);
            line-height: 1.6;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: -250px;
            width: 250px;
            height: 100vh;
            background: linear-gradient(135deg, var(--color-acento-principal), var(--color-acento-secundario));
            transition: left 0.3s ease;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar.open {
            left: 0;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header .logo {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            display: block;
            padding: 15px 20px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .menu-item:hover, .menu-item.active {
            background: rgba(255,255,255,0.1);
            border-left-color: white;
            color: white;
        }

        .menu-item i {
            margin-right: 10px;
            width: 20px;
        }

        /* Toggle Button */
        .toggle-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: var(--color-acento-principal);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }

        .toggle-btn:hover {
            background: var(--color-acento-secundario);
            transform: translateY(-2px);
        }

        /* Main Content */
        .main-content {
            transition: margin-left 0.3s ease;
            margin-left: 0;
            min-height: 100vh;
            position: relative;
        }

        .main-content.shifted {
            margin-left: 250px;
        }

        /* Overlay para móviles */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Header */
        .app-header {
            background-color: var(--color-header-fondo);
            padding: 1rem 2rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-left: 70px;
        }

        .app-header h1 {
            font-size: 1.5rem;
            color: var(--color-acento-principal);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--color-boton-principal-fondo);
            color: var(--color-boton-principal-texto);
        }

        .btn-primary:hover {
            background-color: var(--color-acento-secundario);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--color-acento-principal);
            border: 1px solid var(--color-acento-principal);
        }

        .btn-secondary:hover {
            background-color: var(--color-acento-principal);
            color: white;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content.shifted {
                margin-left: 0;
            }
            
            .app-header {
                margin-left: 70px;
                padding: 1rem;
            }
            
            .toggle-btn {
                top: 15px;
                left: 15px;
            }
        }

        /* Formularios */
        .form-container {
            max-width: 500px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--color-texto-principal);
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--color-acento-principal);
            box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.1);
        }

        /* Alertas */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-info {
            background: #cce7ff;
            color: #004085;
            border: 1px solid #99d1ff;
        }
    </style>
</head>
<body>