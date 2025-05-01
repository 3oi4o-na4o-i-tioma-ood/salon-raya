<footer>
    <a href="sign-in.php" class="sign-in-icon">
        <i class="far fa-user"></i>
    </a>

    <div class="row" style="flex-grow: 1; flex-direction: column;">

        <a href="/" class="logo">Райа</a>

        <div class="footer-nav">
            <a href="/#hero">Начало</a>
            <a href="/#services">Услуги</a>
            <a href="za-nas.php">За нас</a>
            <a href="contacts.php">Контакти</a>
        </div>

        <div class="social-links">
            <a href="https://www.facebook.com/profile.php?id=100042636765868&locale=bg_BG" target="_blank"><i
                    class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
    </div>
    <p class="copyright">&copy; 2025 Фризьорски салон Райа. Всички права запазени.</p>
    <style>
        footer {
            opacity: 0.9;
            z-index: 10000;
            min-height: 200px;
            padding: 5px 0;
            margin-top: auto;
            background-color: #fff;
            text-align: center;

            display: flex;
            flex-direction: column;
            padding: 5px 10px;
            position: relative;
        }


        footer .row {
            display: flex;
            width: 100%;
            justify-content: space-around;
            align-items: center;
        }

        footer .social-links a {
            margin: 0 5px;
            font-size: 18px;
        }

        footer .logo {
            /* font-size: 3rem; */
        }

        .footer-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 90%;
            max-width: 400px;
            margin: 0;

            padding: 4px 10px;
            border-radius: 4px;
        }

        .footer-nav a {
            transition: color 0.3s ease;
            color: black;
            margin: 0 3px;
            font-size: 0.8rem;
            text-decoration: none;
        }

        .footer-nav a:hover {
            color: black;
            text-decoration: underline;
        }

        .footer-copyright {
            margin-top: 1rem;
            font-size: 0.9rem;
            color: #666;
            text-align: center;
        }

        .copyright {
            position: absolute;
            bottom: 25px;
            right: 25px;

            font-size: 0.7em;
            margin: 0;
            text-align: center;
            margin-top: 5px;
        }

        .sign-in-icon {
            position: absolute;
            left: 5px;
            bottom: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;

            color: #a484e8;
            font-size: 1.2rem;
            cursor: pointer;
            transition: opacity 0.3s ease;
            opacity: 0.8;
            text-decoration: none;
            background: #fff;
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }


        .sign-in-icon i {
            color: black;
        }

        @media (max-width: 1000px) {
            .social-links {
                margin-bottom: 40px;
            }

            .copyright {
                left: 50%;
                width: 100%;
                transform: translateX(-50%);
                bottom: 10px;
            }

            footer {
                min-height: 250px;
            }

            .sign-in-icon {
                bottom: 30px;
            }
        }

        /* Footer social icons */
        footer .social-links a {
            color: #000;
            margin-right: 15px;
            font-size: 20px;
        }

        footer .social-links a:hover {
            color: #a484e8;
        }

        /* General social links */
        footer .social-links {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        footer .social-links a {
            color: #333;
            margin: 0 10px;
            font-size: 24px;
            text-decoration: none;
            display: inline-block;
            transition: color 0.3s ease;
        }

        footer .social-links a:hover {
            color: #666;
        }


        @media (max-width: 800px) {
            .sign-in-icon {
                order: 3;
                position: absolute;
                left: 15px;
            }
        }

        .sign-in-icon:hover {
            opacity: 1;
            transform: scale(1.1);
        }

        @media (max-width: 768px) {
            .sign-in-icon {
                margin: 0.5rem;
            }
        }
    </style>
</footer>