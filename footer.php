<footer>
    <div class="footer-content">
        <div class="social-links">
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
        </div>
        
        <div class="footer-nav">
            <a href="/#hero">Начало</a>
            <a href="/#services">Услуги</a>
            <a href="za-nas.php">За нас</a>
            <a href="contacts.php">Контакти</a>
        </div>
        
        <a href="sign-in.php" class="sign-in-icon">
            <i class="far fa-user"></i>
        </a>
        
        <p class="copyright">&copy; 2023 Фризьорски салон Райа. Всички права запазени.</p>
    </div>
    <style>
        footer {
            padding: 5px 0;
            margin-top: auto;
        }
        
        .footer-content { 
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            padding: 5px 10px;
            position: relative;
        }
        
        .social-links { 
            margin: 0 10px;
            display: flex;
            justify-content: center;
        }
        
        .social-links a {
            margin: 0 5px;
            font-size: 18px;
        }
        
        .footer-nav {
            display: flex;
            flex-direction: row;
            align-items: center;
            margin: 0 10px;
            background-color: #a484e8;
            padding: 4px 10px;
            border-radius: 4px;
        }
        
        .footer-nav a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
            margin: 0 3px;
            font-size: 0.8rem;
        }
        
        .footer-nav a:hover {
            color: #f0f0f0;
            text-decoration: underline;
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
        
        @media (max-width: 768px) {
            .footer-content {
                flex-direction: column;
                gap: 8px;
            }
            
            .footer-nav {
                order: 2;
            }
            
            .social-links {
                order: 1;
            }
            
            .sign-in-icon {
                order: 3;
            }
            
            .copyright {
                order: 4;
            }
        }
    </style>
</footer>