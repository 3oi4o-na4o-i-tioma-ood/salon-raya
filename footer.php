<footer>
    <div class="footer-content">
        <a href="sign-in.php" class="sign-in-icon">
            <i class="far fa-user"></i>
        </a>

        <div class="center-content">
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
            <p class="copyright">&copy; 2023 Фризьорски салон Райа. Всички права запазени.</p>
        </div>


        <div class="footer-nav">
            <a href="/#hero">Начало</a>
            <a href="/#services">Услуги</a>
            <a href="za-nas.php">За нас</a>
            <a href="contacts.php">Контакти</a>
        </div>



    </div>
    <style>
        footer {
            padding: 5px 0;
            margin-top: auto;
            background-color: #fff;
            text-align: center;
        }

        .footer-content {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            padding: 5px 10px;
            position: relative;
        }

        .center-content {
            display: flex;
            flex-direction: column;
            margin: 0 auto;
        }

        @media (min-width: 800px) {
            .center-content {
                position: absolute;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
            }

        }

        @media (max-width: 800px) {
            .footer-content {
                flex-direction: column-reverse;
            }
        }

        .social-links {
            margin: 0;
            display: flex;
            justify-content: center;
        }

        .social-links a {
            margin: 0 5px;
            font-size: 18px;
        }

        .footer-nav {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0 auto;

            padding: 4px 10px;
            border-radius: 4px;
        }

        @media (min-width: 800px) {
            .footer-nav {
                margin: 0 150px 0 auto;
            }
        }

        .footer-nav a {
            transition: color 0.3s ease;
            color: #a484e8;
            margin: 0 3px;
            font-size: 0.8rem;
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
            font-size: 0.55em;
            color: #a484e8;
            margin: 0;
            width: 100%;
            text-align: center;
            margin-top: 5px;
        }

        .sign-in-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
        }

        .sign-in-icon i {
            color: black;
        }

        @media (max-width: 800px) {

            .footer-nav {
                order: 2;
            }

            .social-links {
                order: 1;
            }


            .copyright {
                order: 4;
            }
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            color: #333;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
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
            margin-bottom: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
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



        .sign-in-icon {
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
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);

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