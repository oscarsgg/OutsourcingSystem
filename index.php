<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TalentBridge - Conectando Talento con Oportunidades</title>
    <style>
        /* Estilos generales */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header y navegación */
        header {
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #3498db;
        }
        .nav-links {
            display: flex;
            list-style: none;
        }
        .nav-links li {
            align-self: center;
            margin-top: 20px;
            margin-bottom: 20px;
            margin-left: 20px;
        }
        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .nav-links a:hover {
            color: #3498db;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #2980b9;
        }

        /* Hero section */
        .hero {
            background: linear-gradient(135deg, #3498db, #8e44ad);
            color: #fff;
            padding: 100px 0;
            margin-top: 60px;
        }
        .hero-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .hero-text {
            flex: 1;
            padding-right: 40px;
        }
        .hero-text h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        .hero-text p {
            font-size: 1.1rem;
            margin-bottom: 30px;
        }
        .hero-image {
            flex: 1;
        }
        .hero-image img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Características */
        .features {
            padding: 80px 0;
            background-color: #f9f9f9;
        }
        .features h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 40px;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }
        .feature-item {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .feature-item i {
            font-size: 2.5rem;
            color: #3498db;
            margin-bottom: 20px;
        }
        .feature-item h3 {
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        /* Testimonios */
        .testimonials {
            padding: 80px 0;
        }
        .testimonials h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 40px;
        }
        .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        .testimonial-item {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .testimonial-item p {
            font-style: italic;
            margin-bottom: 20px;
        }
        .testimonial-author {
            font-weight: bold;
        }

        /* CTA */
        .cta {
            background-color: #3498db;
            color: #fff;
            padding: 80px 0;
            text-align: center;
        }
        .cta h2 {
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .cta p {
            font-size: 1.1rem;
            margin-bottom: 30px;
        }
        .cta .btn {
            background-color: #fff;
            color: #3498db;
            font-size: 1.1rem;
            padding: 15px 30px;
        }
        .cta .btn:hover {
            background-color: #f1f1f1;
        }

        /* Footer */
        footer {
            background-color: #333;
            color: #fff;
            padding: 40px 0;
        }
        .footer-content {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .footer-section {
            flex: 1;
            margin-right: 20px;
            margin-bottom: 20px;
        }
        .footer-section h3 {
            margin-bottom: 20px;
        }
        .footer-section ul {
            list-style: none;
        }
        .footer-section ul li {
            margin-bottom: 10px;
        }
        .footer-section ul li a {
            color: #fff;
            text-decoration: none;
        }
        .footer-bottom {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #555;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .hero-content {
                flex-direction: column;
            }
            .hero-text, .hero-image {
                flex: none;
                width: 100%;
                padding-right: 0;
                margin-bottom: 30px;
            }
            .nav-links {
                display: none;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">TalentBridge</div>
            <ul class="nav-links">
                <li><a href="#inicio">Inicio</a></li>
                <li><a href="#caracteristicas">Características</a></li>
                <li><a href="#testimonios">Testimonios</a></li>
                <li><a href="#contacto">Contacto</a></li>
                <li><a href="login.php" class="btn">Iniciar Sesión</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero" id="inicio">
        <div class="container hero-content">
            <div class="hero-text">
                <h1>Conectamos Talento con Oportunidades</h1>
                <p>TalentBridge es la plataforma líder en outsourcing que une a profesionales talentosos con empresas innovadoras. Descubre tu próxima gran oportunidad o encuentra el talento perfecto para tu equipo.</p>
                <a href="registro.php" class="btn">Comienza Ahora</a>
            </div>
            <div class="hero-image">
                <img src="img/cpbox.jpeg" alt="Profesionales trabajando juntos">
            </div>
        </div>
    </section>

    <section class="features" id="caracteristicas">
        <div class="container">
            <h2>Nuestras Características</h2>
            <div class="feature-grid">
                <div class="feature-item">
                    <i>🔍</i>
                    <h3>Búsqueda Inteligente</h3>
                    <p>Encuentra el talento perfecto o la oportunidad ideal con nuestro sistema de búsqueda avanzado.</p>
                </div>
                <div class="feature-item">
                    <i>📊</i>
                    <h3>Perfiles Detallados</h3>
                    <p>Crea un perfil completo que destaque tus habilidades y experiencia.</p>
                </div>
                <div class="feature-item">
                    <i>🤝</i>
                    <h3>Conexiones Directas</h3>
                    <p>Conecta directamente con empresas o candidatos sin intermediarios.</p>
                </div>
                <div class="feature-item">
                    <i>📈</i>
                    <h3>Seguimiento de Proyectos</h3>
                    <p>Gestiona y haz seguimiento de tus proyectos de outsourcing en un solo lugar.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="testimonials" id="testimonios">
        <div class="container">
            <h2>Lo que dicen nuestros usuarios</h2>
            <div class="testimonial-grid">
                <div class="testimonial-item">
                    <p>"TalentBridge me ayudó a encontrar oportunidades increíbles que se ajustan perfectamente a mis habilidades. ¡Altamente recomendado!"</p>
                    <span class="testimonial-author">- Brian S., Desarrollador Web</span>
                </div>
                <div class="testimonial-item">
                    <p>"Como empresa, hemos encontrado profesionales excepcionales a través de TalentBridge. Ha revolucionado nuestra forma de contratar."</p>
                    <span class="testimonial-author">- Carlos R., CEO de TechSolutions</span>
                </div>
                <div class="testimonial-item">
                    <p>"La plataforma es intuitiva y fácil de usar. He conseguido varios proyectos interesantes desde que me uní."</p>
                    <span class="testimonial-author">- Ana L., Diseñadora Gráfica</span>
                </div>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="container">
            <h2>¿Listo para dar el siguiente paso en tu carrera?</h2>
            <p>Únete a TalentBridge hoy y descubre un mundo de oportunidades.</p>
            <a href="registro.php" class="btn">Regístrate Gratis</a>
        </div>
    </section>

    <footer id="contacto">
        <div class="container footer-content">
            <div class="footer-section">
                <h3>Acerca de TalentBridge</h3>
                <p>Conectamos profesionales talentosos con empresas innovadoras, facilitando el outsourcing y el crecimiento profesional.</p>
            </div>
            <div class="footer-section">
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#caracteristicas">Características</a></li>
                    <li><a href="#testimonios">Testimonios</a></li>
                    <li><a href="login.php">Iniciar Sesión</a></li>
                    <li><a href="registro.php">Registrarse</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contacto</h3>
                <p>Email: info@talentbridge.com</p>
                <p>Teléfono: (123) 456-7890</p>
                <p>Dirección: Calle Principal 123, Ciudad</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 TalentBridge. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>